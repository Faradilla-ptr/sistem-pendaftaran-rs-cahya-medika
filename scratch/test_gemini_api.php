<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$key = env('GEMINI_API_KEY');
echo "Testing Gemini API Key: " . substr($key, 0, 10) . "...\n";

$service = new \App\Services\GeminiOcrService();
// 1x1 pixel base64 image test
$tinyBase64 = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==";

$res = $service->parseKtpImage($tinyBase64, 'image/png');
echo "Result:\n";
print_r($res);
