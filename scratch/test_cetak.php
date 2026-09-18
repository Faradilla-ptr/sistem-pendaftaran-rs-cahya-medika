<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $user = \App\Models\User::where('role', 'admin')->first();
    if ($user) \Illuminate\Support\Facades\Auth::login($user);
    $pendaftaran = \App\Models\Pendaftaran::with('pasien')->first();
    $html = view('admin.pendaftaran.cetak_formulir', compact('pendaftaran'))->render();
    echo "CETAK FORMULIR BLADE RENDER SUCCESSFUL! Length: " . strlen($html) . " bytes\n";
} catch (\Throwable $e) {
    echo "CETAK FORMULIR RENDER ERROR: " . $e->getMessage() . "\n";
}
