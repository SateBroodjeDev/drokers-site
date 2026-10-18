#!/usr/bin/env bash
# Run on Vimexx over SSH, after the GitHub release. Only the reviewed additive contact migration is allowed.
set -Eeuo pipefail
umask 077

fail() { printf 'GESTOPT: %s\n' "$*" >&2; exit 1; }
mode="${1:-}"
release_id="${2:-}"
case "$mode" in prepare|backup|migrate|activate|rollback) ;; *) fail 'Gebruik: bash update-hosting.sh prepare|backup|migrate|activate|rollback RELEASE_ID' ;; esac
[[ "$release_id" =~ ^[a-zA-Z0-9][a-zA-Z0-9._-]{2,70}$ ]] || fail 'Ongeldige release-ID.'
[[ "$(uname -s)" == Linux ]] || fail 'Dit script is alleen voor de Linux-hostingserver.'

hosting_home="$(cd "$HOME" && pwd -P)"
project="$hosting_home/apps/drokers"
releases="$project/releases"
domain="$hosting_home/domains/domenicrokers.nl"
webroot="$domain/public_html"
release="$releases/$release_id"
state="$project/deploy-state/$release_id"
php='/opt/alt/php83/usr/bin/php'
reviewed_migration='2026_10_20_000001_create_contact_messages_table.php'

for program in readlink flock sha256sum unzip diff cmp mv ln find cp; do command -v "$program" >/dev/null || fail "Ontbrekend commando: $program"; done
[[ -x "$php" ]] || fail 'De verwachte PHP-runtime ontbreekt.'
[[ -d "$releases" && "$(readlink -f -- "$releases")" == "$releases" ]] || fail 'Onverwacht pad voor releases.'
[[ -d "$domain" && "$(readlink -f -- "$domain")" == "$domain" ]] || fail 'Onverwacht domeinpad.'
[[ -L "$webroot" ]] || fail 'public_html moet de eerder gemaakte Laravel-symlink zijn.'

exec 9>"$project/.release.lock"
flock -n 9 || fail 'Er draait al een update via deze hulp.'

valid_public() {
    local candidate="$1" relative
    [[ "$candidate" == "$releases/"*'/public' && -f "$candidate/index.php" ]] || return 1
    relative="${candidate#"$releases/"}"
    [[ "$relative" =~ ^[a-zA-Z0-9][a-zA-Z0-9._-]{2,70}/public$ ]]
}

point_webroot() {
    local destination="$1" temporary="$domain/.portfolio-$release_id"
    valid_public "$destination" || fail 'Ongeldig doel voor de webroot.'
    [[ ! -e "$temporary" && ! -L "$temporary" ]] || fail 'De tijdelijke koppeling bestaat al.'
    ln -sT -- "$destination" "$temporary"
    if ! mv -Tf -- "$temporary" "$webroot"; then
        unlink -- "$temporary"
        fail 'Omschakelen mislukt; de bestaande webroot is behouden.'
    fi
}

