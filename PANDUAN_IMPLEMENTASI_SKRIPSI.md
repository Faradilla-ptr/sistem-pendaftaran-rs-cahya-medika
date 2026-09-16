# PANDUAN IMPLEMENTASI SISTEM REKAM MEDIS & PENDAFTARAN ONLINE (MODUL LENGKAP SKRIPSI)

Dokumen ini berisi panduan teknis, rancangan arsitektur, diagram alur, konfigurasi controller, dan integrasi API (OCR AI & SATUSEHAT) untuk merealisasikan project skripsi D4 Manajemen Informasi Kesehatan (MIK).

---

## DAFTAR ISI
1. [Arsitektur Sistem & Diagram Alur Data (DFD / Activity)](#1-arsitektur-sistem)
2. [Rancangan Algoritma & Modul Scanner (QR Code + OCR AI)](#2-modul-scanner-qr-code--ocr-ai)
3. [Implementasi Logic Khusus Hasil Observasi Lapangan](#3-implementasi-logic-khusus)
4. [Modul Integrasi SATUSEHAT Kemenkes RI](#4-modul-integrasi-satusehat)
5. [Modul Sensus Harian & Pelaporan (Format Excel & PDF)](#5-modul-sensus-harian)
6. [Langkah-Langkah Instalasi & Setup Project](#6-langkah-instalasi)

---

## 1. ARSITEKTUR SISTEM

### Tech Stack Rekomendasi:
- **Backend Framework**: PHP (Laravel 10/11) atau Python (Flask / FastAPI)
- **Database**: MySQL 8.0 / MariaDB 10.4+
- **Frontend**: Blade Engine + Bootstrap 5 / Tailwind CSS + Alpine.js
- **Scanner Lib**: `html5-qrcode` (Webcam QR Code Reader) & `Tesseract.js` / Google Gemini API (OCR Data Parser)
- **Export Engine**: `PhpSpreadsheet` (Excel Sensus) & `DomPDF / WeasyPrint` (Kwitansi & Cetak RM)

### Alur Data Pendaftaran:
```
[Pasien di Rumah] 
   └── Buka Web Pendaftaran -> Pilih Poli & Tanggal (Validasi Kuota < 30) -> Submit
         └── Sistem Generate UUID & QR Code e-Tiket (Status: 'menunggu')

[Pasien Tiba di Rumah Sakit]
   └── Menuju Loket Pendaftaran -> Tunjukkan QR Code di Smartphone / Cetak
         └── Petugas Loket Scan QR Code via Webcam (html5-qrcode)
               └── Data otomatis termuat di SIMRS
                     ├── Verifikasi Alamat KTP vs Domisili (Dalam/Luar Kota)
                     ├── Pasien Bayi -> Validasi NIK Ibu
                     ├── Bayar Deposit Awal Rp 200.000,- (Cetak Bukti)
                     └── Status Berubah: 'terverifikasi_loket'

[Pasien di Ruang Poli]
   └── Dokter/Perawat Memanggil Antrean
         └── Mulai Input Anamnesa & CPPT
               └── SISTEM OTOMATIS MENGUNCI (Lock Edit Loket = TRUE)
                     └── Diagnosa ICD-10 & Tindakan ICD-9-CM Terisi

[Unit Rekam Medis (Back Office)]
   └── Buka Menu Sensus Harian -> Otomatis Terhitung (Lama/Baru, Wilayah, Gender)
   └── Buka Menu SATUSEHAT -> Manual Cross-Check Koding -> Klik "Kirim SATUSEHAT"
```

---

## 2. MODUL SCANNER (QR CODE + OCR AI)

### A. Frontend: QR Code Scanner (`html5-qrcode.js`)
File: `resources/views/pendaftaran/scanner.blade.php`
```html
<div class="card shadow-sm p-3">
    <h4>Scan QR Code Tiket Pendaftaran</h4>
    <div id="qr-reader" style="width: 100%; max-width: 500px;"></div>
    <div id="qr-reader-results" class="mt-3"></div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
function onScanSuccess(decodedText, decodedResult) {
    console.log(`Scan result: ${decodedText}`);
    // Redirect atau AJAX Fetch ke controller verifikasi
    window.location.href = `/admin/pendaftaran/verifikasi?kode_booking=${decodedText}`;
}

var html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: 250 });
html5QrcodeScanner.render(onScanSuccess);
</script>
```

### B. Controller Ekstraksi KTP via OCR + AI Parser
File: `app/Http/Controllers/OcrKtpController.php`
```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OcrKtpController extends Controller
{
    public function parseKtp(Request $request)
    {
        $request->validate(['foto_ktp' => 'required|image|max:4096']);
        $imagePath = $request->file('foto_ktp')->store('temp_ktp');
        
        // 1. Jalankan OCR Lokal / Tesseract
        $rawText = shell_exec("tesseract " . storage_path('app/' . $imagePath) . " stdout -l ind");

        // 2. Parsir Teks Menggunakan AI Prompt Sederhana / Regex
        // Mengekstrak NIK, Nama, Tanggal Lahir, Jenis Kelamin, dan Alamat
        preg_match('/(\d{16})/', $rawText, $nikMatch);
        $nik = $nikMatch[0] ?? null;

        return response()->json([
            'status' => 'success',
            'data' => [
                'nik' => $nik,
                'raw_text' => $rawText
            ]
        ]);
    }
}
```

---

## 3. IMPLEMENTASI LOGIC KHUSUS (HASIL OBSERVASI)

### A. Lock Status Pendaftaran Saat CPPT Terisi (Security & Data Integrity)
```php
// Pada PendaftaranController@update (Loket)
public function update(Request $request, $id)
{
    $pendaftaran = Pendaftaran::findOrFail($id);
    
    // Validasi apakah CPPT sudah mulai diisi perawat poli
    if ($pendaftaran->is_locked_by_poli || $pendaftaran->status_pelayanan === 'dalam_pemeriksaan') {
        return back()->with('error', 'Data terkunci! Pelayanan di poli sudah dimulai. Revisi data hanya dapat dilakukan melalui Otorisasi Unit Rekam Medis.');
    }
    
    $pendaftaran->update($request->all());
    return back()->with('success', 'Data pendaftaran berhasil diperbarui.');
}
```

### B. Validasi Pasien Bayi (< 30 Hari) Tanpa NIK Mandiri
```php
public function storePasien(Request $request)
{
    $birthDate = Carbon::parse($request->tgl_lahir);
    $ageDays = $birthDate->diffInDays(Carbon::now());
    
    if ($ageDays < 30 && empty($request->no_ktp)) {
        $request->validate([
            'nik_ibu' => 'required|digits:16',
            'nama_ibu' => 'required|string|max:150',
        ]);
        $isBayi = true;
    } else {
        $request->validate([
            'no_ktp' => 'required|digits:16|unique:pasien,no_ktp',
        ]);
        $isBayi = false;
    }
    
    // Generate Nomor Rekam Medis Otomatis
    $noRm = $this->generateNomorRM();
    
    // Simpan data pasien...
}
```

---

## 4. MODUL INTEGRASI SATUSEHAT (FHIR JSON BUILDER)

### Pengiriman Encounter Rawat Jalan (OAuth2 & Payload Kemenkes)
File: `app/Services/SatuSehatService.php`
```php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class SatuSehatService
{
    protected $baseUrl;
    protected $authUrl;
    protected $clientId;
    protected $clientSecret;
    protected $orgId;

    public function __construct()
    {
        $this->baseUrl = env('SATUSEHAT_BASE_URL', 'https://api-satusehat-stg.dto.kemkes.go.id/fhir-r4/v1');
        $this->authUrl = env('SATUSEHAT_AUTH_URL', 'https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1/accesstoken?grant_type=client_credentials');
        $this->clientId = env('SATUSEHAT_CLIENT_ID');
        $this->clientSecret = env('SATUSEHAT_CLIENT_SECRET');
        $this->orgId = env('SATUSEHAT_ORG_ID');
    }

    public function getAccessToken()
    {
        $response = Http::asForm()->post($this->authUrl, [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);
        return $response->json()['access_token'] ?? null;
    }

    public function sendEncounter($pendaftaran, $ihsPatientId, $ihsPractitionerId, $locationId)
    {
        $token = $this->getAccessToken();
        
        $payload = [
            "resourceType" => "Encounter",
            "status" => "arrived",
            "class" => [
                "system" => "http://terminology.hl7.org/CodeSystem/v3-ActCode",
                "code" => "AMB",
                "display" => "ambulatory"
            ],
            "subject" => [
                "reference" => "Patient/" . $ihsPatientId
            ],
            "participant" => [
                [
                    "type" => [
                        [
                            "coding" => [
                                [
                                    "system" => "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                                    "code" => "ATND",
                                    "display" => "attender"
                                ]
                            ]
                        ]
                    ],
                    "individual" => [
                        "reference" => "Practitioner/" . $ihsPractitionerId
                    ]
                ]
            ],
            "period" => [
                "start" => $pendaftaran->tgl_registrasi . "T" . $pendaftaran->jam_registrasi . "+07:00"
            ],
            "location" => [
                [
                    "location" => [
                        "reference" => "Location/" . $locationId
                    ]
                ]
            ],
            "serviceProvider" => [
                "reference" => "Organization/" . $this->orgId
            ]
        ];

        $response = Http::withToken($token)
            ->post($this->baseUrl . "/Encounter", $payload);

        return $response->json();
    }
}
```

---

## 5. MODUL SENSUS HARIAN & PELAPORAN

### Query Rekapitulasi Sensus Rawat Jalan Otomatis:
```sql
SELECT 
    p.nama_poli,
    d.nama_dokter,
    COUNT(pd.id) AS total_kunjungan,
    SUM(CASE WHEN pd.status_pasien = 'Baru' THEN 1 ELSE 0 END) AS pasien_baru,
    SUM(CASE WHEN pd.status_pasien = 'Lama' THEN 1 ELSE 0 END) AS pasien_lama,
    SUM(CASE WHEN ps.jenis_kelamin = 'L' THEN 1 ELSE 0 END) AS laki_laki,
    SUM(CASE WHEN ps.jenis_kelamin = 'P' THEN 1 ELSE 0 END) AS perempuan,
    SUM(CASE WHEN pd.status_wilayah = 'dalam_kota' THEN 1 ELSE 0 END) AS dalam_kota,
    SUM(CASE WHEN pd.status_wilayah = 'luar_kota' THEN 1 ELSE 0 END) AS luar_kota,
    SUM(CASE WHEN pd.cara_bayar = 'Umum' THEN 1 ELSE 0 END) AS bayar_umum,
    SUM(CASE WHEN pd.cara_bayar = 'BPJS Kesehatan' THEN 1 ELSE 0 END) AS bayar_bpjs
FROM pendaftaran pd
JOIN pasien ps ON pd.pasien_id = ps.id
JOIN poli p ON pd.poli_id = p.id
JOIN dokter d ON pd.dokter_id = d.id
WHERE pd.tgl_registrasi = CURDATE() AND pd.status_pelayanan != 'batal'
GROUP BY p.id, d.id;
```

---

## 6. LANGKAH INSTALASI & MENJALANKAN PROJECT

1. **Import Database**:
   - Buka phpMyAdmin / MySQL Workbench.
   - Buat database `cahya_medika_db`.
   - Import file `database_schema.sql`.

2. **Konfigurasi Environment (`.env`)**:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=cahya_medika_db
   DB_USERNAME=root
   DB_PASSWORD=

   SATUSEHAT_ENV=sandbox
   SATUSEHAT_BASE_URL=https://api-satusehat-stg.dto.kemkes.go.id/fhir-r4/v1
   SATUSEHAT_AUTH_URL=https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1/accesstoken?grant_type=client_credentials
   SATUSEHAT_CLIENT_ID=your_client_id
   SATUSEHAT_CLIENT_SECRET=your_client_secret
   SATUSEHAT_ORG_ID=your_organization_id
   ```

3. **Jalankan Aplikasi**:
   ```bash
   composer install
   php artisan migrate
   php artisan serve
   ```
4. **Akun Uji Coba Default**:
   - Admin IT: `admin` / password: `password`
   - Rekam Medis: `sania` / password: `password`
   - Loket Pendaftaran: `loket1` / password: `password`
   - Dokter: `dr_uri` / password: `password`
