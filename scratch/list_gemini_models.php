<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$key = env('GEMINI_API_KEY');
$url = "https://generativelanguage.googleapis.com/v1beta/models?key={$key}";

$response = \Illuminate\Support\Facades\Http::get($url);
echo "Status: " . $response->status() . "\n";
$data = $response->json();

if (isset($data['models'])) {
    foreach ($data['models'] as $m) {
        if (in_array('generateContent', $m['supportedGenerationMethods'] ?? [])) {
            echo "Available model: " . $m['name'] . "\n";
        }
    }
} else {
    print_r($data);
}
