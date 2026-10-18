<?php

declare(strict_types=1);

use Dotenv\Dotenv;

require __DIR__.'/../vendor/autoload.php';

$projectRoot = dirname(__DIR__);
$environmentPath = $projectRoot.'/.env';

if (! file_exists($environmentPath)) {
    copy($projectRoot.'/.env.example', $environmentPath);
}

$contents = file_get_contents($environmentPath);
$configuration = Dotenv::parse($contents);

if (($configuration['APP_ENV'] ?? '') !== 'local') {
    fwrite(STDERR, "Deze setup is uitsluitend voor APP_ENV=local.\n");
    exit(1);
}

if (empty($configuration['APP_KEY'])) {
    $key = 'base64:'.base64_encode(random_bytes(32));
    $contents = preg_replace('/^APP_KEY=.*$/m', 'APP_KEY='.$key, $contents, 1, $replacements);

    if ($replacements === 0) {
        $contents .= "\nAPP_KEY=$key\n";
    }

    file_put_contents($environmentPath, $contents);
    echo "Een lokale applicatiesleutel is aangemaakt.\n";
}

if (($configuration['DB_CONNECTION'] ?? 'sqlite') === 'sqlite') {
    $databasePath = $configuration['DB_DATABASE'] ?? $projectRoot.'/database/database.sqlite';

    if ($databasePath === ':memory:') {
        fwrite(STDERR, "Gebruik voor de lokale applicatie een blijvend SQLite-bestand.\n");
        exit(1);
    }

    if (! preg_match('~^(?:[A-Za-z]:[/\\\\]|/)~', $databasePath)) {
        $databasePath = $projectRoot.'/'.$databasePath;
    }

    $databaseDirectory = realpath(dirname($databasePath));
    if ($databaseDirectory === false || ! str_starts_with($databaseDirectory.DIRECTORY_SEPARATOR, $projectRoot.DIRECTORY_SEPARATOR)) {
        fwrite(STDERR, "Het lokale SQLite-bestand moet binnen dit project staan.\n");
        exit(1);
    }

    if (! file_exists($databasePath)) {
        touch($databasePath);
        echo "De lokale SQLite-database is aangemaakt.\n";
    }
}

echo "Lokale configuratie staat klaar; bestaande sleutels en gegevens blijven behouden.\n";
