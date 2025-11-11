<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Gunakan: ->middleware('role:admin') atau 'role:admin,teknisi'
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        if (!empty($roles) && !in_array($user->role, $roles, true)) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
