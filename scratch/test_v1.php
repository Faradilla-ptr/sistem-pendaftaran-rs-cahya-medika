<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$key = env('GEMINI_API_KEY');
$models = [
    'gemini-2.5-flash',
    'gemini-1.5-flash',
    'gemini-2.0-flash',
    'gemini-2.5-pro',
    'gemini-flash-latest',
];

foreach ($models as $m) {
    // try v1
    $t0 = microtime(true);
    $url = "https://generativelanguage.googleapis.com/v1/models/{$m}:generateContent?key={$key}";
    try {
        $res = Illuminate\Support\Facades\Http::timeout(3)->post($url, [
            'contents' => [['parts' => [['text' => 'Hi']]]]
        ]);
        $ms = round((microtime(true) - $t0) * 1000);
        echo "v1 {$m}: Status " . $res->status() . " ({$ms} ms)\n";
    } catch (\Exception $e) {
        echo "v1 {$m}: Error " . $e->getMessage() . "\n";
    }

    // try v1beta
    $t0 = microtime(true);
    $url = "https://generativelanguage.googleapis.com/v1beta/models/{$m}:generateContent?key={$key}";
    try {
        $res = Illuminate\Support\Facades\Http::timeout(3)->post($url, [
            'contents' => [['parts' => [['text' => 'Hi']]]]
        ]);
        $ms = round((microtime(true) - $t0) * 1000);
        echo "v1beta {$m}: Status " . $res->status() . " ({$ms} ms)\n";
    } catch (\Exception $e) {
        echo "v1beta {$m}: Error " . $e->getMessage() . "\n";
    }
}
