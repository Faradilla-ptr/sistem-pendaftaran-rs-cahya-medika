<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Total Doctors: " . App\Models\Dokter::count() . PHP_EOL;

foreach (App\Models\Poli::all() as $p) {
    $count = App\Models\Dokter::where('poli_id', $p->id)->count();
    echo "- {$p->nama} (ID: {$p->id}): {$count} dokter" . PHP_EOL;
    $doctors = App\Models\Dokter::where('poli_id', $p->id)->get();
    foreach ($doctors as $d) {
        echo "   * {$d->nama_lengkap} ({$d->spesialisasi})" . PHP_EOL;
    }
}
