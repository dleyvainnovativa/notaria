<?php

namespace App\Http\Middleware;

use App\Models\Memorial;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class CheckMemorialAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = User::where('firebase_uid', session('firebase_uid'))->first();

        $memorialParam = $request->route('memorial');

        if (! $memorialParam instanceof Memorial) {
            $memorial = Memorial::where('slug', $memorialParam)->first();
        } else {
            $memorial = $memorialParam;
        }

        if (! $user || ! $memorial || ! $memorial->canAccess($user)) {
            return response()->view('admin.pages.no_permission', [
                'memorial' => $memorial,
                'memorial_slug' => $memorial ? $memorial->slug : null,
                'user' => $user,
            ], 403);
        }

        $routeName = $request->route()->getName();
        $parts = explode('.', $routeName);
        $module = end($parts);

        $moduleMap = [
            'info' => 'info',
            'timeline' => 'timeline',
            'life' => 'life',
            'gallery' => 'gallery',
            'messages' => 'messages',
            'invitations' => 'invitations',
        ];

        if (array_key_exists($module, $moduleMap)) {

            if (! $memorial->canEditModule($user, $moduleMap[$module])) {
                return response()->view('admin.pages.no_permission', [
                    'memorial' => $memorial,
                    'memorial_slug' => $memorial->slug,
                    'module' => $module,
                    'user' => $user,
                ], 403);
            }
        }

        $request->route()->setParameter('current_user', $user);

        return $next($request);
    }
}
