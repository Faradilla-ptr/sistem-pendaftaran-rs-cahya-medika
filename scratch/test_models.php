<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$key = env('GEMINI_API_KEY');
$models = ['gemini-2.5-flash', 'gemini-1.5-flash', 'gemini-flash-latest', 'gemini-2.0-flash', 'gemini-2.0-flash-exp'];

foreach ($models as $m) {
    $t0 = microtime(true);
    $url = "https://generativelanguage.googleapis.com/v1beta/models/{$m}:generateContent?key={$key}";
    $res = Illuminate\Support\Facades\Http::timeout(5)->post($url, [
        'contents' => [['parts' => [['text' => 'Hi']]]]
    ]);
    $ms = round((microtime(true) - $t0) * 1000);
    echo "Model {$m}: Status " . $res->status() . " ({$ms} ms)\n";
}
