<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\InvitationMail;
use App\Models\Memorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\MemorialInvitation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class MemorialController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $owned = $user->memorials()->get();
        $invited = Memorial::whereHas('invitations', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
        $memorials = $owned->merge($invited)->unique('id')->values();

        return response()->json($memorials);
    }
    public function info(Request $request, Memorial $memorial)
    {
        $user = $request->user();
        if (! $memorial->canAccess($user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $obj["memorial"] = $memorial;
        if ($memorial->is_public) {
            $memorial->is_public_icon = "fas fa-lock fa-2xl text-success";
            $memorial->is_public_title = "¿Estás seguro de cambiar a Privado?";
            $memorial->is_public_text = "Solo las personas que compartas el link y conozcan la contraseña que establezcas podrán ver tu Memorial";
        } else {
            $memorial->is_public_icon = "fas fa-lock-open fa-2xl text-success";
            $memorial->is_public_title = "¿Estás seguro de cambiar a Público?";
            $memorial->is_public_text = "Todas las personas que conozcan tu link podrán ver tu Memorial";
        }
        $obj["user"] = $user;
        return response()->json($memorial);
    }
    public function update(Request $request, Memorial $memorial)
    {
        $user = $request->user();
        // 1. Authorization: Ensure the user owns this memorial
        if (! $memorial->canAccess($user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        // 2. Validation: Ensure the incoming data is valid
        $validator = Validator::make($request->all(), [
            'deceased_name' => 'required|string|max:255',
            'biography'       => 'nullable|string',
            'birth_date'      => 'required|date',
            'death_date'      => 'required|date|after_or_equal:birth_date',
            'playlist'     => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (!self::extractSpotifyEmbedSrc($value)) {
                        $fail('El código de Spotify no es válido.');
                    }
                }
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422); // 422 Unprocessable Entity is standard for validation errors
        }

        $data = $validator->validated();
        if (!empty($data['playlist'])) {
            $data['playlist'] = self::extractSpotifyEmbedSrc($data['playlist']);
        }
        $memorial->update($data);

        // 4. Return a success response
        return response()->json([
            'message'  => 'Se han actualizado correctamente los datos',
            'memorial' => $memorial,
        ]);
    }
    public function privacy(Request $request, Memorial $memorial)
    {
        $user = $request->user();
        // 1. Authorization: Ensure the user owns this memorial
        if (! $memorial->canAccess($user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }


        // 2. Validation: Ensure the incoming data is valid
        $validator = Validator::make($request->all(), [
            'is_public' => 'required|boolean',
            'access_password'       => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422); // 422 Unprocessable Entity is standard for validation errors
        }


        $data = $validator->validated();
        if (!$data['is_public']) {
            if (empty($data['access_password']) || strlen($data['access_password']) < 6) {
                return response()->json([
                    'message' => 'La contraseña es obligatoria y debe tener al menos 6 caracteres cuando el memorial es privado.',
                    'errors' => [
                        'access_password' => ['Debe tener al menos 6 caracteres y no estar vacía.']
                    ]
                ], 422);
            }
        }
        $data["access_password"] = Hash::make($data["access_password"]);
        $memorial->update($data);
        return response()->json([
            'message'  => 'Se han actualizado correctamente los datos',
            'memorial' => $memorial,
            'data' => $data,
            'success' => true,
        ]);
    }
    public function photo(Request $request, Memorial $memorial)
    {
        $user = $request->user();

        // Authorization
        if (! $memorial->canAccess($user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // Validation
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120', // 5MB
        ]);

        // Delete old image if exists
        if ($memorial->profile_image_url) {
            $oldPath = str_replace('/storage/', '', $memorial->profile_image_url);
            Storage::disk('public')->delete($oldPath);
        }

        // Store image
        $path = $request->file('photo')->store(
            'memorials/profile-images',
            'public'
        );

        // Save URL
        $memorial->update([
            'profile_image_url' => Storage::url($path)
        ]);

        return response()->json([
            'message' => 'Foto actualizada correctamente',
            'image_url' => $memorial->profile_image_url
        ]);
    }
    private static function extractSpotifyEmbedSrc(string $input): ?string
    {
        $input = trim($input);

        // 1️⃣ Extract src if iframe
        if (preg_match('/src="([^"]+)"/i', $input, $matches)) {
            $input = $matches[1];
        }

        // 2️⃣ Match Spotify URL (embed or regular)
        $pattern = '#^https://open\.spotify\.com/(embed/)?(playlist|album|track)/([a-zA-Z0-9]+)#';

        if (!preg_match($pattern, $input, $matches)) {
            return null;
        }

        $type = $matches[2]; // playlist | album | track
        $id   = $matches[3];

        // 3️⃣ Always return canonical embed URL
        return "https://open.spotify.com/embed/{$type}/{$id}";
    }

    public function invitations(Request $request, Memorial $memorial)
    {
        $user = $request->user();
        if (! $memorial->canAccess($user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return response()->json($memorial->invitations()->with('invitedBy')->get());
    }
    public function invite(Request $request, Memorial $memorial)
    {
        $user = $request->user();

        if (! $memorial->canAccess($user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $request->validate([
            'email' => 'required|email',
            'permissions' => 'required|array',
        ]);

        $email = $request->email;
        $permissions = $request->permissions;

        // 🔍 Check if already invited or already collaborator
        $existing = MemorialInvitation::where('memorial_id', $memorial->id)
            ->where('email', $email)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Este correo ya ha sido invitado a este memorial'
            ], 409);
        }

        // 🔍 Check if user already exists
        $invitedUser = User::where('email', $email)->first();

        if ($invitedUser == $user) {
            return response()->json([
                'message' => 'El usuario ya es el propietario de este memorial'
            ], 409);
        }

        // 🔑 Generate secure token
        $token = Str::uuid();

        // 💾 Create invitation

        $invitation = MemorialInvitation::create([
            'memorial_id' => $memorial->id,
            'invited_by' => $user->id,
            'email' => $email,
            'user_id' => $invitedUser?->id,
            'token' => $token,
            'expires_at' => now()->addDays(7),

            'can_edit_info' => $permissions['info'] ?? false,
            'can_edit_timeline' => $permissions['timeline'] ?? false,
            'can_edit_life' => $permissions['life'] ?? false,
            'can_edit_gallery' => $permissions['gallery'] ?? false,
            'can_edit_messages' => $permissions['messages'] ?? false,
        ]);

        // 📩 (Later) Send email here
        // Mail::to($email)->send(new MemorialInvitationMail($invitation));
        Mail::to($email)->send(
            new InvitationMail(route("register"), route("memory", $memorial->slug), $memorial->deceased_name)
        );

        return response()->json([
            'success' => true,
            'message' => 'Invitación enviada correctamente',
            'invitation' => $invitation
        ], 201);
    }
    public function updatePermissions(Request $request, $id)
    {
        $user = $request->user();

        $invitation = MemorialInvitation::findOrFail($id);

        $memorial = $invitation->memorial;

        // 🔐 Only owner can edit permissions
        if ($memorial->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $request->validate([
            'permissions' => 'required|array',
        ]);

        $permissions = $request->permissions;

        $invitation->update([
            'can_edit_info' => $permissions['info'] ?? false,
            'can_edit_timeline' => $permissions['timeline'] ?? false,
            'can_edit_life' => $permissions['life'] ?? false,
            'can_edit_gallery' => $permissions['gallery'] ?? false,
            'can_edit_messages' => $permissions['messages'] ?? false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permissions updated successfully',
            'invitation' => $invitation
        ]);
    }
}
