<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\Admin\AdminController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - RS Cahya Medika Bondowoso
|--------------------------------------------------------------------------
*/

// ==================== HOME ====================
Route::get('/', function () {
    return view('welcome');
})->name('home');

// General /dashboard route redirector
Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    /** @var User $user */
    $user = Auth::user();
    if ($user->role === 'rekam_medis') {
        return redirect()->route('rekam_medis.dashboard');
    }
    if ($user->role === 'pendaftaran' || $user->role === 'admin') {
        return redirect()->route('pendaftaran.dashboard');
    }
    return redirect()->route('pasien.dashboard');
})->name('dashboard');

// General /admin shortcut redirector
Route::get('/admin', function () {
    if (!Auth::check()) {
        return redirect()->route('pendaftaran.login');
    }
    /** @var User $user */
    $user = Auth::user();
    if ($user->role === 'rekam_medis') {
        return redirect()->route('rekam_medis.dashboard');
    }
    if ($user->role === 'pendaftaran' || $user->role === 'admin') {
        return redirect()->route('pendaftaran.dashboard');
    }
    return redirect()->route('pasien.dashboard');
});

// General /pendaftaran shortcut redirector
Route::get('/pendaftaran', function () {
    if (!Auth::check()) {
        return redirect()->route('pendaftaran.login');
    }
    /** @var User $user */
    $user = Auth::user();
    if ($user->role === 'rekam_medis') {
        return redirect()->route('rekam_medis.dashboard');
    }
    if ($user->role === 'pasien') {
        return redirect()->route('pasien.dashboard');
    }
    return redirect()->route('pendaftaran.dashboard');
});

// General /rekam-medis shortcut redirector
Route::get('/rekam-medis', function () {
    if (!Auth::check()) {
        return redirect()->route('rekam_medis.login');
    }
    /** @var User $user */
    $user = Auth::user();
    if ($user->role === 'pendaftaran' || $user->role === 'admin') {
        return redirect()->route('pendaftaran.dashboard');
    }
    if ($user->role === 'pasien') {
        return redirect()->route('pasien.dashboard');
    }
    return redirect()->route('rekam_medis.dashboard');
});

// ==================== AUTH ====================
Route::middleware('guest')->group(function () {
    // 1. Pasien Login
    Route::get('/login', function() { return redirect()->route('pasien.login'); })->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/pasien/login', [AuthController::class, 'showLogin'])->name('pasien.login');
    Route::post('/pasien/login', [AuthController::class, 'login'])->name('pasien.login.post');
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register'])->name('register.post');

    // 2. Pendaftaran & Loket Login
    Route::get('/pendaftaran/login', [AuthController::class, 'showAdminLogin'])->name('pendaftaran.login');
    Route::post('/pendaftaran/login', [AuthController::class, 'login'])->name('pendaftaran.login.post');
    Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');

    // 3. Rekam Medis Login
    Route::get('/rekam-medis/login', [AuthController::class, 'showRekamMedisLogin'])->name('rekam_medis.login');
    Route::post('/rekam-medis/login', [AuthController::class, 'login'])->name('rekam_medis.login.post');
});

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::match(['get', 'post'], '/pasien/logout', [AuthController::class, 'logout'])->name('pasien.logout')->middleware('auth');
Route::match(['get', 'post'], '/pendaftaran/logout', [AuthController::class, 'logout'])->name('pendaftaran.logout')->middleware('auth');
Route::match(['get', 'post'], '/rekam-medis/logout', [AuthController::class, 'logout'])->name('rekam_medis.logout')->middleware('auth');
Route::match(['get', 'post'], '/admin/logout', [AuthController::class, 'logout'])->name('admin.logout')->middleware('auth');

