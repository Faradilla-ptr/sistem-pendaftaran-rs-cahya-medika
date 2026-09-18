<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $user = \App\Models\User::where('role', 'pasien')->first();
    if ($user) \Illuminate\Support\Facades\Auth::login($user);
    $pasien = \App\Models\Pasien::first();
    $poli = \App\Models\Poli::all();
    $html = view('pasien.pendaftaran.create', compact('pasien', 'poli'))->render();
    echo "BLADE RENDER SUCCESSFUL! Length: " . strlen($html) . " bytes\n";
} catch (\Throwable $e) {
    echo "BLADE RENDER ERROR: " . $e->getMessage() . "\n";
}
