<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\Dokter;
use App\Models\Pendaftaran;
use App\Http\Controllers\PendaftaranController;
use Illuminate\Http\Request;

$user = User::where('role', 'pasien')->first();
Auth::login($user);
$pasien = $user->pasien;

$todayStr = today()->format('Y-m-d');
Pendaftaran::where('pasien_id', $pasien->id)->whereDate('tanggal_kunjungan', $todayStr)->delete();

$polis = Poli::all();
$controller = app()->make(PendaftaranController::class);

for ($i = 0; $i < 5; $i++) {
    $pObj = $polis[$i % count($polis)];
    $dObj = Dokter::where('poli_id', $pObj->id)->first();

    $req = Request::create('/pendaftaran', 'POST', [
        'nik'               => $pasien->nik,
        'nama_lengkap'      => $pasien->nama_lengkap,
        'tempat_lahir'      => 'Bondowoso',
        'tanggal_lahir'     => '1990-01-01',
        'jenis_kelamin'     => 'L',
        'pekerjaan'          => 'Wiraswasta',
        'agama'              => 'Islam',
        'pendidikan'         => 'Sarjana',
        'status_pernikahan'  => 'Kawin',
        'alamat'            => 'Jl. Mastrip No. 25',
        'kelurahan'         => 'Dabasah',
        'kecamatan'         => 'Bondowoso',
        'kabupaten'         => 'Bondowoso',
        'provinsi'          => 'Jawa Timur',
        'warga_negara'      => 'WNI',
        'golongan_darah'    => 'Tidak Tahu',
        'no_hp'             => '081234567890',
        'poli_id'           => $pObj->id,
        'dokter_id'         => $dObj->id,
        'tanggal_kunjungan' => $todayStr,
        'jam_kunjungan'     => '09:00',
        'jenis_kunjungan'   => 'baru',
        'keluhan'           => 'Pemeriksaan ' . ($i + 1),
    ]);

    $res = $controller->store($req);
    $errors = session('errors');
    if ($errors && $errors->any()) {
        echo "[Attempt " . ($i + 1) . "] REJECTED: " . implode(', ', $errors->all()) . PHP_EOL;
    } else {
        echo "[Attempt " . ($i + 1) . "] SUCCESS: Registered to Poli " . $pObj->nama . PHP_EOL;
    }
}

$countToday = Pendaftaran::where('pasien_id', $pasien->id)->whereDate('tanggal_kunjungan', $todayStr)->count();
echo PHP_EOL . "TOTAL SUCCESSFUL REGISTRATIONS CREATED IN DB: " . $countToday . PHP_EOL;
