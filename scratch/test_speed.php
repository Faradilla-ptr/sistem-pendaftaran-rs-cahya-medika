<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$start = microtime(true);
$service = new \App\Services\GeminiOcrService();
$tinyBase64 = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==";

$res = $service->parseKtpImage($tinyBase64, 'image/png');
$elapsed = round((microtime(true) - $start) * 1000);

echo "Execution time: {$elapsed} ms\n";
print_r($res);
