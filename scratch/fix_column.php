<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Pasien;

try {
    DB::statement("ALTER TABLE pasien MODIFY COLUMN golongan_darah VARCHAR(20) NULL");
    echo "SUCCESS: Column 'golongan_darah' changed to VARCHAR(20) NULL." . PHP_EOL;

    // Test saving 'Tidak Tahu' into pasien model
    $pasien = Pasien::first();
    if ($pasien) {
        $pasien->update(['golongan_darah' => 'Tidak Tahu']);
        echo "SUCCESS: Updated pasien ID {$pasien->id} with golongan_darah = 'Tidak Tahu'." . PHP_EOL;
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
}
