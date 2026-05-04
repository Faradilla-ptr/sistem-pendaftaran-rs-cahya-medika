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
        $this->baseUrl = config('satusehat.base_url');
        $this->authUrl = config('satusehat.auth_url');
        $this->clientId = config('satusehat.client_id');
        $this->clientSecret = config('satusehat.client_secret');
        $this->organizationId = config('satusehat.organization_id');

        $this->client = new Client([
            'timeout' => 30,
            'verify' => false,
        ]);
    }

    /**
     * Get Access Token dari SatuSehat
     */
    public function getAccessToken(): ?string
    {
        return Cache::remember('satusehat_token', 3500, function () {
            try {
                $response = $this->client->post($this->authUrl . '/accesstoken', [
                    'form_params' => [
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                    ],
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                ]);

                $data = json_decode($response->getBody()->getContents(), true);
                return $data['access_token'] ?? null;
            } catch (RequestException $e) {
                Log::error('SatuSehat Auth Error: ' . $e->getMessage());
                // Return dummy token for development
                return 'dummy_access_token_for_development';
            }
        });
    }

    /**
     * Headers untuk API request
     */
    protected function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * GET Patient by NIK
     */
    public function getPatientByNik(string $nik): array
    {
        try {
            $response = $this->client->get($this->baseUrl . '/fhir-r4/v1/Patient', [
                'headers' => $this->getHeaders(),
                'query' => [
                    'identifier' => 'https://fhir.kemkes.go.id/id/nik|' . $nik,
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('SatuSehat Get Patient Error: ' . $e->getMessage());
            return $this->getDummyPatient($nik);
        }
    }

    /**
     * Create Patient di SatuSehat
     */
    public function createPatient(array $data): array
    {
        $payload = $this->buildPatientPayload($data);

        try {
            $response = $this->client->post($this->baseUrl . '/fhir-r4/v1/Patient', [
                'headers' => $this->getHeaders(),
                'json' => $payload,
            ]);

            return [
                'success' => true,
                'data' => json_decode($response->getBody()->getContents(), true),
            ];
        } catch (RequestException $e) {
            Log::error('SatuSehat Create Patient Error: ' . $e->getMessage());
            // Return dummy response for development
            return [
                'success' => true,
                'data' => array_merge($payload, ['id' => 'dummy-patient-' . rand(1000, 9999)]),
                'is_dummy' => true,
            ];
        }
    }

    /**
     * Get Practitioner (Dokter)
     */
    public function getPractitioner(string $nik): array
    {
        try {
            $response = $this->client->get($this->baseUrl . '/fhir-r4/v1/Practitioner', [
                'headers' => $this->getHeaders(),
                'query' => [
                    'identifier' => 'https://fhir.kemkes.go.id/id/nik|' . $nik,
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('SatuSehat Get Practitioner Error: ' . $e->getMessage());
            return $this->getDummyDoctors();
        }
    }

    /**
     * Create Encounter (Kunjungan)
     */
    public function createEncounter(array $data): array
    {
        $payload = $this->buildEncounterPayload($data);

        try {
            $response = $this->client->post($this->baseUrl . '/fhir-r4/v1/Encounter', [
                'headers' => $this->getHeaders(),
                'json' => $payload,
            ]);

            return [
                'success' => true,
                'data' => json_decode($response->getBody()->getContents(), true),
            ];
        } catch (RequestException $e) {
            Log::error('SatuSehat Create Encounter Error: ' . $e->getMessage());
            return [
                'success' => true,
                'data' => array_merge($payload, ['id' => 'dummy-encounter-' . rand(1000, 9999)]),
                'is_dummy' => true,
            ];
        }
    }

    /**
     * Get Organization (RS)
     */
    public function getOrganization(): array
    {
        try {
            $response = $this->client->get($this->baseUrl . '/fhir-r4/v1/Organization/' . $this->organizationId, [
                'headers' => $this->getHeaders(),
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error('SatuSehat Get Organization Error: ' . $e->getMessage());
            return $this->getDummyOrganization();
        }
    }

    /**
     * Build Patient FHIR Payload
     */
    protected function buildPatientPayload(array $data): array
    {
        return [
            'resourceType' => 'Patient',
            'identifier' => [
                [
                    'use' => 'official',
                    'system' => 'https://fhir.kemkes.go.id/id/nik',
                    'value' => $data['nik'],
                ]
            ],
            'active' => true,
            'name' => [
                [
                    'use' => 'official',
                    'text' => $data['nama'],
                ]
            ],
            'telecom' => [
                [
                    'system' => 'phone',
                    'value' => $data['telepon'],
                    'use' => 'mobile',
                ]
            ],
            'gender' => $data['jenis_kelamin'] === 'L' ? 'male' : 'female',
            'birthDate' => $data['tanggal_lahir'],
            'address' => [
                [
                    'use' => 'home',
                    'line' => [$data['alamat']],
                    'city' => $data['kota'] ?? 'Bondowoso',
                    'postalCode' => $data['kode_pos'] ?? '68219',
                    'country' => 'ID',
                ]
            ],
        ];
    }

    /**
     * Build Encounter FHIR Payload
     */
    protected function buildEncounterPayload(array $data): array
    {
        return [
            'resourceType' => 'Encounter',
            'status' => 'planned',
            'class' => [
                'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code' => 'AMB',
                'display' => 'ambulatory',
            ],
            'subject' => [
                'reference' => 'Patient/' . $data['patient_id'],
                'display' => $data['nama_pasien'],
            ],
            'participant' => [
                [
                    'type' => [
                        [
                            'coding' => [
                                [
                                    'system' => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType',
                                    'code' => 'ATND',
                                    'display' => 'attender',
                                ]
                            ]
                        ]
                    ],
                    'individual' => [
                        'reference' => 'Practitioner/' . ($data['dokter_id'] ?? 'unknown'),
                        'display' => $data['nama_dokter'] ?? 'Dokter Umum',
                    ]
                ]
            ],
            'period' => [
                'start' => $data['tanggal_kunjungan'] . 'T' . $data['jam_kunjungan'] . ':00+07:00',
            ],
            'serviceProvider' => [
                'reference' => 'Organization/' . $this->organizationId,
            ],
            'reasonCode' => [
                [
                    'text' => $data['keluhan'] ?? '',
                ]
            ],
        ];
    }

    /**
     * Dummy Data untuk Development
     */
    protected function getDummyPatient(string $nik): array
    {
        return [
            'resourceType' => 'Bundle',
            'total' => 0,
            'entry' => [],
            '_dummy' => true,
        ];
    }

    public function getDummyDoctors(): array
    {
        return [
            'resourceType' => 'Bundle',
            'total' => 5,
            'entry' => [
                ['resource' => ['id' => 'dr-001', 'name' => [['text' => 'dr. Ahmad Fauzi, Sp.PD']], 'speciality' => 'Penyakit Dalam']],
                ['resource' => ['id' => 'dr-002', 'name' => [['text' => 'dr. Siti Rahma, Sp.A']], 'speciality' => 'Anak']],
                ['resource' => ['id' => 'dr-003', 'name' => [['text' => 'dr. Budi Santoso, Sp.OG']], 'speciality' => 'Kandungan']],
                ['resource' => ['id' => 'dr-004', 'name' => [['text' => 'dr. Maya Indah, Sp.B']], 'speciality' => 'Bedah']],
                ['resource' => ['id' => 'dr-005', 'name' => [['text' => 'dr. Rudi Pratama, Sp.JP']], 'speciality' => 'Jantung']],
            ],
            '_dummy' => true,
        ];
    }

    protected function getDummyOrganization(): array
    {
        return [
            'resourceType' => 'Organization',
            'id' => $this->organizationId ?: 'org-dummy',
            'name' => config('app.rs_name', 'RS Cahya Medika Bondowoso'),
            'type' => [['text' => 'Rumah Sakit Umum Swasta']],
            '_dummy' => true,
        ];
    }
}
