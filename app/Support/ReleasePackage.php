<?php

namespace App\Support;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use ZipArchive;

/** Builds an isolated release; never copies local configuration or runtime data. */
final class ReleasePackage
{
    public function prepare(string $root, string $destination): void
    {
        if (file_exists($destination) || is_link($destination)) throw new RuntimeException('De releasemap bestaat al; kies een nieuwe release-ID.');
        $this->directory($destination);
        foreach (['app', 'config', 'routes', 'resources', 'public', 'database/migrations', 'database/seeders'] as $tree) {
            $this->copyTree($root.'/'.$tree, $destination.'/'.$tree, $tree);
        }
        foreach (['artisan', 'composer.json', 'composer.lock', '.env.example', '.env.staging.example'] as $file) {
            $this->copyFile($root.'/'.$file, $destination.'/'.$file);
        }
        $this->copyFile($root.'/bootstrap/app.php', $destination.'/bootstrap/app.php');
        $this->copyFile($root.'/bootstrap/providers.php', $destination.'/bootstrap/providers.php');
        $this->copyFile($root.'/scripts/hosting-check.php', $destination.'/scripts/hosting-check.php');
        foreach (['bootstrap/cache', 'storage/app/private', 'storage/app/public', 'storage/logs', 'storage/framework/cache/data', 'storage/framework/sessions', 'storage/framework/views'] as $directory) {
            $this->directory($destination.'/'.$directory);
        }
        $manifest = $destination.'/public/build/manifest.json';
        if (! is_file($manifest)) throw new RuntimeException('De frontendbuild ontbreekt. Voer eerst npm run build uit.');
        $assets = json_decode(file_get_contents($manifest), true, flags: JSON_THROW_ON_ERROR);
        foreach ($assets as $asset) {
            foreach (array_merge([$asset['file'] ?? ''], $asset['css'] ?? [], $asset['assets'] ?? []) as $file) {
                if (! $file || str_contains($file, '..') || ! is_file($destination.'/public/build/'.$file)) throw new RuntimeException('De frontendbuild is onvolledig.');
            }
        }
    }

    public function archive(string $directory, string $archive, array $metadata = []): array
    {
        if (file_exists($archive) || is_link($archive)) throw new RuntimeException('Het releasearchief bestaat al.');
        $files = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS));
        foreach ($iterator as $file) {
            if ($file->isLink()) throw new RuntimeException('Symlinks worden niet in een release opgenomen.');
            if (! $file->isFile()) continue;
            $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($directory) + 1));
            if ($this->forbidden($relative)) throw new RuntimeException('Onverwachte configuratie of runtimegegevens in de releasemap: '.$relative);
            $files[$relative] = hash_file('sha256', $file->getPathname());
        }
        ksort($files);
        $manifest = ['schema' => 1, 'created_at_utc' => gmdate('c'), 'metadata' => $metadata, 'files' => $files];
        file_put_contents($directory.'/release-manifest.json', json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n");
        $zip = new ZipArchive;
        if ($zip->open($archive, ZipArchive::CREATE | ZipArchive::EXCL) !== true) throw new RuntimeException('Het releasearchief kan niet worden gemaakt.');
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iterator as $file) {
            $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($directory) + 1));
            $file->isDir() ? $zip->addEmptyDir($relative) : $zip->addFile($file->getPathname(), $relative);
        }
        $zip->close();
        $checksum = hash_file('sha256', $archive);
        file_put_contents($archive.'.sha256', $checksum.'  '.basename($archive)."\n");
        return ['archive' => $archive, 'sha256' => $checksum, 'files' => count($files)];
    }

    private function copyTree(string $source, string $destination, string $relative): void
    {
        if (! is_dir($source) || is_link($source)) throw new RuntimeException('Ontbrekende of ongeldige bronmap: '.$relative);
        $this->directory($destination);
        foreach (new FilesystemIterator($source, FilesystemIterator::SKIP_DOTS) as $file) {
            $path = $relative.'/'.$file->getFilename();
            if ($this->forbidden($path)) continue;
            if ($file->isLink()) throw new RuntimeException('Onverwachte symlink in de bronmap: '.$path);
            $file->isDir() ? $this->copyTree($file->getPathname(), $destination.'/'.$file->getFilename(), $path)
                : $this->copyFile($file->getPathname(), $destination.'/'.$file->getFilename());
        }
    }

    private function forbidden(string $path): bool
    {
        if (preg_match('~(^|/)(\.git|\.work|\.tools|node_modules|auth\.json)(/|$)~', $path)) return true;
        if (preg_match('~(^|/)\.env(?:\..*)?$~', $path) && ! in_array($path, ['.env.example', '.env.staging.example'], true)) return true;
        if (preg_match('~\.(sqlite(?:-.*)?|pem|key|log|pdf)$~i', $path)) return true;
        if (in_array($path, ['public/hot', 'public/storage'], true) || str_starts_with($path, 'public/storage/')) return true;
        if (str_starts_with($path, 'bootstrap/cache/') && $path !== 'bootstrap/cache/.gitignore') return true;
        return str_starts_with($path, 'storage/') && basename($path) !== '.gitignore';
    }

    private function copyFile(string $source, string $destination): void
    {
        if (! is_file($source) || is_link($source)) throw new RuntimeException('Ontbrekend of ongeldig bronbestand: '.basename($source));
        $this->directory(dirname($destination));
        if (! copy($source, $destination)) throw new RuntimeException('Een releasebestand kon niet worden gekopieerd.');
    }

    private function directory(string $path): void
    {
        if (! is_dir($path) && ! mkdir($path, 0755, true) && ! is_dir($path)) throw new RuntimeException('Een releasemap kon niet worden gemaakt.');
    }
}
