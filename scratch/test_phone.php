<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$testCases = [
    '081234567890'   => true,  // 12 digits, starts with 08
    '0812345678'     => true,  // 10 digits, starts with 08
    '0812345678901'  => true,  // 13 digits, starts with 08
    '08123456789012' => false, // 14 digits (exceeds 13)
    '071234567890'   => false, // starts with 07 (not 08)
    '08123abc7890'   => false, // contains letters
    '08123'          => false, // under 10 digits
];

echo "--- TESTING BACKEND REGEX PATTERN /^08[0-9]{8,11}$/ ---" . PHP_EOL;
foreach ($testCases as $phone => $expected) {
    $valid = (bool) preg_match('/^08[0-9]{8,11}$/', $phone);
    $status = ($valid === $expected) ? "PASSED" : "FAILED";
    echo sprintf("[%s] Phone: '%s' -> Valid: %s (Expected: %s)" . PHP_EOL, $status, $phone, $valid ? 'YES' : 'NO', $expected ? 'YES' : 'NO');
}