// ==================== PASIEN ROUTES ====================
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->name('pasien.')->group(function () {
    Route::get('/dashboard', [PasienController::class, 'dashboard'])->name('dashboard');
    Route::get('/profil', [PasienController::class, 'profil'])->name('profil');
    Route::put('/profil', [PasienController::class, 'updateProfil'])->name('profil.update');
    Route::post('/ganti-password', [PasienController::class, 'gantiPassword'])->name('password.update');
    Route::get('/riwayat', [PasienController::class, 'riwayat'])->name('riwayat');

    // Pendaftaran
    Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::get('/pendaftaran/baru', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
    Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
    Route::get('/pendaftaran/{pendaftaran}', [PendaftaranController::class, 'show'])->name('pendaftaran.show');
    Route::post('/pendaftaran/{pendaftaran}/batal', [PendaftaranController::class, 'cancel'])->name('pendaftaran.cancel');

    // AJAX
    Route::get('/api/dokter-by-poli', [PendaftaranController::class, 'getDokterByPoli'])->name('api.dokter');
    Route::get('/api/jadwal-dokter', [PendaftaranController::class, 'getJadwalDokter'])->name('api.jadwal');
    Route::post('/ocr-ktp', [PasienController::class, 'ocrKtp'])->name('ocr-ktp');
});

// ==================== PENDAFTARAN & LOKET ROUTES (PREFIX: pendaftaran/) ====================
Route::middleware(['auth', 'role:admin,pendaftaran'])->prefix('pendaftaran')->name('pendaftaran.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Pasien
    Route::get('/pasien', [AdminController::class, 'pasienIndex'])->name('pasien.index');
    Route::get('/pasien/{pasien}', [AdminController::class, 'pasienShow'])->name('pasien.show');
    Route::get('/pasien/{pasien}/edit', [AdminController::class, 'pasienEdit'])->name('pasien.edit');
    Route::put('/pasien/{pasien}', [AdminController::class, 'pasienUpdate'])->name('pasien.update');

    // Pendaftaran & Check-in Workflow
    Route::get('/pendaftaran', [AdminController::class, 'pendaftaranIndex'])->name('pendaftaran.index');
    Route::get('/pendaftaran/{pendaftaran}', [AdminController::class, 'pendaftaranShow'])->name('pendaftaran.show');
    Route::get('/pendaftaran/{pendaftaran}/pdf', [AdminController::class, 'pendaftaranCetakPdf'])->name('pendaftaran.pdf');
    Route::get('/pendaftaran/{pendaftaran}/cetak-formulir', [AdminController::class, 'cetakFormulir'])->name('pendaftaran.cetak-formulir');
    Route::post('/pendaftaran/{pendaftaran}/checkin', [AdminController::class, 'checkin'])->name('pendaftaran.checkin');
    Route::post('/pendaftaran/{pendaftaran}/settle-deposit', [AdminController::class, 'settleDeposit'])->name('pendaftaran.settle-deposit');
    Route::post('/pendaftaran/{pendaftaran}/finalize-rekam-medis', [AdminController::class, 'finalizeRekamMedis'])->name('pendaftaran.finalize-rekam-medis');
    Route::get('/api/lookup-booking', [AdminController::class, 'lookupBooking'])->name('api.lookup-booking');
    Route::patch('/pendaftaran/{pendaftaran}/status', [AdminController::class, 'pendaftaranUpdateStatus'])->name('pendaftaran.status');
    Route::post('/pendaftaran/{pendaftaran}/vital', [AdminController::class, 'pendaftaranUpdateVital'])->name('pendaftaran.vital');

    // Dokter
    Route::get('/dokter', [AdminController::class, 'dokterIndex'])->name('dokter.index');
    Route::get('/dokter/tambah', [AdminController::class, 'dokterCreate'])->name('dokter.create');
    Route::post('/dokter', [AdminController::class, 'dokterStore'])->name('dokter.store');
    Route::get('/dokter/{dokter}/edit', [AdminController::class, 'dokterEdit'])->name('dokter.edit');
    Route::put('/dokter/{dokter}', [AdminController::class, 'dokterUpdate'])->name('dokter.update');

    // Poli
    Route::get('/poli', [AdminController::class, 'poliIndex'])->name('poli.index');
    Route::post('/poli', [AdminController::class, 'poliStore'])->name('poli.store');

    // SatuSehat
    Route::get('/satusehat', [AdminController::class, 'satusehatStatus'])->name('satusehat.status');
    Route::get('/satusehat/test-koneksi', [AdminController::class, 'satusehatTestKoneksi'])->name('satusehat.test');
    Route::get('/satusehat/cari-pasien',  [AdminController::class, 'satusehatCariPasien'])->name('satusehat.cari-pasien');
    Route::get('/satusehat/cari-dokter',  [AdminController::class, 'satusehatCariDokter'])->name('satusehat.cari-dokter');
    Route::get('/satusehat/cari-wilayah', [AdminController::class, 'satusehatCariWilayah'])->name('satusehat.wilayah');
    Route::post('/satusehat/{pendaftaran}/sync', [AdminController::class, 'satusehatSync'])->name('satusehat.sync');

    // Chart AJAX
    Route::get('/api/chart-data', [AdminController::class, 'dashboardChartData'])->name('api.chart');

    // Laporan
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
    Route::get('/laporan/export-excel', [AdminController::class, 'laporanExportExcel'])->name('laporan.excel');
    Route::get('/laporan/export-pdf', [AdminController::class, 'laporanExportPdf'])->name('laporan.pdf');
});

