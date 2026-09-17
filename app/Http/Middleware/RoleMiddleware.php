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
            if ($request->is('rekam-medis*')) {
                return redirect()->route('rekam_medis.login');
            }
            if ($request->is('pendaftaran*') || $request->is('admin*')) {
                return redirect()->route('pendaftaran.login');
            }
            return redirect()->route('pasien.login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!empty($roles) && !in_array($user->role, $roles)) {
            if ($user->role === 'rekam_medis') {
                return redirect()->route('rekam_medis.dashboard');
            }
            if ($user->role === 'pendaftaran' || $user->role === 'admin') {
                return redirect()->route('pendaftaran.dashboard');
            }
            return redirect()->route('pasien.dashboard');
        }

        return $next($request);
    }
}
