<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiOcrService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY', '');
    }

    /**
     * Process and Validate KTP Image base64 string using Gemini Vision AI API
     */
    public function parseKtpImage(string $base64Image, string $mimeType = 'image/jpeg'): array
    {
        // Clean base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
            $mimeType = 'image/' . strtolower($type[1]);
        }

        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'GEMINI_API_KEY belum diisi pada file .env. Silakan atur GEMINI_API_KEY pada .env'
            ];
        }

        $prompt = "Verifikasi & OCR KTP Indonesia.\n" .
            "1. Jika BUKAN foto KTP Indonesia asli valid (pemandangan/hewan/resi/selfie/dll), setel \"is_ktp\": false.\n" .
            "2. Jika KTP valid, setel \"is_ktp\": true dan ekstrak bidang berikut:\n" .
            "{\n" .
            '  "is_ktp": true,' . "\n" .
            '  "nik": "16 digit NIK",' . "\n" .
            '  "nama_lengkap": "NAMA LENGKAP PASIEN",' . "\n" .
            '  "tempat_lahir": "KOTA LAHIR",' . "\n" .
            '  "tanggal_lahir": "YYYY-MM-DD",' . "\n" .
            '  "jenis_kelamin": "L atau P",' . "\n" .
            '  "alamat": "ALAMAT JALAN / RT RW",' . "\n" .
            '  "kelurahan": "DESA / KELURAHAN",' . "\n" .
            '  "kecamatan": "KECAMATAN",' . "\n" .
            '  "kabupaten": "KABUPATEN / KOTA",' . "\n" .
            '  "provinsi": "PROVINSI",' . "\n" .
            '  "golongan_darah": "A / B / AB / O / - / Tidak Tahu",' . "\n" .
            '  "agama": "AGAMA",' . "\n" .
            '  "status_perkawinan": "BELUM KAWIN / KAWIN / DUDA / JANDA",' . "\n" .
            '  "pekerjaan": "PEKERJAAN",' . "\n" .
            '  "warga_negara": "WNI atau WNA"' . "\n" .
            "}\n" .
            "HANYA KEMBALIKAN OBJECT JSON murni.";

        $models = [
            'gemini-3.5-flash-lite',
            'gemini-3.1-flash-lite',
            'gemini-flash-lite-latest',
            'gemini-3.5-flash'
        ];

        $lastErrorMessage = '';

        foreach ($models as $model) {
            try {
                $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$this->apiKey}";
                $response = Http::timeout(10)->post($endpoint, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                                [
                                    'inline_data' => [
                                        'mime_type' => $mimeType,
                                        'data'      => $base64Image,
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature'        => 0.0,
                        'topP'               => 0.1,
                        'maxOutputTokens'    => 350,
                        'response_mime_type' => 'application/json',
                    ]
                ]);

                if ($response->successful()) {
                    $jsonResult = $response->json();
                    $text = $jsonResult['candidates'][0]['content']['parts'][0]['text'] ?? '';
                    $cleanJson = preg_replace('/```json\s*|\s*```/', '', trim($text));
                    $data = json_decode($cleanJson, true);

                    if (is_array($data)) {
                        // Check if Gemini detected non-KTP image
                        $isKtp = $data['is_ktp'] ?? null;
                        if ($isKtp === false || (is_string($isKtp) && strtolower($isKtp) === 'false')) {
                            return [
                                'success' => false,
                                'message' => 'Gambar yang Anda unggah bukan foto KTP (Kartu Tanda Penduduk) Indonesia yang valid. Harap unggah foto KTP asli yang jelas.'
                            ];
                        }

                        $nik = preg_replace('/[^0-9]/', '', $data['nik'] ?? '');
                        $nama = trim($data['nama_lengkap'] ?? '');

                        // If neither NIK nor Nama could be read, reject image
                        if (empty($nik) && empty($nama)) {
                            return [
                                'success' => false,
                                'message' => 'Foto KTP tidak terdeteksi dengan jelas atau gambar bukan KTP Indonesia. Pastikan posisi KTP cukup terang dan tidak buram.'
                            ];
                        }

                        return [
                            'success' => true,
                            'data'    => $this->normalizeKtpData($data)
                        ];
                    }
                } else {
                    $errData = $response->json();
                    $lastErrorMessage = $errData['error']['message'] ?? $response->body();
                    Log::warning("Gemini OCR Model {$model} error: " . $lastErrorMessage);
                }
            } catch (\Exception $e) {
                $lastErrorMessage = $e->getMessage();
                Log::error("Gemini OCR Model {$model} exception: " . $e->getMessage());
            }
        }

        return [
            'success' => false,
            'message' => 'Gagal memproses gambar: ' . $lastErrorMessage
        ];
    }

    private function normalizeKtpData(array $data): array
    {
        $nik = preg_replace('/[^0-9]/', '', $data['nik'] ?? '');
        $nama = strtoupper(trim($data['nama_lengkap'] ?? ''));
        $tempat = ucwords(strtolower(trim($data['tempat_lahir'] ?? '')));
        $tgl = trim($data['tanggal_lahir'] ?? '');
        if ($tgl && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl)) {
            $timestamp = strtotime($tgl);
            if ($timestamp) {
                $tgl = date('Y-m-d', $timestamp);
            }
        }

        $jkRaw = strtoupper(trim($data['jenis_kelamin'] ?? ''));
        if (str_contains($jkRaw, 'PEREMPUAN') || $jkRaw === 'P') {
            $jk = 'P';
        } elseif (str_contains($jkRaw, 'LAKI') || $jkRaw === 'L') {
            $jk = 'L';
        } else {
            $jk = '';
        }

        $gdRaw = strtoupper(trim($data['golongan_darah'] ?? ''));
        // Clean out any non-alphabetic character
        $gdClean = preg_replace('/[^A-Z]/', '', $gdRaw);

        if ($gdClean === 'AB' || $gdRaw === 'AB') {
            $gd = 'AB';
        } elseif ($gdClean === 'A' || $gdRaw === 'A') {
            $gd = 'A';
        } elseif ($gdClean === 'B' || $gdRaw === 'B') {
            $gd = 'B';
        } elseif ($gdClean === 'O' || $gdRaw === 'O' || $gdRaw === '0') {
            $gd = 'O';
        } else {
            // Dash '-' or 'TIDAK TAHU' or empty -> 'Tidak Tahu'
            $gd = 'Tidak Tahu';
        }

        $agRaw = strtoupper(trim($data['agama'] ?? ''));
        if (str_contains($agRaw, 'ISLAM')) {
            $ag = 'Islam';
        } elseif (str_contains($agRaw, 'KRISTEN')) {
            $ag = 'Kristen';
        } elseif (str_contains($agRaw, 'KATOLIK')) {
            $ag = 'Katolik';
        } elseif (str_contains($agRaw, 'BUDHA') || str_contains($agRaw, 'BUDDHA')) {
            $ag = 'Budha';
        } elseif (str_contains($agRaw, 'HINDU')) {
            $ag = 'Hindu';
        } else {
            $ag = 'Lain-lain';
        }

        $stRaw = strtoupper(trim($data['status_perkawinan'] ?? ''));
        if (str_contains($stRaw, 'BELUM') || str_contains($stRaw, 'LAJANG')) {
            $st = 'Belum Kawin';
        } elseif (str_contains($stRaw, 'KAWIN') || str_contains($stRaw, 'MENIKAH')) {
            $st = 'Kawin';
        } elseif (str_contains($stRaw, 'DUDA')) {
            $st = 'Duda';
        } elseif (str_contains($stRaw, 'JANDA')) {
            $st = 'Janda';
        } else {
            $st = '';
        }

        $wnRaw = strtoupper(trim($data['warga_negara'] ?? ''));
        if (str_contains($wnRaw, 'WNA') || str_contains($wnRaw, 'ASING')) {
            $wn = 'WNA';
        } else {
            $wn = 'WNI';
        }

        $pekRaw = strtoupper(trim($data['pekerjaan'] ?? ''));
        // Check WIRASWASTA before SWASTA!
        if (str_contains($pekRaw, 'WIRASWASTA') || str_contains($pekRaw, 'DAGANG') || str_contains($pekRaw, 'PEDAGANG')) {
            $pek = 'Wiraswasta';
        } elseif (str_contains($pekRaw, 'SWASTA')) {
            $pek = 'Karyawan Swasta';
        } elseif (str_contains($pekRaw, 'PNS') || str_contains($pekRaw, 'TNI') || str_contains($pekRaw, 'POLRI') || str_contains($pekRaw, 'NEGERI')) {
            $pek = 'PNS / TNI / Polri';
        } elseif (str_contains($pekRaw, 'PETANI') || str_contains($pekRaw, 'PETERNAK')) {
            $pek = 'Petani / Peternak';
        } elseif (str_contains($pekRaw, 'NELAYAN')) {
            $pek = 'Nelayan';
        } elseif (str_contains($pekRaw, 'BURUH')) {
            $pek = 'Buruh Harian Lepas';
        } elseif (str_contains($pekRaw, 'RUMAH TANGGA') || str_contains($pekRaw, 'IRT')) {
            $pek = 'Ibu Rumah Tangga';
        } elseif (str_contains($pekRaw, 'PELAJAR') || str_contains($pekRaw, 'MAHASISWA')) {
            $pek = 'Pelajar / Mahasiswa';
        } elseif (str_contains($pekRaw, 'BELUM') || str_contains($pekRaw, 'TIDAK BEKERJA')) {
            $pek = 'Belum / Tidak Bekerja';
        } elseif (!empty($pekRaw)) {
            $pek = ucwords(strtolower($pekRaw));
        } else {
            $pek = '';
        }

        // Clean region strings
        $cleanRegion = function($str) {
            $s = trim($str ?? '');
            $s = preg_replace('/^(kabupaten|kab\.|kota|kecamatan|kec\.|desa|kelurahan|kel\.|provinsi|prov\.)\s+/i', '', $s);
            return ucwords(strtolower(trim($s)));
        };

        return [
            'nik'               => $nik,
            'nama_lengkap'      => $nama,
            'tempat_lahir'      => $tempat,
            'tanggal_lahir'     => $tgl,
            'jenis_kelamin'     => $jk,
            'golongan_darah'    => $gd,
            'alamat'            => ucwords(strtolower(trim($data['alamat'] ?? ''))),
            'kelurahan'         => $cleanRegion($data['kelurahan'] ?? ''),
            'kecamatan'         => $cleanRegion($data['kecamatan'] ?? ''),
            'kabupaten'         => $cleanRegion($data['kabupaten'] ?? ''),
            'provinsi'          => $cleanRegion($data['provinsi'] ?? ''),
            'agama'             => $ag,
            'status_perkawinan' => $st,
            'pekerjaan'         => $pek,
            'warga_negara'      => $wn,
        ];
    }
}
