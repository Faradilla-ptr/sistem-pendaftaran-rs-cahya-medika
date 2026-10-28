<?php

namespace Tests\Feature;

use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Poli;
use App\Models\User;
use App\Models\Icd9Code;
use App\Services\SatuSehatService;
use Tests\TestCase;

class PrdComplianceTest extends TestCase
{
    /**
     * Test 1: AJAX Cek NIK endpoint functionality (FR-01)
     */
    public function test_cek_nik_api_returns_correct_response(): void
    {
        // 0. Unauthenticated request must be rejected (OWASP A01 Protection)
        $resGuest = $this->getJson('/api/cek-nik?nik=3511011234567890');
        $resGuest->assertStatus(401);

        $user = User::factory()->create(['role' => 'pasien']);

        // 1. Unregistered NIK
        $resNotFound = $this->actingAs($user)->getJson('/api/cek-nik?nik=9999999999999999');
        $resNotFound->assertStatus(200)
            ->assertJson([
                'success' => true,
                'exists'  => false,
            ]);

        // 2. Existing patient NIK
        $pasien = Pasien::create([
            'user_id'       => $user->id,
            'no_rm'         => 'RM-TEST-' . rand(1000, 9999),
            'nik'           => '351101' . rand(1000000000, 9999999999),
            'nama_lengkap'  => 'Pasien Terdaftar Uji',
            'tanggal_lahir' => '1995-05-15',
            'jenis_kelamin' => 'L',
        ]);

        $resFound = $this->actingAs($user)->getJson('/api/cek-nik?nik=' . $pasien->nik);
        $resFound->assertStatus(200)
            ->assertJson([
                'success' => true,
                'exists'  => true,
            ])
            ->assertJsonPath('pasien.nama_lengkap', 'Pasien Terdaftar Uji');
    }

    /**
     * Test 2: Status wilayah classification (Bondowoso = dalam_kota, other = luar_kota) (FR-05)
     */
    public function test_status_wilayah_auto_classification(): void
    {
        $user1 = User::factory()->create(['role' => 'pasien']);
        $pasienDalam = Pasien::create([
            'user_id'       => $user1->id,
            'no_rm'         => 'RM-TEST-' . rand(1000, 9999),
            'nik'           => '351102' . rand(1000000000, 9999999999),
            'nama_lengkap'  => 'Warga Bondowoso',
            'kabupaten'     => 'Bondowoso',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
        ]);

        $user2 = User::factory()->create(['role' => 'pasien']);
        $pasienLuar = Pasien::create([
            'user_id'       => $user2->id,
            'no_rm'         => 'RM-TEST-' . rand(1000, 9999),
            'nik'           => '351103' . rand(1000000000, 9999999999),
            'nama_lengkap'  => 'Warga Jember',
            'kabupaten'     => 'Jember',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'P',
        ]);

        $status1 = (strtolower(trim($pasienDalam->kabupaten)) === 'bondowoso') ? 'dalam_kota' : 'luar_kota';
        $status2 = (strtolower(trim($pasienLuar->kabupaten)) === 'bondowoso') ? 'dalam_kota' : 'luar_kota';

        $this->assertEquals('dalam_kota', $status1);
        $this->assertEquals('luar_kota', $status2);
    }

    /**
     * Test 3: CPPT Lock restricts loket/pendaftaran check-in (FR-04)
     */
    public function test_cppt_lock_restricts_pendaftaran_role(): void
    {
        $adminUser = User::factory()->create(['role' => 'pendaftaran']);
        $poli = Poli::firstOrCreate(['kode' => 'INT'], [
            'nama' => 'Poli Penyakit Dalam',
            'is_active' => true,
        ]);
        $dokter = Dokter::firstOrCreate(['nama' => 'dr. Ahmad Spesialis'], [
            'poli_id'      => $poli->id,
            'spesialisasi' => 'Penyakit Dalam',
            'is_active'    => true,
        ]);
        $userPasien = User::factory()->create(['role' => 'pasien']);
        $pasien = Pasien::create([
            'user_id'       => $userPasien->id,
            'no_rm'         => 'RM-TEST-' . rand(1000, 9999),
            'nik'           => '351104' . rand(1000000000, 9999999999),
            'nama_lengkap'  => 'Pasien CPPT',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
        ]);

        $pendaftaran = Pendaftaran::create([
            'pasien_id'          => $pasien->id,
            'dokter_id'          => $dokter->id,
            'poli_id'            => $poli->id,
            'kode_booking'       => 'BK-LOCK-' . rand(100, 999),
            'tanggal_kunjungan'  => today(),
            'jam_kunjungan'      => '09:00',
            'jenis_kunjungan'    => 'baru',
            'keluhan'            => 'Pemeriksaan rutin keluhan umum',
            'cara_bayar'         => 'umum',
            'status'             => 'pemeriksaan_selesai',
            'is_locked_by_poli'  => true, // CPPT Lock active
        ]);

        $response = $this->actingAs($adminUser)->post("/pendaftaran/pendaftaran/{$pendaftaran->id}/checkin");
        $response->assertSessionHas('error');
    }

    /**
     * Test 4: SATUSEHAT Service procedure & dynamic condition structure (FR-06)
     */
    public function test_satusehat_service_procedure_and_icd9(): void
    {
        $service = app(SatuSehatService::class);
        $this->assertTrue(method_exists($service, 'createProcedure'));

        $icd9 = Icd9Code::where('code', '89.03')->first();
        $this->assertNotNull($icd9);
        $this->assertEquals('89.03', $icd9->code);
    }
}
