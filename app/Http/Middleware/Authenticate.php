<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        if ($request->is('rekam-medis*')) {
            return route('rekam_medis.login');
        }

        if ($request->is('pendaftaran*') || $request->is('admin*')) {
            return route('pendaftaran.login');
        }

        return route('pasien.login');
    }
}
