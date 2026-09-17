<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$key = env('GEMINI_API_KEY');
$lites = [
    'gemini-flash-lite-latest',
    'gemini-2.5-flash-lite',
    'gemini-3.1-flash-lite',
    'gemini-3.5-flash-lite',
];

foreach ($lites as $m) {
    $t0 = microtime(true);
    $url = "https://generativelanguage.googleapis.com/v1beta/models/{$m}:generateContent?key={$key}";
    try {
        $res = Illuminate\Support\Facades\Http::timeout(5)->post($url, [
            'contents' => [['parts' => [['text' => 'Fast test']]]]
        ]);
        $ms = round((microtime(true) - $t0) * 1000);
        echo "Model {$m}: Status " . $res->status() . " ({$ms} ms)\n";
    } catch (\Exception $e) {
        echo "Model {$m}: Error " . $e->getMessage() . "\n";
    }
}
