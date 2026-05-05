<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Kreait\Firebase\JWT\IdTokenVerifier;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgetMail;
use App\Mail\WelcomeCompletedMail;
use App\Models\MemorialInvitation;
use Exception;

class AuthController extends Controller
{
    protected FirebaseAuth $auth;

    public function __construct(FirebaseAuth $auth)
    {
        $this->auth = $auth;
    }

    public function firebaseLogin(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        try {
            $verifier = IdTokenVerifier::createWithProjectId(
                env("VITE_FIREBASE_PROJECT_ID")
            );

            $verifiedToken = $verifier->verifyIdTokenWithLeeway(
                $request->token,
                10 // seconds
            );
            $firebaseUid = $verifiedToken->payload()['sub'];



            $firebaseUser = $this->auth->getUser($firebaseUid);

            $user = User::where('firebase_uid', $firebaseUid)
                ->orWhere('email', $firebaseUser->email)
                ->first();

            if (!$user) {
                // New user
                $user = User::create([
                    'firebase_uid' => $firebaseUid,
                    'name' => $firebaseUser->displayName ?? 'Usuario',
                    'email' => $firebaseUser->email,
                ]);

                MemorialInvitation::where('email', $user->email)->update([
                    'user_id' => $user->id,
                ]);

                Mail::to($user->email)->send(
                    new WelcomeCompletedMail($user, null)
                );
            } else {
                if (!$user->firebase_uid) {
                    $user->update([
                        'firebase_uid' => $firebaseUid,
                    ]);
                }
            }

            session([
                'firebase_uid' => $firebaseUid,
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
            ]);

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 401);
        }
    }

    public function forget(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            $email = $request->email;
            $user = User::where('email', $email)->first();
            if (!$user) {
                throw new Exception("Correo no asociado a usuario");
            }
            $auth = app('firebase.auth');

            $link = $auth->getPasswordResetLink($email);

            Mail::to($email)->send(
                new ForgetMail($link, $email)
            );

            return response()->json([
                'success' => true,
                'message' => 'Correo enviado, revisa tu bandeja'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        session()->flush();
        return redirect('/login');
    }
}
