[CmdletBinding()]
param()

$ErrorActionPreference = 'Stop'
$ProgressPreference = 'SilentlyContinue'
$projectRoot = Split-Path -Parent $PSScriptRoot
$toolsDirectory = Join-Path $projectRoot '.tools'
$phpDirectory = Join-Path $toolsDirectory 'php'
$phpVersion = '8.4.26'
$composerVersion = '2.10.3'

function Get-VerifiedDownload {
    param([string] $Uri, [string] $Destination, [string] $Sha256)

    if (-not (Test-Path -LiteralPath $Destination)) {
        Invoke-WebRequest -UseBasicParsing -Uri $Uri -OutFile $Destination
    }

    if ((Get-FileHash -LiteralPath $Destination -Algorithm SHA256).Hash -ne $Sha256) {
        throw "Controle van download mislukt: $Destination"
    }
}

New-Item -ItemType Directory -Force -Path $toolsDirectory | Out-Null

if (-not (Test-Path -LiteralPath (Join-Path $phpDirectory 'php.exe'))) {
    Write-Host "PHP $phpVersion downloaden en controleren..."
    $phpArchive = Join-Path $toolsDirectory "php-$phpVersion.zip"
    Get-VerifiedDownload `
        -Uri "https://downloads.php.net/~windows/releases/php-$phpVersion-nts-Win32-vs17-x64.zip" `
        -Destination $phpArchive `
        -Sha256 'da68394f9193b7f6b89d0c76861a4034ae10efee7fd55a7255d8118c2acf70d7'
    Expand-Archive -LiteralPath $phpArchive -DestinationPath $phpDirectory
}

$caFile = Join-Path $toolsDirectory 'cacert.pem'
if (-not (Test-Path -LiteralPath $caFile)) {
    Invoke-WebRequest -UseBasicParsing -Uri 'https://curl.se/ca/cacert.pem' -OutFile $caFile
}

$extensionDirectory = (Join-Path $phpDirectory 'ext').Replace('\', '/')
$certificatePath = $caFile.Replace('\', '/')
$phpConfiguration = @(
    'date.timezone=UTC'
    'memory_limit=512M'
    'max_execution_time=120'
    'post_max_size=8M'
    'upload_max_filesize=5M'
    "extension_dir=`"$extensionDirectory`""
    "curl.cainfo=`"$certificatePath`""
    "openssl.cafile=`"$certificatePath`""
    'extension=curl'
    'extension=fileinfo'
    'extension=gd'
    'extension=intl'
    'extension=mbstring'
    'extension=openssl'
    'extension=pdo_mysql'
    'extension=pdo_sqlite'
    'extension=sodium'
    'extension=sqlite3'
    'extension=zip'
)
[System.IO.File]::WriteAllLines((Join-Path $phpDirectory 'php.ini'), $phpConfiguration)

Write-Host "Composer $composerVersion controleren..."
Get-VerifiedDownload `
    -Uri "https://getcomposer.org/download/$composerVersion/composer.phar" `
    -Destination (Join-Path $toolsDirectory 'composer.phar') `
    -Sha256 '7a2d379d5b8ffdaa028580ef26494c36d2feef4b178d3dd1473a4dbc5e17c8d6'

& (Join-Path $phpDirectory 'php.exe') --version
if ($LASTEXITCODE -ne 0) { throw 'PHP kan niet worden gestart.' }
Write-Host 'De lokale tools staan klaar in .tools; de systeeminstallatie is niet aangepast.'
