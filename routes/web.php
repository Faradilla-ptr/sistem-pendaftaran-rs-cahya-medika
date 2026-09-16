<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\Admin\AdminController;
use App\Models\User;
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

// ==================== AUTH ====================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register'])->name('register.post');

    Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
});

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::match(['get', 'post'], '/admin/logout', [AuthController::class, 'logout'])->name('admin.logout')->middleware('auth');

// ==================== PASIEN ====================
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
});

// ==================== ADMIN ====================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Pasien
    Route::get('/pasien', [AdminController::class, 'pasienIndex'])->name('pasien.index');
    Route::get('/pasien/{pasien}', [AdminController::class, 'pasienShow'])->name('pasien.show');
    Route::get('/pasien/{pasien}/edit', [AdminController::class, 'pasienEdit'])->name('pasien.edit');
    Route::put('/pasien/{pasien}', [AdminController::class, 'pasienUpdate'])->name('pasien.update');

    // Pendaftaran
    Route::get('/pendaftaran', [AdminController::class, 'pendaftaranIndex'])->name('pendaftaran.index');
    Route::get('/pendaftaran/{pendaftaran}', [AdminController::class, 'pendaftaranShow'])->name('pendaftaran.show');
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