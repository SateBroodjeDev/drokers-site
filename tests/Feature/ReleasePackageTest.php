<?php

namespace Tests\Feature;

use App\Support\ReleasePackage;
use Illuminate\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use ZipArchive;

class ReleasePackageTest extends TestCase
{
    private string $sandbox;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sandbox = dirname(__DIR__, 2).'/.work/package-tests/'.bin2hex(random_bytes(8));
        mkdir($this->sandbox, 0755, true);
        foreach (['app', 'config', 'routes', 'resources', 'database/migrations', 'database/seeders'] as $directory) mkdir($this->sandbox.'/source/'.$directory, 0755, true);
        foreach (['artisan', 'composer.json', 'composer.lock', '.env.example', '.env.staging.example', 'bootstrap/app.php', 'bootstrap/providers.php', 'scripts/hosting-check.php'] as $file) $this->put('source/'.$file, 'safe fixture');
        $this->put('source/public/build/manifest.json', '{"app":{"file":"assets/app.css"}}');
        $this->put('source/public/build/assets/app.css', 'body{}');
        $this->put('source/public/.htaccess', 'RewriteEngine On');
        $this->put('source/public/index.php', '<?php');
    }

    protected function tearDown(): void
    {
        $allowed = realpath(dirname(__DIR__, 2).'/.work/package-tests');
        $target = realpath($this->sandbox);
        if ($allowed && $target && str_starts_with($target, $allowed.DIRECTORY_SEPARATOR)) (new Filesystem)->deleteDirectory($target);
        parent::tearDown();
    }

    private function put(string $relative, string $content): void
    {
        $path = $this->sandbox.'/'.$relative;
        if (! is_dir(dirname($path))) mkdir(dirname($path), 0755, true);
        file_put_contents($path, $content);
    }

    public function test_release_excludes_local_secrets_uploads_database_and_bootstrap_cache(): void
    {
        foreach (['.env', '.env.production', 'auth.json', 'database/private.sqlite', 'storage/app/private/portrait.webp', 'storage/logs/laravel.log', 'bootstrap/cache/config.php', 'public/hot', 'public/storage/upload.webp', 'resources/private.pdf', 'vendor/dev-package/test.php'] as $file) $this->put('source/'.$file, 'CANARY_PRIVATE_DATA');
        $package = new ReleasePackage;
        $package->prepare($this->sandbox.'/source', $this->sandbox.'/release');
        $result = $package->archive($this->sandbox.'/release', $this->sandbox.'/release.zip');
        $zip = new ZipArchive;
        $zip->open($result['archive']);
        for ($index = 0; $index < $zip->numFiles; $index++) {
            $this->assertStringNotContainsString('CANARY_PRIVATE_DATA', $zip->getFromIndex($index) ?: '');
        }
        $this->assertNotFalse($zip->locateName('public/.htaccess'));
        $this->assertNotFalse($zip->locateName('.env.staging.example'));
        $this->assertFalse($zip->locateName('.env'));
        $this->assertFalse($zip->locateName('vendor/dev-package/test.php'));
        $this->assertSame(hash_file('sha256', $result['archive']), $result['sha256']);
        $manifest = json_decode($zip->getFromName('release-manifest.json'), true, flags: JSON_THROW_ON_ERROR);
        $this->assertSame(hash('sha256', 'body{}'), $manifest['files']['public/build/assets/app.css']);
        $zip->close();
    }

    public function test_archiving_refuses_runtime_files_added_after_preparation(): void
    {
        $package = new ReleasePackage;
        $package->prepare($this->sandbox.'/source', $this->sandbox.'/release');
        $this->put('release/storage/app/private/accidental-upload.webp', 'private');
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('runtimegegevens');
        $package->archive($this->sandbox.'/release', $this->sandbox.'/release.zip');
    }

    public function test_release_refuses_an_incomplete_frontend_build(): void
    {
        $this->put('source/public/build/manifest.json', '{"app":{"file":"assets/missing.js"}}');
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('onvolledig');
        (new ReleasePackage)->prepare($this->sandbox.'/source', $this->sandbox.'/release');
    }

    public function test_release_never_overwrites_an_existing_directory(): void
    {
        $this->put('release/keep.txt', 'existing');
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('bestaat al');
        (new ReleasePackage)->prepare($this->sandbox.'/source', $this->sandbox.'/release');
    }
}
