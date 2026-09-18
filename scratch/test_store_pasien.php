<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\Dokter;
use App\Http\Controllers\PendaftaranController;
use Illuminate\Http\Request;

$user = User::where('role', 'pasien')->first();
if (!$user) {
    echo "No pasien user found!" . PHP_EOL;
    exit;
}

Auth::login($user);

$poli = Poli::first();
$dokter = Dokter::where('poli_id', $poli->id)->first();

$req = Request::create('/pendaftaran', 'POST', [
    'nik'               => '3512026203050002',
    'nama_lengkap'      => 'ANNISA IKRIMATIUS SOLEHA',
    'tempat_lahir'      => 'Situbondo',
    'tanggal_lahir'     => '1995-05-15',
    'jenis_kelamin'     => 'P',
    'pekerjaan'          => 'Wiraswasta',
    'agama'              => 'Islam',
    'pendidikan'         => 'S1',
    'status_pernikahan'  => 'Menikah',
    'alamat'            => 'Jl. Panglima Sudirman No. 12',
    'kelurahan'         => 'Dabasah',
    'kecamatan'         => 'Bondowoso',
    'kabupaten'         => 'Bondowoso',
    'provinsi'          => 'Jawa Timur',
    'warga_negara'      => 'WNI',
    'golongan_darah'    => 'Tidak Tahu',
    'no_hp'             => '081234567890',
    'poli_id'           => $poli->id,
    'dokter_id'         => $dokter->id,
    'tanggal_kunjungan' => today()->format('Y-m-d'),
    'jam_kunjungan'     => '09:00',
    'jenis_kunjungan'   => 'baru',
    'keluhan'           => 'Pemeriksaan rutin kesehatan',
]);

$controller = app()->make(PendaftaranController::class);

try {
    $res = $controller->store($req);
    echo "SUCCESS_PENDAFTARAN_STORE! Redirect to: " . $res->getTargetUrl() . PHP_EOL;
    $p = Pasien::where('nik', '3512026203050002')->first();
    if ($p) {
        echo "Pasien saved! Golongan Darah: " . $p->golongan_darah . PHP_EOL;
    }
} catch (\Exception $e) {
    echo "ERROR_PENDAFTARAN_STORE: " . $e->getMessage() . PHP_EOL;
}
