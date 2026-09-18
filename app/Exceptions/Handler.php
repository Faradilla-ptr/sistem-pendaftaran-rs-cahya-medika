<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            if ($request->is('login') || $request->is('*/login')) {
                return redirect()->back()->withInput($request->except('password', '_token'))
                    ->withErrors(['email' => 'Sesi login telah diperbarui. Silakan klik Masuk kembali.']);
            }
            return redirect()->back()->withErrors(['error' => 'Sesi Anda telah kedaluwarsa. Silakan muat ulang halaman.']);
        });
    }
}
