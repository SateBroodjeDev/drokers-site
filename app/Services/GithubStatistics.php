<?php

namespace App\Services;

use App\Models\GithubSnapshot;
use App\Models\SiteProfile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

class GithubStatistics
{
    public static function validUsername(?string $username): bool
    {
        return is_string($username) && preg_match('/\A[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,37}[a-zA-Z0-9])?\z/', $username) && !str_contains($username, '--');
    }

    public function sync(SiteProfile $profile): string
    {
        $settings = $profile->settings ?? [];
        $username = strtolower(trim($settings['github_username'] ?? ''));
        if (!($settings['github_statistics'] ?? false) || !self::validUsername($username)) return 'Koppeling uitgeschakeld of gebruikersnaam ontbreekt.';
        $lock = Cache::lock('github-sync:'.$username, 180);
        if (!$lock->get()) return 'Er draait al een synchronisatie.';
        try {
            $snapshot = GithubSnapshot::firstOrCreate(['username' => $username]);
            if ($snapshot->retry_after?->isFuture() || $snapshot->attempted_at?->gt(now()->subMinutes(5))) return 'Wacht tot de volgende toegestane poging.';
            $snapshot->update(['attempted_at' => now()]);
            $seen = [];
            $repositories = $stars = 0;
            $deadline = microtime(true) + 45;
            try {
                for ($page = 1; $page <= 100; $page++) {
                    if (microtime(true) > $deadline) throw new RuntimeException('incomplete');
                    $request = Http::accept('application/vnd.github+json')->withHeaders(['User-Agent' => 'DomenicRokers-Portfolio', 'X-GitHub-Api-Version' => '2026-03-10'])->connectTimeout(4)->timeout(10)->withoutRedirecting();
                    if ($token = config('services.github.token')) $request = $request->withToken($token);
                    $response = $request->get('https://api.github.com/users/'.rawurlencode($username).'/repos', ['per_page' => 100, 'page' => $page, 'type' => 'owner', 'sort' => 'full_name', 'direction' => 'asc']);
                    if (!$response->successful()) {
                        if (in_array($response->status(), [403, 429], true)) {
                            $seconds = max(300, (int)$response->header('Retry-After'), (int)$response->header('X-RateLimit-Reset') - now()->timestamp);
                            $snapshot->update(['retry_after' => now()->addSeconds(min(86400, $seconds))]);
                        }
                        throw new RuntimeException(match ($response->status()) { 401 => 'authentication', 403, 429 => 'rate_limit', 404 => 'account_not_found', default => 'api_unavailable' });
                    }
                    $items = $response->json();
                    if (!is_array($items) || !array_is_list($items)) throw new RuntimeException('invalid_response');
                    foreach ($items as $item) {
                        if (!is_array($item) || !is_int($item['id'] ?? null) || !is_bool($item['private'] ?? null) || !is_bool($item['fork'] ?? null) || !is_bool($item['archived'] ?? null) || !is_string($item['owner']['login'] ?? null) || !is_int($item['stargazers_count'] ?? null) || $item['stargazers_count'] < 0) throw new RuntimeException('invalid_response');
                        if (isset($seen[$item['id']])) continue;
                        $seen[$item['id']] = true;
                        if ($item['private'] || $item['fork'] || $item['archived'] || strcasecmp($item['owner']['login'], $username) !== 0) continue;
                        $repositories++;
                        $stars += $item['stargazers_count'];
                    }
                    if (count($items) < 100) break;
                    if ($page === 100) throw new RuntimeException('incomplete');
                }
                // Publish only a complete result; failures retain the previous measurement.
                $snapshot->update(['repositories' => $repositories, 'stars' => $stars, 'synced_at' => now(), 'retry_after' => null, 'error_category' => null]);
                return 'GitHub-statistieken bijgewerkt.';
            } catch (Throwable $exception) {
                $category = $exception instanceof RuntimeException && in_array($exception->getMessage(), ['incomplete','invalid_response','authentication','rate_limit','account_not_found','api_unavailable'], true) ? $exception->getMessage() : 'connection';
                $snapshot->update(['error_category' => $category]);
                return 'Synchronisatie niet geslaagd ('.$category.'). De vorige meting is behouden.';
            }
        } finally { $lock->release(); }
    }
}
