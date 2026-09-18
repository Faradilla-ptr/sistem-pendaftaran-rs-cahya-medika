<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$controller = app()->make(App\Http\Controllers\PendaftaranController::class);

// Test 1: Kamis (Thursday) 2026-09-17
$reqKamis = Illuminate\Http\Request::create('/api/dokter-by-poli', 'GET', ['poli_id' => 1, 'tanggal' => '2026-09-17']);
$resKamis = json_decode($controller->getDokterByPoli($reqKamis)->getContent(), true);

echo "--- POLI UMUM (ID 1) - KAMIS (2026-09-17) ---" . PHP_EOL;
echo "Jumlah Dokter Bertugas: " . count($resKamis) . PHP_EOL;
foreach ($resKamis as $d) {
    echo " - " . $d['nama'] . " (" . $d['jam_praktik'] . ")" . PHP_EOL;
}

// Test 2: Minggu (Sunday) 2026-09-20
$reqMinggu = Illuminate\Http\Request::create('/api/dokter-by-poli', 'GET', ['poli_id' => 1, 'tanggal' => '2026-09-20']);
$resMinggu = json_decode($controller->getDokterByPoli($reqMinggu)->getContent(), true);

echo PHP_EOL . "--- POLI UMUM (ID 1) - MINGGU (2026-09-20) ---" . PHP_EOL;
echo "Jumlah Dokter Bertugas: " . count($resMinggu) . PHP_EOL;
foreach ($resMinggu as $d) {
    echo " - " . $d['nama'] . " (" . $d['jam_praktik'] . ")" . PHP_EOL;
}
