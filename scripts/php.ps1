$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot
$phpExecutable = Join-Path $projectRoot '.tools/php/php.exe'
if (-not (Test-Path -LiteralPath $phpExecutable)) {
    throw 'Voer eerst .\scripts\bootstrap-windows.ps1 uit, of gebruik een eigen PHP-installatie.'
}

& $phpExecutable @args
exit $LASTEXITCODE
