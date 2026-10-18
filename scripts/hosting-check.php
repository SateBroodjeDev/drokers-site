<?php

// Standalone CLI check: no Laravel bootstrap, .env read, database access or writes.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$checks = [];
$add = function (string $name, bool $ok, string $detail, bool $required = true) use (&$checks): void {
    $checks[] = ['name' => $name, 'status' => $ok ? 'ok' : ($required ? 'fail' : 'warning'), 'detail' => $detail];
};
$add('PHP', version_compare(PHP_VERSION, '8.3.0', '>='), PHP_VERSION);
foreach (['ctype', 'curl', 'dom', 'fileinfo', 'filter', 'gd', 'hash', 'iconv', 'intl', 'mbstring', 'openssl', 'pdo', 'pdo_mysql', 'session', 'sodium', 'tokenizer', 'xml', 'xmlreader', 'xmlwriter', 'zip'] as $extension) {
    $add('ext-'.$extension, extension_loaded($extension), extension_loaded($extension) ? 'aanwezig' : 'ontbreekt');
}
$add('GD WebP', function_exists('imagewebp') && (gd_info()['WebP Support'] ?? false), 'nodig voor veilige afbeeldingsverwerking');
$root = dirname(__DIR__);
foreach (['bootstrap/cache', 'storage/app/private', 'storage/logs', 'storage/framework/cache/data', 'storage/framework/sessions', 'storage/framework/views'] as $path) {
    $add('Schrijfbaar: '.$path, is_dir($root.'/'.$path) && is_writable($root.'/'.$path), 'alleen rechten gecontroleerd; geen bestand aangemaakt');
}
$add('Vendor', is_file($root.'/vendor/autoload.php'), 'productiedependencies bij releasepakket', false);
$add('Frontendbuild', is_file($root.'/public/build/manifest.json'), 'Vite-manifest aanwezig');
$report = ['scope' => 'CLI-runtime en bestandssysteem; geen controle van web-PHP, databaseverbinding, HTTPS of caches',
    'php_binary' => PHP_BINARY, 'checks' => $checks,
    'passed' => ! array_filter($checks, fn ($check) => $check['status'] === 'fail')];
if (in_array('--json', $argv, true)) echo json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR).PHP_EOL;
else {
    echo $report['scope'].PHP_EOL.'PHP: '.PHP_BINARY.PHP_EOL;
    foreach ($checks as $check) echo strtoupper($check['status']).' | '.$check['name'].' | '.$check['detail'].PHP_EOL;
}
exit($report['passed'] ? 0 : 1);
