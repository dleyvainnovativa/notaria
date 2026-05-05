<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Memorial;
use App\Models\Tribute;
use Illuminate\Http\Request;

class TributeController extends Controller
{
    /**
     * GET /api/{memorial}/tributes
     */
    public function index(Request $request, Memorial $memorial)
    {
        $user = $request->user();
        if (! $memorial->canAccess($user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json([
            'tributes' => $memorial->tributes()
                ->latest()
                ->get(),
        ]);
    }

    public function store(Request $request, Memorial $memorial)
    {
        $validated = $request->validate([
            'author_name' => ['required', 'string', 'max:100'],
            'message'     => ['required', 'string', 'max:200'],
        ]);

        $user = $request->user();
        // if (! $memorial->canAccess($user)) {
        //     return response()->json(['message' => 'Forbidden'], 403);
        // }

        $tribute = $memorial->tributes()->create([
            'author_name' => $validated['author_name'],
            'message'     => $validated['message'],
            'is_approved' => false, // moderation required
        ]);

        return response()->json([
            'message' => 'Tribute submitted successfully',
            // 'tribute' => $tribute,
        ], 201);
    }

    /**
     * PATCH /api/{memorial}/tributes/{tribute}/approve
     */
    public function approve(Request $request, Memorial $memorial, Tribute $tribute)
    {
        $user = $request->user();
        if (! $memorial->canAccess($user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $this->authorizeTribute($memorial, $tribute);

        $tribute->update([
            'is_approved' => true,
        ]);

        return response()->json([
            'message' => 'Tribute approved',
        ]);
    }

    /**
     * PATCH /api/{memorial}/tributes/{tribute}/reject
     */
    public function reject(Request $request, Memorial $memorial, Tribute $tribute)
    {
        $user = $request->user();
        if (! $memorial->canAccess($user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $this->authorizeTribute($memorial, $tribute);

        $tribute->update([
            'is_approved' => false,
        ]);

        return response()->json([
            'message' => 'Tribute rejected',
        ]);
    }

    /**
     * Ensure tribute belongs to memorial
     */
    private function authorizeTribute(Memorial $memorial, Tribute $tribute): void
    {
        if ($tribute->memorial_id !== $memorial->id) {
            abort(403);
        }
    }
}
