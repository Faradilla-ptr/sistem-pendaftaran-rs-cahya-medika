<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$key = env('GEMINI_API_KEY');

// List available models from API
$url = "https://generativelanguage.googleapis.com/v1beta/models?key={$key}";
$res = Illuminate\Support\Facades\Http::get($url);
$data = $res->json();

foreach ($data['models'] as $m) {
    $name = $m['name']; // e.g. models/gemini-2.5-flash
    $t0 = microtime(true);
    $postUrl = "https://generativelanguage.googleapis.com/v1beta/{$name}:generateContent?key={$key}";
    try {
        $postRes = Illuminate\Support\Facades\Http::timeout(3)->post($postUrl, [
            'contents' => [['parts' => [['text' => 'Hi']]]]
        ]);
        $ms = round((microtime(true) - $t0) * 1000);
        echo "Endpoint {$name}: Status " . $postRes->status() . " ({$ms} ms)\n";
        if ($postRes->successful()) {
            echo "--> WORKING MODEL FOUND: {$name}!\n";
            break;
        }
    } catch (\Exception $e) {
        echo "Endpoint {$name}: Error " . $e->getMessage() . "\n";
    }
}
