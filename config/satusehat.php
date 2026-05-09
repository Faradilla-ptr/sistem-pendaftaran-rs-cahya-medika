<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SatuSehat API Configuration
    |--------------------------------------------------------------------------
    | Konfigurasi untuk integrasi dengan platform SatuSehat milik Kemenkes RI
    | Gunakan environment staging/sandbox untuk development
    */

    'base_url' => env('SATUSEHAT_BASE_URL', 'https://api-satusehat-stg.dto.kemkes.go.id'),
    'auth_url' => env('SATUSEHAT_AUTH_URL', 'https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1'),
    'client_id' => env('SATUSEHAT_CLIENT_ID', ''),
    'client_secret' => env('SATUSEHAT_CLIENT_SECRET', ''),
    'organization_id' => env('SATUSEHAT_ORGANIZATION_ID', ''),

    // Location ID RS (diperoleh saat mendaftarkan Location ke SatuSehat)
    // Jalankan: php test_setup_location.php untuk generate ID ini
    'location_id'     => env('SATUSEHAT_LOCATION_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | FHIR Resource Endpoints
    |--------------------------------------------------------------------------
    */
    'endpoints' => [
        'patient' => '/fhir-r4/v1/Patient',
        'practitioner' => '/fhir-r4/v1/Practitioner',
        'encounter' => '/fhir-r4/v1/Encounter',
        'organization' => '/fhir-r4/v1/Organization',
        'location' => '/fhir-r4/v1/Location',
        'condition' => '/fhir-r4/v1/Condition',
    ],

    /*
    |--------------------------------------------------------------------------
    | Mode Development (gunakan dummy data jika API tidak tersedia)
    |--------------------------------------------------------------------------
    */
    'use_dummy' => env('SATUSEHAT_USE_DUMMY', true),
];
