<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FirebaseAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('firebase_uid')) {
            $redirect = $request->query('redirect');
            return redirect('/login' . ($redirect ? '?redirect=' . $redirect : ''));
        }

        return $next($request);
    }
}