if [[ "$mode" == prepare ]]; then
    current_public="$(readlink -f -- "$webroot")"
    valid_public "$current_public" || fail 'De actieve webroot hoort niet bij een bekende applicatierelease.'
    previous="${current_public%/public}"
    [[ "$previous" != "$release" ]] || fail 'Deze release is al actief.'
    [[ ! -e "$release" && ! -L "$release" ]] || fail 'Deze releasemap bestaat al; niet overschrijven. Stuur de eerdere foutmelding.'
    [[ ! -e "$state" && ! -L "$state" ]] || fail 'Er bestaat al updatestatus voor deze release.'

    configuration="$(readlink -f -- "$previous/.env")"
    storage="$(readlink -f -- "$previous/storage")"
    [[ "$configuration" == "$project/"* && -f "$configuration" ]] || fail 'Bestaande private configuratie ontbreekt of ligt buiten de applicatiemap.'
    [[ "$storage" == "$project/"* && -d "$storage/app/private" ]] || fail 'Bestaande private opslag ontbreekt of ligt buiten de applicatiemap.'

    archive_name="drokers-$release_id.zip"
    [[ -f "$hosting_home/$archive_name" && -f "$hosting_home/$archive_name.sha256" ]] || fail 'ZIP of controlesom ontbreekt in de home-map.'
    # Accept only the checksum entry for this exact archive, not arbitrary filenames.
    read -r expected_hash expected_name < "$hosting_home/$archive_name.sha256"
    [[ "$expected_hash" =~ ^[a-fA-F0-9]{64}$ && "$expected_name" == "$archive_name" ]] || fail 'Ongeldig checksum-bestand.'
    actual_hash="$(sha256sum -- "$hosting_home/$archive_name")"
    [[ "${actual_hash%% *}" == "$expected_hash" ]] || fail 'De ZIP-controlesom komt niet overeen.'
    printf 'ZIP-controlesom: OK\n'

    mkdir -- "$release"
    chmod 0755 -- "$release"
    # Public assets need directory traversal/read access for the hosting webserver.
    umask 022
    unzip -q -- "$hosting_home/$archive_name" -d "$release"
    umask 077
    [[ -f "$release/release-manifest.json" && -f "$release/public/index.php" ]] || fail 'Onvolledig pakket.'
    cmp -s -- "$previous/composer.lock" "$release/composer.lock" || fail 'Dependencies verschillen. Deze updatehulp is alleen voor de voorbereide release zonder dependencywijzigingen.'
    for migration in "$previous"/database/migrations/*.php; do
        cmp -s -- "$migration" "$release/database/migrations/$(basename "$migration")" || fail 'Een bestaande migratie is gewijzigd of ontbreekt; stop voor beoordeling.'
    done
    for migration in "$release"/database/migrations/*.php; do
        migration_name="$(basename "$migration")"
        [[ -f "$previous/database/migrations/$migration_name" || "$migration_name" == "$reviewed_migration" ]] || fail 'Onverwachte nieuwe migratie; deze hulp werkt de eerdere GitHub-release bij naar de contactrelease.'
    done

    # Keep the freshly unpacked empty tree, then use the existing persistent data.
    [[ ! -e "$release/.env" && ! -L "$release/.env" ]] || fail 'Onverwachte .env in het pakket.'
    [[ -d "$release/storage" && ! -L "$release/storage" ]] || fail 'Onverwachte opslag in het pakket.'
    [[ -z "$(find "$release/storage" -type f ! -name .gitignore -print -quit)" ]] || fail 'Het pakket bevat onverwachte runtimegegevens.'
    mv -T -- "$release/storage" "$release/storage.release-empty"
    ln -sT -- "$storage" "$release/storage"
    ln -sT -- "$configuration" "$release/.env"

    # Keep old hashed assets reachable for browsers with a page from before the switch.
    if [[ -d "$previous/public/build/assets" ]]; then
        cp -an -- "$previous/public/build/assets/." "$release/public/build/assets/"
    fi
    cd "$release"
    "$php" scripts/hosting-check.php
    "$php" artisan --version
    "$php" artisan migrate:status --no-ansi
    "$php" artisan config:cache --no-ansi

    mkdir -p -- "$state"
    printf '%s\n' "$current_public" > "$state/previous-public"
    printf '%s\n' "$expected_hash" > "$state/archive-sha256"
    printf 'VOORBEREID: %s\nDe actieve website is nog niet omgeschakeld.\n' "$release_id"
    exit 0
fi

[[ -f "$state/previous-public" && -d "$release" && ! -L "$release" ]] || fail 'Geen voorbereide release gevonden.'
IFS= read -r previous_public < "$state/previous-public"
valid_public "$previous_public" || fail 'De vorige release is niet meer beschikbaar.'
valid_public "$release/public" || fail 'De nieuwe release is niet volledig beschikbaar.'
current_public="$(readlink -f -- "$webroot")"

if [[ "$mode" == backup || "$mode" == migrate ]]; then
    [[ "$current_public" == "$previous_public" ]] || fail 'De actieve release is gewijzigd.'
    backup_directory="$project/backups"
    [[ ! -L "$backup_directory" ]] || fail 'Onverwachte symlink voor back-ups.'
    mkdir -p -- "$backup_directory"
    [[ "$(readlink -f -- "$backup_directory")" == "$backup_directory" ]] || fail 'Onverwacht back-uppad.'
    backup_file="$backup_directory/$release_id.sql"
    cd "$release"
    # Read only non-secret connection fields; the password stays in the private configuration.
    database_target="$("$php" -r 'require "vendor/autoload.php"; $app = require "bootstrap/app.php"; $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); $c = config("database.connections.mysql"); if (config("database.default") !== "mysql" || $c["host"] !== "localhost" || !in_array((string) ($c["port"] ?? 3306), ["", "3306"], true) || !empty($c["url"]) || !empty($c["unix_socket"]) || !preg_match("/^[A-Za-z0-9_]+$/D", $c["database"]) || !preg_match("/^[A-Za-z0-9_]+$/D", $c["username"])) { fwrite(STDERR, "Een lokale MySQL-configuratie zonder URL of socket is vereist.\n"); exit(1); } echo $c["database"]." ".$c["username"];')"
    read -r database_name database_user <<< "$database_target"
    if [[ "$mode" == backup ]]; then
        command -v mysqldump >/dev/null || fail 'mysqldump ontbreekt.'
        [[ ! -e "$backup_file" && ! -L "$backup_file" && ! -e "$backup_file.partial" && ! -L "$backup_file.partial" ]] || fail 'Back-upbestand bestaat al; niet overschrijven.'
        printf 'Voer nu het wachtwoord van de portfoliodatabase in.\n'
        (set -o noclobber; mysqldump --single-transaction --quick --skip-lock-tables --no-tablespaces -h localhost -u "$database_user" -p "$database_name" > "$backup_file.partial")
        [[ -s "$backup_file.partial" ]] || fail 'Lege databaseback-up.'
        mv -T -- "$backup_file.partial" "$backup_file"
        chmod 0600 -- "$backup_file"
        sha256sum -- "$backup_file" > "$backup_file.sha256"
        printf '%s\n' "$backup_file" > "$state/database-backup"
        printf 'BACK-UP GEMAAKT: %s\nDownload ook een kopie buiten dit hostingaccount. Dit is nog geen hersteltest.\n' "$backup_file"
    else
        [[ -f "$state/database-backup" && -s "$backup_file" && -f "$backup_file.sha256" ]] || fail 'Maak eerst de databaseback-up met de backup-stap.'
        sha256sum -c -- "$backup_file.sha256"
        [[ -f "$release/database/migrations/$reviewed_migration" ]] || fail 'De beoordeelde contact-migratie ontbreekt.'
        "$php" artisan migrate --path="database/migrations/$reviewed_migration" --force --no-ansi
        "$php" -r 'require "vendor/autoload.php"; $app = require "bootstrap/app.php"; $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); if (!Illuminate\Support\Facades\Schema::hasTable("contact_messages")) exit(1);'
        touch -- "$state/migration-ready"
        printf 'MIGRATIE GEREED. De actieve website is nog niet omgeschakeld.\n'
    fi
    exit 0
fi

if [[ "$mode" == activate ]]; then
    if [[ -f "$release/database/migrations/$reviewed_migration" ]]; then
        [[ -f "$state/migration-ready" ]] || fail 'Voer eerst backup en migrate uit.'
    fi
    [[ "$current_public" == "$previous_public" ]] || fail 'De actieve versie is inmiddels gewijzigd; niet overschrijven.'
    [[ "$(readlink -f -- "$release/.env")" == "$(readlink -f -- "${previous_public%/public}/.env")" ]] || fail 'De configuratiekoppeling is gewijzigd.'
    [[ "$(readlink -f -- "$release/storage")" == "$(readlink -f -- "${previous_public%/public}/storage")" ]] || fail 'De opslagkoppeling is gewijzigd.'
    point_webroot "$release/public"
    printf 'ACTIEF: %s\nControleer homepage, cv, licht/donker, afbeeldingen en /admin in de browser.\n' "$release_id"
else
    [[ "$current_public" == "$release/public" ]] || fail 'Deze release is niet actief; niets teruggezet.'
    point_webroot "$previous_public"
    printf 'TERUGGEZET: %s\nDe database, sleutel en uploads zijn behouden.\n' "${previous_public%/public}"
fi
