<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$key = env('GEMINI_API_KEY');
$testModels = [
    'models/gemini-2.5-flash',
    'models/gemini-flash-latest',
    'models/gemini-2.5-flash-lite',
    'models/gemini-3.5-flash',
];

foreach ($testModels as $m) {
    $t0 = microtime(true);
    $url = "https://generativelanguage.googleapis.com/v1beta/{$m}:generateContent?key={$key}";
    $res = Illuminate\Support\Facades\Http::timeout(5)->post($url, [
        'contents' => [['parts' => [['text' => 'Hello']]]]
    ]);
    $ms = round((microtime(true) - $t0) * 1000);
    echo "Model {$m}: Status " . $res->status() . " ({$ms} ms)\n";
}
