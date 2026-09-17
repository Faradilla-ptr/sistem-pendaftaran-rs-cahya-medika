<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                /** @var \App\Models\User $user */
                $user = Auth::guard($guard)->user();

                if ($user->role === 'rekam_medis') {
                    return redirect()->route('rekam_medis.dashboard');
                }
                if ($user->role === 'pendaftaran' || $user->role === 'admin') {
                    return redirect()->route('pendaftaran.dashboard');
                }
                return redirect()->route('pasien.dashboard');
            }
        }

        return $next($request);
    }
}
