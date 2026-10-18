<?php

// Build outside the active application, including an isolated production vendor/.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require __DIR__.'/../vendor/autoload.php';

use App\Support\ReleasePackage;

$options = getopt('', ['composer:', 'id:']);
$root = dirname(__DIR__);
$id = $options['id'] ?? gmdate('Ymd\THis\Z');
if (! preg_match('/^[a-zA-Z0-9][a-zA-Z0-9._-]{2,70}$/', $id)) throw new RuntimeException('Ongeldige release-ID.');
$composer = $options['composer'] ?? $root.'/.tools/composer.phar';
if (! is_file($composer)) throw new RuntimeException('Geef het Composer-bestand op met --composer=/pad/naar/composer.');
$base = $root.'/.work/releases';
if (! is_dir($base)) mkdir($base, 0755, true);
$directory = $base.'/drokers-'.$id;
$package = new ReleasePackage;
$package->prepare($root, $directory);

$run = function (array $command) use ($directory): void {
    $process = proc_open($command, [STDIN, STDOUT, STDERR], $pipes, $directory);
    if (! is_resource($process) || proc_close($process) !== 0) throw new RuntimeException('De releasebuild is gestopt; er is geen releasearchief gemaakt.');
};

// Only the isolated copy is changed. No key generation, migrations or content import.
putenv('APP_ENV=production');
putenv('APP_DEBUG=false');
$run([PHP_BINARY, $composer, 'install', '--no-dev', '--prefer-dist', '--no-interaction', '--no-progress', '--optimize-autoloader']);
$run([PHP_BINARY, $composer, 'check-platform-reqs', '--no-dev']);
$run([PHP_BINARY, $directory.'/artisan', 'filament:assets', '--no-interaction']);

// Never ship generated bootstrap caches or machine-specific configuration.
foreach (glob($directory.'/bootstrap/cache/*.php') ?: [] as $cache) unlink($cache);
$result = $package->archive($directory, $directory.'.zip', [
    'release_id' => $id, 'php_build_version' => PHP_VERSION, 'target_php' => '^8.3',
    'composer_lock_sha256' => hash_file('sha256', $root.'/composer.lock'),
    'frontend_manifest_sha256' => hash_file('sha256', $root.'/public/build/manifest.json'),
    'production_dependencies' => true, 'deployed' => false,
]);
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR).PHP_EOL;
