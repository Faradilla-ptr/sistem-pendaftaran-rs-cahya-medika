<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$key = env('GEMINI_API_KEY');
$t0 = microtime(true);
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash-lite:generateContent?key={$key}";

$tinyBase64 = "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==";

$res = Illuminate\Support\Facades\Http::timeout(5)->post($url, [
    'contents' => [
        [
            'parts' => [
                ['text' => 'Is this a KTP card? JSON response {"is_ktp": false}'],
                [
                    'inline_data' => [
                        'mime_type' => 'image/png',
                        'data'      => $tinyBase64,
                    ]
                ]
            ]
        ]
    ],
    'generationConfig' => [
        'temperature'        => 0.0,
        'maxOutputTokens'    => 150,
        'response_mime_type' => 'application/json',
    ]
]);

$ms = round((microtime(true) - $t0) * 1000);
echo "gemini-3.5-flash-lite vision speed: {$ms} ms! Status: " . $res->status() . "\n";
echo "Response body: " . $res->body() . "\n";
