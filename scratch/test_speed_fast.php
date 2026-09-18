<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$key = env('GEMINI_API_KEY');
$t0 = microtime(true);
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-lite-latest:generateContent?key={$key}";
$res = Illuminate\Support\Facades\Http::timeout(5)->post($url, [
    'contents' => [['parts' => [['text' => 'Halo']]]]
]);
$ms = round((microtime(true) - $t0) * 1000);
echo "gemini-flash-lite-latest speed: {$ms} ms! Status: " . $res->status() . "\n";
