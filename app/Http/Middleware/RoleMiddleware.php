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

        // Allow if user's exact role is allowed, or if 'admin' role is requested and user is an admin-type role (admin, pendaftaran, rekam_medis)
        if (in_array($user->role, $roles) || (in_array('admin', $roles) && $user->isAdmin())) {
            return $next($request);
        }

        // Instead of throwing a 403 error page, smoothly redirect user to their authorized dashboard
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('info', 'Anda dialihkan ke Dashboard Admin / Loket.');
        }

        return redirect()->route('pasien.dashboard')->with('info', 'Anda dialihkan ke Dashboard Pasien.');
    }
}
