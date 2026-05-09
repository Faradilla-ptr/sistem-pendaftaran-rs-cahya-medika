<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SatuSehatService
{
    protected Client $client;
    protected string $baseUrl;
    protected string $authUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $organizationId;

    public function __construct()
    {
        $this->baseUrl        = config('satusehat.base_url');
        $this->authUrl        = config('satusehat.auth_url');
        $this->clientId       = config('satusehat.client_id');
        $this->clientSecret   = config('satusehat.client_secret');
        $this->organizationId = config('satusehat.organization_id');

        $this->client = new Client([
            'timeout' => 30,
            'verify'  => false,
        ]);
    }

    // =========================================================
    // AUTH
    // =========================================================

    public function getAccessToken(): ?string
    {
        if (config('satusehat.use_dummy') && empty($this->clientId)) {
            return 'dummy_access_token_for_development';
        }

        $cached = Cache::get('satusehat_token');
        if ($cached && $cached !== 'dummy_access_token_for_development') {
            return $cached;
        }

        try {
            // SatuSehat: grant_type wajib di query string, credential di form body
            $response = $this->client->post(
                $this->authUrl . '/accesstoken?grant_type=client_credentials',
                [
                    'form_params' => [
                        'client_id'     => $this->clientId,
                        'client_secret' => $this->clientSecret,
                    ],
                ]
            );

            $data  = json_decode($response->getBody()->getContents(), true);
            $token = $data['access_token'] ?? null;

            if ($token) {
                Cache::put('satusehat_token', $token, 3500);
            }

            return $token;
        } catch (\Exception $e) {
            Log::error('SatuSehat Auth Error: ' . $e->getMessage() . ' | ' . $this->getBody($e));
            return config('satusehat.use_dummy') ? 'dummy_access_token_for_development' : null;
        }
    }

    protected function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Content-Type'  => 'application/json',
        ];
    }

    // =========================================================
    // PATIENT
    // =========================================================

    /** GET Patient by NIK */
    public function getPatientByNik(string $nik): array
    {
        try {
            $response = $this->client->get($this->baseUrl . '/fhir-r4/v1/Patient', [
                'headers' => $this->getHeaders(),
                'query'   => [
                    'identifier' => 'https://fhir.kemkes.go.id/id/nik|' . $nik,
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error('SatuSehat Get Patient Error: ' . $e->getMessage() . ' | ' . $this->getBody($e));
            return ['resourceType' => 'Bundle', 'total' => 0, 'entry' => []];
        }
    }

    /** GET Patient by ID (IHS Number) */
    public function getPatientById(string $id): array
    {
        try {
            $response = $this->client->get($this->baseUrl . '/fhir-r4/v1/Patient/' . $id, [
                'headers' => $this->getHeaders(),
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error('SatuSehat Get Patient By ID Error: ' . $e->getMessage() . ' | ' . $this->getBody($e));
            return [];
        }
    }

    /**
     * Cari pasien by NIK, kalau tidak ada baru buat.
     * Selalu kembalikan ['success', 'data' => resource Patient]
     */
    public function getOrCreatePatient(array $data): array
    {
        $nik = $data['nik'] ?? '';

        if ($nik) {
            $found = $this->getPatientByNik($nik);

            if (!empty($found['total']) && $found['total'] > 0) {
                $resource = $found['entry'][0]['resource'] ?? [];
                return [
                    'success'        => true,
                    'data'           => $resource,
                    'found_existing' => true,
                ];
            }
        }

        return $this->createPatient($data);
    }

    /** POST Create Patient */
    public function createPatient(array $data): array
    {
        $payload = $this->buildPatientPayload($data);

        try {
            $response = $this->client->post($this->baseUrl . '/fhir-r4/v1/Patient', [
                'headers' => $this->getHeaders(),
                'json'    => $payload,
            ]);

            return [
                'success' => true,
                'data'    => json_decode($response->getBody()->getContents(), true),
            ];
        } catch (\Exception $e) {
            $body = $this->getBody($e);
            Log::error('SatuSehat Create Patient Error: ' . $e->getMessage() . ' | ' . $body);

            if (config('satusehat.use_dummy')) {
                return [
                    'success'  => true,
                    'data'     => array_merge($payload, ['id' => 'dummy-patient-' . rand(1000, 9999)]),
                    'is_dummy' => true,
                ];
            }

            return ['success' => false, 'error' => $e->getMessage(), 'details' => $body];
        }
    }

    // =========================================================
    // PRACTITIONER
    // =========================================================

    /** GET Practitioner by NIK */
    public function getPractitioner(string $nik): array
    {
        try {
            $response = $this->client->get($this->baseUrl . '/fhir-r4/v1/Practitioner', [
                'headers' => $this->getHeaders(),
                'query'   => [
                    'identifier' => 'https://fhir.kemkes.go.id/id/nik|' . $nik,
                ],
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error('SatuSehat Get Practitioner Error: ' . $e->getMessage() . ' | ' . $this->getBody($e));
            return ['resourceType' => 'Bundle', 'total' => 0, 'entry' => []];
        }
    }

    /** GET Practitioner by ID */
    public function getPractitionerById(string $id): array
    {
        try {
            $response = $this->client->get($this->baseUrl . '/fhir-r4/v1/Practitioner/' . $id, [
                'headers' => $this->getHeaders(),
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error('SatuSehat Get Practitioner By ID Error: ' . $e->getMessage() . ' | ' . $this->getBody($e));
            return [];
        }
    }

    /**
     * Ekstrak info ringkas dari resource Practitioner FHIR
     */
    public function extractPractitionerInfo(array $resource): array
    {
        $name = $resource['name'][0] ?? [];
        return [
            'id'           => $resource['id'] ?? '',
            'nama_lengkap' => $name['text'] ?? implode(' ', array_merge(
                [$name['prefix'][0] ?? ''],
                $name['given'] ?? [],
                [$name['family'] ?? ''],
                [$name['suffix'][0] ?? '']
            )),
            'gender'       => $resource['gender'] ?? '',
            'birthDate'    => $resource['birthDate'] ?? '',
            'nik'          => collect($resource['identifier'] ?? [])
                ->firstWhere('system', 'https://fhir.kemkes.go.id/id/nik')['value'] ?? '',
            'nip'          => collect($resource['identifier'] ?? [])
                ->firstWhere('system', 'https://fhir.kemkes.go.id/id/nip')['value'] ?? '',
        ];
    }

    /**
     * Ekstrak info ringkas dari resource Patient FHIR
     */
    public function extractPatientInfo(array $resource): array
    {
        $name    = $resource['name'][0] ?? [];
        $telecom = collect($resource['telecom'] ?? [])->firstWhere('system', 'phone');
        $address = $resource['address'][0] ?? [];
        return [
            'id'            => $resource['id'] ?? '',
            'nik'           => collect($resource['identifier'] ?? [])
                ->firstWhere('system', 'https://fhir.kemkes.go.id/id/nik')['value'] ?? '',
            'nama_lengkap'  => $name['text'] ?? ($name['family'] ?? ''),
            'jenis_kelamin' => ($resource['gender'] ?? '') === 'male' ? 'L' : 'P',
            'tanggal_lahir' => $resource['birthDate'] ?? '',
            'no_hp'         => $telecom['value'] ?? '',
            'alamat'        => $address['line'][0] ?? '',
            'kota'          => $address['city'] ?? '',
            'kode_pos'      => $address['postalCode'] ?? '',
        ];
    }

    // =========================================================
    // ENCOUNTER
    // =========================================================

    /** POST Create Encounter */
    public function createEncounter(array $data): array
    {
        $payload = $this->buildEncounterPayload($data);

        try {
            $response = $this->client->post($this->baseUrl . '/fhir-r4/v1/Encounter', [
                'headers' => $this->getHeaders(),
                'json'    => $payload,
            ]);

            return [
                'success' => true,
                'data'    => json_decode($response->getBody()->getContents(), true),
            ];
        } catch (\Exception $e) {
            $body = $this->getBody($e);
            Log::error('SatuSehat Create Encounter Error: ' . $e->getMessage() . ' | ' . $body);

            if (config('satusehat.use_dummy')) {
                return [
                    'success'  => true,
                    'data'     => array_merge($payload, ['id' => 'dummy-encounter-' . rand(1000, 9999)]),
                    'is_dummy' => true,
                ];
            }

            return ['success' => false, 'error' => $e->getMessage(), 'details' => $body];
        }
    }

    // =========================================================
    // ORGANIZATION
    // =========================================================

    /** GET Organization */
    public function getOrganization(): array
    {
        try {
            $response = $this->client->get(
                $this->baseUrl . '/fhir-r4/v1/Organization/' . $this->organizationId,
                ['headers' => $this->getHeaders()]
            );

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error('SatuSehat Get Organization Error: ' . $e->getMessage() . ' | ' . $this->getBody($e));
            return $this->getDummyOrganization();
        }
    }

    // =========================================================
    // ADMINISTRATIVE AREA (Kode Wilayah BPS)
    // =========================================================

    /**
     * Cari kode wilayah kelurahan/desa berdasarkan nama.
     * Gunakan ini untuk mendapatkan kode yang valid dari SatuSehat.
     * Contoh: searchAdministrativeArea('Kademangan', 'district', '351101')
     */
    public function searchAdministrativeArea(string $name, string $part = 'district', string $parent = ''): array
    {
        try {
            $query = ['name' => $name, 'part' => $part];
            if ($parent) {
                $query['parent'] = $parent;
            }

            $response = $this->client->get($this->baseUrl . '/fhir-r4/v1/Location', [
                'headers' => $this->getHeaders(),
                'query'   => $query,
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error('SatuSehat Administrative Area Error: ' . $e->getMessage() . ' | ' . $this->getBody($e));
            return ['resourceType' => 'Bundle', 'total' => 0, 'entry' => []];
        }
    }

    // =========================================================
    // FHIR PAYLOAD BUILDERS
    // =========================================================

    /**
     * Patient FHIR R4 payload — sesuai spesifikasi SatuSehat
     */
    protected function buildPatientPayload(array $data): array
    {
        $namaLengkap = trim($data['nama_lengkap'] ?? $data['nama'] ?? '');

        // Pisah family name (kata terakhir) dan given name
        $parts  = explode(' ', $namaLengkap);
        $family = count($parts) > 1 ? array_pop($parts) : $namaLengkap;
        $given  = [$namaLengkap]; // gunakan nama lengkap sebagai given

        $telepon  = $data['no_hp'] ?? $data['telepon'] ?? '';
        $kota     = $data['kabupaten'] ?? $data['kota'] ?? '';
        $tglLahir = $data['tanggal_lahir'] ?? '';

        if ($tglLahir instanceof \Carbon\Carbon) {
            $tglLahir = $tglLahir->format('Y-m-d');
        }

        $payload = [
            'resourceType' => 'Patient',
            'meta'         => [
                'profile' => ['https://fhir.kemkes.go.id/r4/StructureDefinition/Patient'],
            ],
            'identifier' => [
                [
                    'use'    => 'official',
                    'system' => 'https://fhir.kemkes.go.id/id/nik',
                    'value'  => $data['nik'] ?? '',
                ],
            ],
            'active' => true,
            'name'   => [
                [
                    'use'    => 'official',
                    'text'   => $namaLengkap,
                    'family' => $family,
                    'given'  => $given,
                ],
            ],
            'gender'              => ($data['jenis_kelamin'] ?? 'L') === 'L' ? 'male' : 'female',
            'birthDate'           => $tglLahir,
            'multipleBirthBoolean' => false,
        ];

        // Telecom (opsional tapi dianjurkan)
        if ($telepon) {
            $payload['telecom'] = [
                [
                    'system' => 'phone',
                    'value'  => $telepon,
                    'use'    => 'mobile',
                ],
            ];
        }

        // Address + extension kode wilayah — WAJIB ada di SatuSehat (Rule 10621-10624)
        // Default staging: Kota Surabaya (Kab Bondowoso belum tersedia di DB staging SatuSehat)
        // Untuk produksi: isi kode_* dari BPS → https://sig.bps.go.id/basisdata/index
        $kodeProvinsi = $data['kode_provinsi']  ?? '35';          // Jawa Timur
        $kodeKota     = $data['kode_kabupaten'] ?? '3578';         // Kota Surabaya (staging fallback)
        $kodeKec      = $data['kode_kecamatan'] ?? '357801';       // Kec. Bulak, Surabaya
        $kodeKel      = $data['kode_kelurahan'] ?? '3578011001';   // Kel. Bulak, Surabaya

        $payload['address'] = [
            [
                'use'        => 'home',
                'line'       => [$data['alamat'] ?? ''],
                'city'       => $kota ?: 'Bondowoso',
                'postalCode' => $data['kode_pos'] ?? '',
                'country'    => 'ID',
                'extension'  => [
                    [
                        'url'       => 'https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode',
                        'extension' => [
                            ['url' => 'province', 'valueCode' => $kodeProvinsi],
                            ['url' => 'city',     'valueCode' => $kodeKota],
                            ['url' => 'district', 'valueCode' => $kodeKec],
                            ['url' => 'village',  'valueCode' => $kodeKel],
                        ],
                    ],
                ],
            ],
        ];

        return $payload;
    }

    /**
     * Encounter FHIR R4 payload — semua field wajib SatuSehat:
     * identifier, statusHistory, participant, location
     */
    protected function buildEncounterPayload(array $data): array
    {
        $patientId  = $data['patient_id']  ?? '';
        $dokterId   = $data['dokter_id']   ?? '';
        $locationId = config('satusehat.location_id') ?: '';

        // Format: 2026-05-09T08:00:00+07:00
        // SatuSehat staging tidak menerima tanggal masa depan (Rule 10123/10174).
        // Jika kunjungan dijadwalkan besok atau lebih, gunakan waktu sekarang sebagai period.start.
        $tglKunjungan = $data['tanggal_kunjungan'] ?? date('Y-m-d');
        $jamKunjungan = $data['jam_kunjungan']     ?? '08:00';
        $tglKunjunganDT = \Carbon\Carbon::createFromFormat('Y-m-d H:i', "$tglKunjungan $jamKunjungan", 'Asia/Jakarta');

        if ($tglKunjunganDT->isFuture()) {
            $waktuMulai = now('Asia/Jakarta')->format('Y-m-d\TH:i:s+07:00');
        } else {
            $waktuMulai = $tglKunjungan . 'T' . $jamKunjungan . ':00+07:00';
        }

        // Identifier menggunakan kode_booking (unik per pendaftaran)
        $kodeBooking = $data['kode_booking'] ?? uniqid('CM');

        $payload = [
            'resourceType' => 'Encounter',

            // Wajib: identifier kunjungan
            'identifier' => [
                [
                    'system' => 'http://sys-ids.kemkes.go.id/encounter/' . $this->organizationId,
                    'value'  => $kodeBooking,
                ],
            ],

            'status' => 'arrived',

            // Wajib: riwayat status
            'statusHistory' => [
                [
                    'status' => 'arrived',
                    'period' => ['start' => $waktuMulai],
                ],
            ],

            'class' => [
                'system'  => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code'    => 'AMB',
                'display' => 'ambulatory',
            ],

            'subject' => [
                'reference' => 'Patient/' . $patientId,
                'display'   => $data['nama_pasien'] ?? '',
            ],

            // Wajib: participant (dokter yang menangani)
            'participant' => [
                [
                    'type' => [
                        [
                            'coding' => [
                                [
                                    'system'  => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType',
                                    'code'    => 'ATND',
                                    'display' => 'attender',
                                ],
                            ],
                        ],
                    ],
                    'individual' => $this->isValidUuid($dokterId)
                        ? ['reference' => 'Practitioner/' . $dokterId, 'display' => $data['nama_dokter'] ?? '']
                        : ['display' => $data['nama_dokter'] ?? 'Dokter Umum'],
                ],
            ],

            'period' => [
                'start' => $waktuMulai,
            ],

            // Wajib: location (didapat dari test_setup_location.php)
            'location' => [
                [
                    'location' => $locationId
                        ? ['reference' => 'Location/' . $locationId, 'display' => $data['nama_poli'] ?? 'Rawat Jalan']
                        : ['display' => $data['nama_poli'] ?? 'Rawat Jalan'],
                    'status' => 'active',
                ],
            ],

            'serviceProvider' => [
                'reference' => 'Organization/' . $this->organizationId,
            ],
        ];

        if (!empty($data['keluhan'])) {
            $payload['reasonCode'] = [['text' => $data['keluhan']]];
        }

        return $payload;
    }

    /**
     * Validasi apakah string adalah UUID SatuSehat yang valid
     */
    protected function isValidUuid(string $value): bool
    {
        return (bool) preg_match(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i',
            $value
        );
    }

    // =========================================================
    // HELPER
    // =========================================================

    /** Ambil response body secara aman dari exception apapun (termasuk ConnectException) */
    protected function getBody(\Exception $e): string
    {
        if ($e instanceof RequestException && $e->hasResponse()) {
            return (string) $e->getResponse()->getBody();
        }
        return '';
    }

    // =========================================================
    // DUMMY FALLBACKS
    // =========================================================

    protected function getDummyOrganization(): array
    {
        return [
            'resourceType' => 'Organization',
            'id'           => $this->organizationId ?: 'org-dummy',
            'name'         => 'RS Cahya Medika Bondowoso',
            'type'         => [['text' => 'Rumah Sakit Umum Swasta']],
            '_dummy'       => true,
        ];
    }

    public function getDummyDoctors(): array
    {
        return [
            'resourceType' => 'Bundle',
            'total'        => 5,
            'entry'        => [
                ['resource' => ['id' => 'dr-001', 'name' => [['text' => 'dr. Ahmad Fauzi, Sp.PD']]]],
                ['resource' => ['id' => 'dr-002', 'name' => [['text' => 'dr. Siti Rahma, Sp.A']]]],
                ['resource' => ['id' => 'dr-003', 'name' => [['text' => 'dr. Budi Santoso, Sp.OG']]]],
                ['resource' => ['id' => 'dr-004', 'name' => [['text' => 'dr. Maya Indah, Sp.B']]]],
                ['resource' => ['id' => 'dr-005', 'name' => [['text' => 'dr. Rudi Pratama, Sp.JP']]]],
            ],
            '_dummy'       => true,
        ];
    }
}
