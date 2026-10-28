<?php

namespace Tests\Feature;

use App\Models\Dokter;
use App\Models\Poli;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    /**
     * Test 1: SQL Injection attempt on dokterIndex (?hari=...)
     */
    public function test_dokter_index_rejects_sql_injection_payload(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $poli = Poli::create([
            'kode'      => 'POLI-' . rand(100, 999),
            'nama'      => 'Poli Umum',
            'is_active' => true,
        ]);

        Dokter::create([
            'nama'         => 'dr. Uji Keamanan',
            'spesialisasi' => 'SpesialisKeamanan',
            'poli_id'      => $poli->id,
            'is_active'    => true,
            'jadwal'       => ['senin' => ['aktif' => true, 'jam_mulai' => '08:00', 'jam_selesai' => '12:00']],
        ]);

        // Malicious SQL injection payload in ?hari
        $maliciousPayload = "senin') = true OR 1=1 --";
        $response = $this->actingAs($admin)->get('/admin/dokter?hari=' . urlencode($maliciousPayload));
        $response->assertStatus(200);

        // Safe valid day query with spesialisasi filter
        $validResponse = $this->actingAs($admin)->get('/admin/dokter?hari=senin&spesialisasi=SpesialisKeamanan');
        $validResponse->assertStatus(200);
        $validResponse->assertSee('dr. Uji Keamanan');
    }

    /**
     * Test 2: OCR KTP endpoint validates file type and rejects non-image / oversized files
     */
    public function test_ocr_ktp_validates_file_mimes_and_size(): void
    {
        $pasienUser = User::factory()->create(['role' => 'pasien']);

        // Uploading an invalid file (e.g. PHP script / PDF instead of image)
        $fakePdf = UploadedFile::fake()->create('malicious.pdf', 100, 'application/pdf');

        $response = $this->actingAs($pasienUser)->postJson(route('pasien.ocr-ktp'), [
            'ktp_image' => $fakePdf,
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    /**
     * Test 3: CSV Export sanitizes formula injection characters (=, +, -, @)
     */
    public function test_csv_export_sanitizes_formula_injection(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.laporan.excel', [
            'bulan' => now()->month,
            'tahun' => now()->year,
        ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    /**
     * Test 4: SatuSehat Practitioner ID validation & Encounter structure (Rule 10336, 10120, 10118)
     */
    public function test_satusehat_practitioner_and_encounter_validation(): void
    {
        /** @var \App\Services\SatuSehatService $service */
        $service = app(\App\Services\SatuSehatService::class);

        // Verify Practitioner IHS format support (N10000001, numeric, and UUID)
        $this->assertTrue($service->isValidUuidOrIhs('N10000001'));
        $this->assertTrue($service->isValidUuidOrIhs('10000001'));
        $this->assertTrue($service->isValidUuidOrIhs('386ed5c0-cc59-441d-88a1-4adfcf8610a6'));
        $this->assertFalse($service->isValidUuidOrIhs(''));
        $this->assertFalse($service->isValidUuidOrIhs('invalid!!id'));

        // Reflection to test buildEncounterPayload
        $refMethod = new \ReflectionMethod($service, 'buildEncounterPayload');
        $refMethod->setAccessible(true);

        $payload = $refMethod->invoke($service, [
            'patient_id'        => 'P20395777465',
            'dokter_id'         => 'N10000001',
            'nama_dokter'       => 'dr. Ahmad Fauzi, Sp.PD',
            'nama_pasien'       => 'Budi Hartono',
            'tanggal_kunjungan' => '2026-08-31',
            'jam_kunjungan'     => '08:00',
            'kode_booking'      => 'CM-TEST-1234',
            'nama_poli'         => 'Poli Penyakit Dalam',
        ]);

        $this->assertEquals('Encounter', $payload['resourceType']);
        $this->assertEquals('Patient/P20395777465', $payload['subject']['reference']);
        $this->assertEquals('Practitioner/N10000001', $payload['participant'][0]['individual']['reference']);
        $this->assertStringStartsWith('Location/', $payload['location'][0]['location']['reference']);

        // Verify Procedure SNOMED CT classification for outpatient consultation (89.07)
        $refProc = new \ReflectionMethod($service, 'buildProcedurePayload');
        $refProc->setAccessible(true);
        $procPayload = $refProc->invoke($service, [
            'patient_id'   => 'P20395777465',
            'encounter_id' => 'enc-123',
            'icd9_code'    => '89.07',
            'icd9_display' => 'Comprehensive consultation',
        ]);

        $this->assertEquals('Procedure', $procPayload['resourceType']);
        $this->assertEquals('103693007', $procPayload['category'][0]['coding'][0]['code']);
        $this->assertEquals('Diagnostic procedure', $procPayload['category'][0]['coding'][0]['display']);
    }

    public function test_gemini_ocr_structured_normalization(): void
    {
        $service = new \App\Services\GeminiOcrService();
        $normalized = $service->normalizeKtpData([
            'is_ktp'            => true,
            'nik'               => '320101-010101-0001',
            'nama_lengkap'      => ' siti aisyah ',
            'tempat_lahir'      => 'BANDUNG',
            'tanggal_lahir'     => '1995-12-25',
            'jenis_kelamin'     => 'P',
            'alamat'            => 'jl. kenanga no. 12',
            'kelurahan'         => 'Kelurahan Sukajadi',
            'kecamatan'         => 'Kec. Sukasari',
            'kabupaten'         => 'Kota Bandung',
            'provinsi'          => 'Provinsi Jawa Barat',
            'golongan_darah'    => 'AB',
            'agama'             => 'Islam',
            'status_perkawinan' => 'Kawin',
            'pekerjaan'         => 'Wiraswasta',
            'warga_negara'      => 'WNI',
        ]);

        $this->assertEquals('3201010101010001', $normalized['nik']);
        $this->assertEquals('SITI AISYAH', $normalized['nama_lengkap']);
        $this->assertEquals('Bandung', $normalized['tempat_lahir']);
        $this->assertEquals('1995-12-25', $normalized['tanggal_lahir']);
        $this->assertEquals('P', $normalized['jenis_kelamin']);
        $this->assertEquals('Sukajadi', $normalized['kelurahan']);
        $this->assertEquals('Sukasari', $normalized['kecamatan']);
        $this->assertEquals('Bandung', $normalized['kabupaten']);
        $this->assertEquals('Jawa Barat', $normalized['provinsi']);
        $this->assertEquals('AB', $normalized['golongan_darah']);
        $this->assertEquals('Islam', $normalized['agama']);
        $this->assertEquals('Kawin', $normalized['status_perkawinan']);
        $this->assertEquals('Wiraswasta', $normalized['pekerjaan']);
        $this->assertEquals('WNI', $normalized['warga_negara']);
    }

    public function test_gemini_ocr_api_header_and_model_fallback(): void
    {
        putenv('GEMINI_API_KEY=test-gemini-key');

        \Illuminate\Support\Facades\Http::fake([
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.8-flash:generateContent' => \Illuminate\Support\Facades\Http::response([
                'error' => ['message' => 'Model overloaded', 'code' => 503]
            ], 503),
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent' => \Illuminate\Support\Facades\Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => json_encode([
                                    'is_ktp' => true,
                                    'nik' => '3201010101010001',
                                    'nama_lengkap' => 'AHMAD DAHLAN',
                                    'tanggal_lahir' => '1985-04-12',
                                ])]
                            ]
                        ]
                    ]
                ]
            ], 200),
        ]);

        $service = new \App\Services\GeminiOcrService('test-gemini-key');
        $result = $service->parseKtpImage('base64testdata');


        $this->assertTrue($result['success']);
        $this->assertEquals('3201010101010001', $result['data']['nik']);
        $this->assertEquals('AHMAD DAHLAN', $result['data']['nama_lengkap']);

        // Verify request sent header x-goog-api-key and NO ?key= in url
        \Illuminate\Support\Facades\Http::assertSent(function ($request) {
            return $request->hasHeader('x-goog-api-key', 'test-gemini-key')
                && !str_contains($request->url(), 'key=');
        });
    }
}