// ==================== REKAM MEDIS ROUTES (PREFIX: rekam-medis/) ====================
Route::middleware(['auth', 'role:admin,rekam_medis'])->prefix('rekam-medis')->name('rekam_medis.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Pasien & Rekam Medis
    Route::get('/pasien', [AdminController::class, 'pasienIndex'])->name('pasien.index');
    Route::get('/pasien/{pasien}', [AdminController::class, 'pasienShow'])->name('pasien.show');
    Route::get('/pasien/{pasien}/edit', [AdminController::class, 'pasienEdit'])->name('pasien.edit');
    Route::put('/pasien/{pasien}', [AdminController::class, 'pasienUpdate'])->name('pasien.update');

    // Pendaftaran & Antrean
    Route::get('/pendaftaran', [AdminController::class, 'pendaftaranIndex'])->name('pendaftaran.index');
    Route::get('/pendaftaran/{pendaftaran}', [AdminController::class, 'pendaftaranShow'])->name('pendaftaran.show');
    Route::get('/pendaftaran/{pendaftaran}/pdf', [AdminController::class, 'pendaftaranCetakPdf'])->name('pendaftaran.pdf');
    Route::get('/pendaftaran/{pendaftaran}/cetak-formulir', [AdminController::class, 'cetakFormulir'])->name('pendaftaran.cetak-formulir');
    Route::post('/pendaftaran/{pendaftaran}/checkin', [AdminController::class, 'checkin'])->name('pendaftaran.checkin');
    Route::post('/pendaftaran/{pendaftaran}/settle-deposit', [AdminController::class, 'settleDeposit'])->name('pendaftaran.settle-deposit');
    Route::post('/pendaftaran/{pendaftaran}/finalize', [AdminController::class, 'finalizeRekamMedis'])->name('pendaftaran.finalize');
    Route::post('/pendaftaran/{pendaftaran}/finalize-rekam-medis', [AdminController::class, 'finalizeRekamMedis'])->name('pendaftaran.finalize-rekam-medis');
    Route::patch('/pendaftaran/{pendaftaran}/status', [AdminController::class, 'pendaftaranUpdateStatus'])->name('pendaftaran.status');
    Route::post('/pendaftaran/{pendaftaran}/vital', [AdminController::class, 'pendaftaranUpdateVital'])->name('pendaftaran.vital');

    // Dokter & Poli
    Route::get('/dokter', [AdminController::class, 'dokterIndex'])->name('dokter.index');
    Route::get('/poli', [AdminController::class, 'poliIndex'])->name('poli.index');

    // SatuSehat
    Route::get('/satusehat', [AdminController::class, 'satusehatStatus'])->name('satusehat.status');
    Route::get('/satusehat/test-koneksi', [AdminController::class, 'satusehatTestKoneksi'])->name('satusehat.test');
    Route::get('/satusehat/cari-pasien',  [AdminController::class, 'satusehatCariPasien'])->name('satusehat.cari-pasien');
    Route::get('/satusehat/cari-dokter',  [AdminController::class, 'satusehatCariDokter'])->name('satusehat.cari-dokter');
    Route::get('/satusehat/cari-wilayah', [AdminController::class, 'satusehatCariWilayah'])->name('satusehat.wilayah');
    Route::post('/satusehat/{pendaftaran}/sync', [AdminController::class, 'satusehatSync'])->name('satusehat.sync');

    // Chart AJAX & Laporan
    Route::get('/api/chart-data', [AdminController::class, 'dashboardChartData'])->name('api.chart');
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
    Route::get('/laporan/export-excel', [AdminController::class, 'laporanExportExcel'])->name('laporan.excel');
    Route::get('/laporan/export-pdf', [AdminController::class, 'laporanExportPdf'])->name('laporan.pdf');
});

