[CmdletBinding()]
param()

$ErrorActionPreference = 'Stop'
$releaseProjectRoot = Split-Path -Parent $PSScriptRoot
$releasePhp = Join-Path $releaseProjectRoot '.tools/php/php.exe'
$releaseComposer = Join-Path $releaseProjectRoot '.tools/composer.phar'
if (-not (Test-Path -LiteralPath $releasePhp) -or -not (Test-Path -LiteralPath $releaseComposer)) {
    throw 'Gebruik eerst de lokale setup, of voer scripts/build-release.php met eigen PHP en Composer uit.'
}

Push-Location $releaseProjectRoot
try {
    npm.cmd run build
    if ($LASTEXITCODE -ne 0) { throw 'De frontendbuild is mislukt.' }
    $env:PATH = (Split-Path -Parent $releasePhp) + [System.IO.Path]::PathSeparator + $env:PATH
    $env:COMPOSER_HOME = Join-Path $releaseProjectRoot '.tools/composer-home'
    $env:COMPOSER_CACHE_DIR = Join-Path $releaseProjectRoot '.tools/composer-cache'
    & $releasePhp (Join-Path $PSScriptRoot 'build-release.php') "--composer=$releaseComposer"
    if ($LASTEXITCODE -ne 0) { throw 'De releasebuild is mislukt.' }
} finally { Pop-Location }
