[CmdletBinding()]
param()

$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot

function Invoke-SetupStep {
    param([scriptblock] $Action)
    & $Action
    if ($LASTEXITCODE -ne 0) { throw 'De setup is gestopt doordat een stap niet slaagde.' }
}

Push-Location $projectRoot
try {
    & (Join-Path $PSScriptRoot 'bootstrap-windows.ps1')
    Invoke-SetupStep { & .\scripts\composer.ps1 install --prefer-dist --no-interaction }
    Invoke-SetupStep { & .\scripts\php.ps1 scripts/prepare-local.php }
    Invoke-SetupStep { & .\scripts\php.ps1 artisan migrate --no-interaction }
    Invoke-SetupStep { & .\scripts\php.ps1 artisan app:import-portfolio }
    Invoke-SetupStep { & .\scripts\php.ps1 artisan filament:assets }
    Invoke-SetupStep { npm.cmd ci --cache .tools/npm-cache --no-fund --no-audit }
    Invoke-SetupStep { npm.cmd run build }
    Write-Host 'Gereed. Maak je beheeraccount aan met: .\scripts\php.ps1 artisan app:create-admin'
    Write-Host 'Start de website met: .\scripts\php.ps1 artisan serve --host=127.0.0.1'
} finally {
    Pop-Location
}
