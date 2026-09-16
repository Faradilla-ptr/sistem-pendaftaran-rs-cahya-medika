<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): mixed
    {
        if (!Auth::check()) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return redirect()->route('admin.login');
            }
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (empty($roles)) {
            return $next($request);
        }

        if (in_array($user->role, $roles) || (in_array('admin', $roles) && $user->isAdmin())) {
            return $next($request);
        }

        abort(403, 'Akses tidak diizinkan.');
    }
}
