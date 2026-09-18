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

    /** GET Encounter by Booking Code identifier */
    public function getEncounterByBookingCode(string $bookingCode): ?array
    {
        try {
            $response = $this->client->get($this->baseUrl . '/fhir-r4/v1/Encounter', [
                'headers' => $this->getHeaders(),
                'query'   => [
                    'identifier' => 'http://sys-ids.kemkes.go.id/encounter/' . $this->organizationId . '|' . $bookingCode,
                ],
            ]);
            $res = json_decode($response->getBody()->getContents(), true);
            if (!empty($res['total']) && $res['total'] > 0) {
                return $res['entry'][0]['resource'] ?? null;
            }
        } catch (\Exception $e) {
            Log::error('SatuSehat Get Encounter Error: ' . $e->getMessage());
        }
        return null;
    }

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

            // Handling jika Encounter sudah pernah dibuat (duplicate)
            if (str_contains($body, 'duplicate')) {
                $existing = $this->getEncounterByBookingCode($data['kode_booking'] ?? '');
                if ($existing) {
                    return [
                        'success'        => true,
                        'data'           => $existing,
                        'found_existing' => true,
                    ];
                }
            }

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

    /** PUT Update Encounter (e.g. set status to in-progress or finished) */
    public function updateEncounterStatus(string $encounterId, string $status, ?string $conditionId = null, string $conditionDisplay = ''): array
    {
        try {
            $response = $this->client->get($this->baseUrl . '/fhir-r4/v1/Encounter/' . $encounterId, [
                'headers' => $this->getHeaders(),
            ]);
            $encounter = json_decode($response->getBody()->getContents(), true);

            if (empty($encounter['id'])) {
                return ['success' => false, 'error' => 'Encounter tidak ditemukan di SatuSehat'];
            }

            $waktuSekarang = now('Asia/Jakarta')->format('Y-m-d\TH:i:s+07:00');
            $encounter['status'] = $status;

            if (!isset($encounter['statusHistory']) || !is_array($encounter['statusHistory'])) {
                $encounter['statusHistory'] = [];
            }

            // SatuSehat Rule 10457: Set end date of previous statusHistory entry to current timestamp
            foreach ($encounter['statusHistory'] as &$sh) {
                if (empty($sh['period']['end'])) {
                    $sh['period']['end'] = $waktuSekarang;
                }
            }
            unset($sh);

            $newHistoryItem = [
                'status' => $status,
                'period' => ['start' => $waktuSekarang],
            ];
            if ($status === 'finished') {
                $newHistoryItem['period']['end'] = $waktuSekarang;
            }

            $encounter['statusHistory'][] = $newHistoryItem;

            if ($status === 'finished') {
                if (!isset($encounter['period'])) {
                    $encounter['period'] = [];
                }
                $encounter['period']['end'] = $waktuSekarang;
            }

            // Rule 10457: Require diagnosis reference when updating Encounter to finished
            if ($conditionId) {
                $encounter['diagnosis'] = [
                    [
                        'condition' => [
                            'reference' => 'Condition/' . $conditionId,
                            'display'   => $conditionDisplay ?: 'Encounter Diagnosis',
                        ],
                        'use' => [
                            'coding' => [
                                [
                                    'system'  => 'http://terminology.hl7.org/CodeSystem/diagnosis-role',
                                    'code'    => 'DD',
                                    'display' => 'Discharge diagnosis',
                                ],
                            ],
                        ],
                        'rank' => 1,
                    ],
                ];
            }

            $putResponse = $this->client->put($this->baseUrl . '/fhir-r4/v1/Encounter/' . $encounterId, [
                'headers' => $this->getHeaders(),
                'json'    => $encounter,
            ]);

            return [
                'success' => true,
                'data'    => json_decode($putResponse->getBody()->getContents(), true),
            ];
        } catch (\Exception $e) {
            $body = $this->getBody($e);
            Log::error('SatuSehat Update Encounter Error: ' . $e->getMessage() . ' | ' . $body);
            return ['success' => false, 'error' => $e->getMessage(), 'details' => $body];
        }
    }

    // =========================================================
    // CONDITION (DIAGNOSA / KELUHAN)
    // =========================================================

    /** POST Create Condition */
    public function createCondition(array $data): array
    {
        $payload = $this->buildConditionPayload($data);

        try {
            $response = $this->client->post($this->baseUrl . '/fhir-r4/v1/Condition', [
                'headers' => $this->getHeaders(),
                'json'    => $payload,
            ]);

            return [
                'success' => true,
                'data'    => json_decode($response->getBody()->getContents(), true),
            ];
        } catch (\Exception $e) {
            $body = $this->getBody($e);
            Log::error('SatuSehat Create Condition Error: ' . $e->getMessage() . ' | ' . $body);

            if (config('satusehat.use_dummy')) {
                return [
                    'success'  => true,
                    'data'     => array_merge($payload, ['id' => 'dummy-condition-' . rand(1000, 9999)]),
                    'is_dummy' => true,
                ];
            }

            return ['success' => false, 'error' => $e->getMessage(), 'details' => $body];
        }
    }

    /**
     * Payload builder untuk Condition FHIR R4 (Diagnosa / Keluhan)
     */
    protected function buildConditionPayload(array $data): array
    {
        $patientId   = $data['patient_id']   ?? '';
        $encounterId = $data['encounter_id'] ?? '';
        $keluhan     = $data['keluhan']      ?? 'Pemeriksaan Umum';
        $icdCode     = $data['icd_code']     ?? 'R50.9'; // Default ICD-10 Fever/Unspecified
        $icdDisplay  = $data['icd_display']  ?? 'Fever, unspecified';
        $recordedAt  = $data['datetime']     ?? now('Asia/Jakarta')->format('Y-m-d\TH:i:s+07:00');

        return [
            'resourceType' => 'Condition',
            'clinicalStatus' => [
                'coding' => [
                    [
                        'system'  => 'http://terminology.hl7.org/CodeSystem/condition-clinical',
                        'code'    => 'active',
                        'display' => 'Active',
                    ],
                ],
            ],
            'category' => [
                [
                    'coding' => [
                        [
                            'system'  => 'http://terminology.hl7.org/CodeSystem/condition-category',
                            'code'    => 'encounter-diagnosis',
                            'display' => 'Encounter Diagnosis',
                        ],
                    ],
                ],
            ],
            'code' => [
                'coding' => [
                    [
                        'system'  => 'http://hl7.org/fhir/sid/icd-10',
                        'code'    => $icdCode,
                        'display' => $icdDisplay,
                    ],
                ],
                'text' => $keluhan,
            ],
            'subject' => [
                'reference' => 'Patient/' . $patientId,
            ],
            'encounter' => [
                'reference' => 'Encounter/' . $encounterId,
            ],
            'recordedDate' => $recordedAt,
        ];
    }


    // =========================================================
    // OBSERVATION (VITAL SIGNS & REKAM MEDIS)
    // =========================================================

    /** POST Create Observation */
    public function createObservation(array $data): array
    {
        $payload = $this->buildObservationPayload($data);

        try {
            $response = $this->client->post($this->baseUrl . '/fhir-r4/v1/Observation', [
                'headers' => $this->getHeaders(),
                'json'    => $payload,
            ]);

            return [
                'success' => true,
                'data'    => json_decode($response->getBody()->getContents(), true),
            ];
        } catch (\Exception $e) {
            $body = $this->getBody($e);
            Log::error('SatuSehat Create Observation Error: ' . $e->getMessage() . ' | ' . $body);

            if (config('satusehat.use_dummy')) {
                return [
                    'success'  => true,
                    'data'     => array_merge($payload, ['id' => 'dummy-obs-' . rand(1000, 9999)]),
                    'is_dummy' => true,
                ];
            }

            return ['success' => false, 'error' => $e->getMessage(), 'details' => $body];
        }
    }

    /**
     * Payload builder untuk Observation FHIR R4 (SatuSehat compliant)
     */
    protected function buildObservationPayload(array $data): array
    {
        $now = now('Asia/Jakarta')->format('Y-m-d\TH:i:s+07:00');

        $payload = [
            'resourceType' => 'Observation',
            'status'       => 'final',
            'category'     => [
                [
                    'coding' => [
                        [
                            'system'  => 'http://terminology.hl7.org/CodeSystem/observation-category',
                            'code'    => 'vital-signs',
                            'display' => 'Vital Signs',
                        ],
                    ],
                ],
            ],
            'code' => [
                'coding' => [
                    [
                        'system'  => $data['system']  ?? 'http://loinc.org',
                        'code'    => $data['code']    ?? '8310-5',
                        'display' => $data['display'] ?? 'Body temperature',
                    ],
                ],
            ],
            'subject' => [
                'reference' => 'Patient/' . ($data['patient_id'] ?? ''),
            ],
            'encounter' => [
                'reference' => 'Encounter/' . ($data['encounter_id'] ?? ''),
            ],
            'effectiveDateTime' => $data['datetime'] ?? $now,
            'issued'            => $data['datetime'] ?? $now,
            'performer'         => [
                [
                    'reference' => 'Organization/' . $this->organizationId,
                ],
            ],
        ];

        if (isset($data['component'])) {
            $payload['component'] = $data['component'];
        } elseif (isset($data['value'])) {
            $payload['valueQuantity'] = [
                'value'  => (float) $data['value'],
                'unit'   => $data['unit'] ?? '',
                'system' => 'http://unitsofmeasure.org',
                'code'   => $data['unit_code'] ?? $data['unit'] ?? '',
            ];
        }

        return $payload;
    }

    /**
     * Kirim semua Tanda Vital ke SatuSehat untuk pendaftaran
     */
    public function syncVitalSigns(array $params): array
    {
        $patientId   = $params['patient_id'] ?? '';
        $encounterId = $params['encounter_id'] ?? '';
        $vitals      = $params['vitals'] ?? [];
        $synced      = [];
        $errors      = [];

        if (!$patientId || !$encounterId) {
            return ['success' => false, 'error' => 'Patient ID dan Encounter ID wajib ada'];
        }

        $now = now('Asia/Jakarta')->format('Y-m-d\TH:i:s+07:00');

        // 1. Suhu Tubuh (LOINC 8310-5)
        if (!empty($vitals['suhu'])) {
            $res = $this->createObservation([
                'patient_id'   => $patientId,
                'encounter_id' => $encounterId,
                'code'         => '8310-5',
                'display'      => 'Body temperature',
                'value'        => $vitals['suhu'],
                'unit'         => 'C',
                'unit_code'    => 'Cel',
                'datetime'     => $now,
            ]);
            if (!empty($res['success'])) { $synced[] = 'Suhu'; } else { $errors[] = 'Suhu: ' . ($res['error'] ?? ''); }
        }

        // 2. Tekanan Darah (LOINC 85354-9 Panel)
        if (!empty($vitals['tekanan_darah']) && str_contains($vitals['tekanan_darah'], '/')) {
            $parts = explode('/', $vitals['tekanan_darah']);
            $sys = (float) trim($parts[0]);
            $dia = (float) trim($parts[1] ?? 80);

            $res = $this->createObservation([
                'patient_id'   => $patientId,
                'encounter_id' => $encounterId,
                'code'         => '85354-9',
                'display'      => 'Blood pressure panel with all children mandatory',
                'datetime'     => $now,
                'component'    => [
                    [
                        'code' => ['coding' => [['system' => 'http://loinc.org', 'code' => '8480-6', 'display' => 'Systolic blood pressure']]],
                        'valueQuantity' => ['value' => $sys, 'unit' => 'mm[Hg]', 'system' => 'http://unitsofmeasure.org', 'code' => 'mm[Hg]'],
                    ],
                    [
                        'code' => ['coding' => [['system' => 'http://loinc.org', 'code' => '8462-4', 'display' => 'Diastolic blood pressure']]],
                        'valueQuantity' => ['value' => $dia, 'unit' => 'mm[Hg]', 'system' => 'http://unitsofmeasure.org', 'code' => 'mm[Hg]'],
                    ],
                ],
            ]);
            if (!empty($res['success'])) { $synced[] = 'Tekanan Darah'; } else { $errors[] = 'Tekanan Darah: ' . ($res['error'] ?? ''); }
        }

        // 3. Denyut Nadi (LOINC 8867-4)
        if (!empty($vitals['nadi'])) {
            $res = $this->createObservation([
                'patient_id'   => $patientId,
                'encounter_id' => $encounterId,
                'code'         => '8867-4',
                'display'      => 'Heart rate',
                'value'        => $vitals['nadi'],
                'unit'         => '/min',
                'unit_code'    => '/min',
                'datetime'     => $now,
            ]);
            if (!empty($res['success'])) { $synced[] = 'Nadi'; } else { $errors[] = 'Nadi: ' . ($res['error'] ?? ''); }
        }

        // 4. Laju Respirasi (LOINC 9279-1)
        if (!empty($vitals['respirasi'])) {
            $res = $this->createObservation([
                'patient_id'   => $patientId,
                'encounter_id' => $encounterId,
                'code'         => '9279-1',
                'display'      => 'Respiratory rate',
                'value'        => $vitals['respirasi'],
                'unit'         => '/min',
                'unit_code'    => '/min',
                'datetime'     => $now,
            ]);
            if (!empty($res['success'])) { $synced[] = 'Respirasi'; } else { $errors[] = 'Respirasi: ' . ($res['error'] ?? ''); }
        }

        // 5. Berat Badan (LOINC 29463-7)
        if (!empty($vitals['berat_badan'])) {
            $res = $this->createObservation([
                'patient_id'   => $patientId,
                'encounter_id' => $encounterId,
                'code'         => '29463-7',
                'display'      => 'Body weight',
                'value'        => $vitals['berat_badan'],
                'unit'         => 'kg',
                'unit_code'    => 'kg',
                'datetime'     => $now,
            ]);
            if (!empty($res['success'])) { $synced[] = 'Berat Badan'; } else { $errors[] = 'BB: ' . ($res['error'] ?? ''); }
        }

        // 6. Tinggi Badan (LOINC 8302-2)
        if (!empty($vitals['tinggi_badan'])) {
            $res = $this->createObservation([
                'patient_id'   => $patientId,
                'encounter_id' => $encounterId,
                'code'         => '8302-2',
                'display'      => 'Body height',
                'value'        => $vitals['tinggi_badan'],
                'unit'         => 'cm',
                'unit_code'    => 'cm',
                'datetime'     => $now,
            ]);
            if (!empty($res['success'])) { $synced[] = 'Tinggi Badan'; } else { $errors[] = 'TB: ' . ($res['error'] ?? ''); }
        }

        // 7. SpO2 (LOINC 59408-5)
        if (!empty($vitals['spo2'])) {
            $res = $this->createObservation([
                'patient_id'   => $patientId,
                'encounter_id' => $encounterId,
                'code'         => '59408-5',
                'display'      => 'Oxygen saturation in Arterial blood by Pulse oximetry',
                'value'        => $vitals['spo2'],
                'unit'         => '%',
                'unit_code'    => '%',
                'datetime'     => $now,
            ]);
            if (!empty($res['success'])) { $synced[] = 'SpO2'; } else { $errors[] = 'SpO2: ' . ($res['error'] ?? ''); }
        }

        return [
            'success' => count($synced) > 0,
            'synced'  => $synced,
            'errors'  => $errors,
        ];
    }

    /**
     * Master Orchestrator: Sync complete consultation (Patient, Encounter POST, Condition POST, Observation POST, Encounter PUT)
     */
    public function syncFullEncounter(array $params): array
    {
        $pasien      = $params['pasien'] ?? null;
        $pendaftaran = $params['pendaftaran'] ?? null;
        $dokter      = $params['dokter'] ?? null;

        if (!$pasien || !$pendaftaran) {
            return ['success' => false, 'error' => 'Data pasien dan pendaftaran wajib ada.'];
        }

        // 1. Patient lookup / sync
        if (!$pasien->satusehat_id) {
            $ssPatient = $this->getOrCreatePatient([
                'nik'           => $pasien->nik,
                'nama_lengkap'  => $pasien->nama_lengkap,
                'no_hp'         => $pasien->no_hp,
                'jenis_kelamin' => $pasien->jenis_kelamin,
                'tanggal_lahir' => $pasien->tanggal_lahir ? $pasien->tanggal_lahir->format('Y-m-d') : null,
                'alamat'        => $pasien->alamat,
                'kabupaten'     => $pasien->kabupaten,
                'kode_pos'      => $pasien->kode_pos,
            ]);

            if (!empty($ssPatient['success']) && !empty($ssPatient['data']['id'])) {
                $pasien->update(['satusehat_id' => $ssPatient['data']['id']]);
                $pasien->refresh();
            } else {
                return ['success' => false, 'error' => 'Gagal sync Pasien ke SatuSehat.'];
            }
        }

        // 2. Encounter POST (Create Encounter - arrived)
        $encounterData = [
            'patient_id'        => $pasien->satusehat_id,
            'nama_pasien'       => $pasien->nama_lengkap,
            'dokter_id'         => $dokter->satusehat_id ?? '',
            'nama_dokter'       => $dokter ? $dokter->nama_lengkap : '',
            'tanggal_kunjungan' => $pendaftaran->tanggal_kunjungan ? $pendaftaran->tanggal_kunjungan->format('Y-m-d') : date('Y-m-d'),
            'jam_kunjungan'     => $pendaftaran->jam_kunjungan ?: '08:00',
            'keluhan'           => $pendaftaran->keluhan ?: 'Pemeriksaan Umum',
            'kode_booking'      => $pendaftaran->kode_booking,
            'nama_poli'         => $pendaftaran->poli->nama ?? 'Rawat Jalan',
        ];

        $encRes = $this->createEncounter($encounterData);
        if (empty($encRes['success']) || empty($encRes['data']['id'])) {
            return ['success' => false, 'error' => 'Gagal create Encounter: ' . ($encRes['error'] ?? 'Unknown error')];
        }

        $encounterId = $encRes['data']['id'];

        // Save encounter_id to DB
        $pendaftaran->update([
            'satusehat_encounter_id' => $encounterId,
            'satusehat_response'     => $encRes['data'],
            'satusehat_status'       => 'success',
        ]);

        // 3. Condition POST (Clinical Condition / Keluhan Utama)
        $conditionRes = $this->createCondition([
            'patient_id'   => $pasien->satusehat_id,
            'encounter_id' => $encounterId,
            'keluhan'      => $pendaftaran->keluhan ?: 'Pemeriksaan Umum',
            'icd_code'     => 'R50.9',
            'icd_display'  => 'Fever, unspecified',
        ]);
        $conditionId = $conditionRes['data']['id'] ?? null;

        // 4. Observation POST (Vital Signs)
        $vitalsRes = $this->syncVitalSigns([
            'patient_id'   => $pasien->satusehat_id,
            'encounter_id' => $encounterId,
            'vitals'       => [
                'suhu'          => $pendaftaran->suhu ?: '36.5',
                'tekanan_darah' => $pendaftaran->tekanan_darah ?: '120/80',
                'nadi'          => $pendaftaran->nadi ?: '80',
                'respirasi'     => $pendaftaran->respirasi ?: '20',
                'berat_badan'   => $pendaftaran->berat_badan ?: '60',
                'tinggi_badan'  => $pendaftaran->tinggi_badan ?: '165',
                'spo2'          => $pendaftaran->spo2 ?: '98',
            ],
        ]);

        // 5. Encounter PUT (Update status to finished with diagnosis reference)
        $targetStatus = ($pendaftaran->status === 'selesai' || $pendaftaran->status === 'diproses') ? 'finished' : 'in-progress';
        $putRes = $this->updateEncounterStatus($encounterId, $targetStatus, $conditionId, 'Fever, unspecified');

        return [
            'success'       => true,
            'encounter_id'  => $encounterId,
            'condition_id'  => $conditionId,
            'observations'  => $vitalsRes['synced'] ?? [],
            'encounter_put' => !empty($putRes['success']),
        ];
    }

    // =========================================================
    // ORGANIZATION
    // =========================================================
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
        if (empty($value)) return false;
        return (bool) preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $value)
            || (bool) preg_match('/^[0-9]{5,15}$/', $value);
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
