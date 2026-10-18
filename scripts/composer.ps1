$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot
$phpDirectory = Join-Path $projectRoot '.tools/php'
$composerFile = Join-Path $projectRoot '.tools/composer.phar'
if (-not (Test-Path -LiteralPath $composerFile)) {
    throw 'Voer eerst .\scripts\bootstrap-windows.ps1 uit.'
}

$env:PATH = $phpDirectory + [System.IO.Path]::PathSeparator + $env:PATH
$env:COMPOSER_HOME = Join-Path $projectRoot '.tools/composer-home'
$env:COMPOSER_CACHE_DIR = Join-Path $projectRoot '.tools/composer-cache'
& (Join-Path $phpDirectory 'php.exe') $composerFile @args
exit $LASTEXITCODE