// ==================== BACKWARD COMPATIBLE ADMIN ROUTES (PREFIX: admin/) ====================
Route::middleware(['auth', 'role:admin,pendaftaran,rekam_medis'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/pasien', [AdminController::class, 'pasienIndex'])->name('pasien.index');
    Route::get('/pasien/{pasien}', [AdminController::class, 'pasienShow'])->name('pasien.show');
    Route::get('/pasien/{pasien}/edit', [AdminController::class, 'pasienEdit'])->name('pasien.edit');
    Route::put('/pasien/{pasien}', [AdminController::class, 'pasienUpdate'])->name('pasien.update');
    Route::get('/pendaftaran', [AdminController::class, 'pendaftaranIndex'])->name('pendaftaran.index');
    Route::get('/pendaftaran/{pendaftaran}', [AdminController::class, 'pendaftaranShow'])->name('pendaftaran.show');
    Route::get('/pendaftaran/{pendaftaran}/pdf', [AdminController::class, 'pendaftaranCetakPdf'])->name('pendaftaran.pdf');
    Route::get('/pendaftaran/{pendaftaran}/cetak-formulir', [AdminController::class, 'cetakFormulir'])->name('pendaftaran.cetak-formulir');
    Route::post('/pendaftaran/{pendaftaran}/checkin', [AdminController::class, 'checkin'])->name('pendaftaran.checkin');
    Route::post('/pendaftaran/{pendaftaran}/settle-deposit', [AdminController::class, 'settleDeposit'])->name('pendaftaran.settle-deposit');
    Route::post('/pendaftaran/{pendaftaran}/finalize-rekam-medis', [AdminController::class, 'finalizeRekamMedis'])->name('pendaftaran.finalize-rekam-medis');
    Route::get('/api/lookup-booking', [AdminController::class, 'lookupBooking'])->name('api.lookup-booking');
    Route::patch('/pendaftaran/{pendaftaran}/status', [AdminController::class, 'pendaftaranUpdateStatus'])->name('pendaftaran.status');
    Route::post('/pendaftaran/{pendaftaran}/vital', [AdminController::class, 'pendaftaranUpdateVital'])->name('pendaftaran.vital');
    Route::get('/dokter', [AdminController::class, 'dokterIndex'])->name('dokter.index');
    Route::get('/dokter/tambah', [AdminController::class, 'dokterCreate'])->name('dokter.create');
    Route::post('/dokter', [AdminController::class, 'dokterStore'])->name('dokter.store');
    Route::get('/dokter/{dokter}/edit', [AdminController::class, 'dokterEdit'])->name('dokter.edit');
    Route::put('/dokter/{dokter}', [AdminController::class, 'dokterUpdate'])->name('dokter.update');
    Route::get('/poli', [AdminController::class, 'poliIndex'])->name('poli.index');
    Route::post('/poli', [AdminController::class, 'poliStore'])->name('poli.store');
    Route::get('/satusehat', [AdminController::class, 'satusehatStatus'])->name('satusehat.status');
    Route::get('/satusehat/test-koneksi', [AdminController::class, 'satusehatTestKoneksi'])->name('satusehat.test');
    Route::get('/satusehat/cari-pasien',  [AdminController::class, 'satusehatCariPasien'])->name('satusehat.cari-pasien');
    Route::get('/satusehat/cari-dokter',  [AdminController::class, 'satusehatCariDokter'])->name('satusehat.cari-dokter');
    Route::get('/satusehat/cari-wilayah', [AdminController::class, 'satusehatCariWilayah'])->name('satusehat.wilayah');
    Route::post('/satusehat/{pendaftaran}/sync', [AdminController::class, 'satusehatSync'])->name('satusehat.sync');
    Route::get('/api/chart-data', [AdminController::class, 'dashboardChartData'])->name('api.chart');
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
    Route::get('/laporan/export-excel', [AdminController::class, 'laporanExportExcel'])->name('laporan.excel');
    Route::get('/laporan/export-pdf', [AdminController::class, 'laporanExportPdf'])->name('laporan.pdf');
});