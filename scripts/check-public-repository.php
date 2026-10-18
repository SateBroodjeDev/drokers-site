<?php

declare(strict_types=1);

// No dependencies or .env access. Inspect Git candidates, including tracked files ignored later.
chdir(dirname(__DIR__));

function gitOutput(array $arguments): string
{
    $process = proc_open(['git', ...$arguments], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
    if (! is_resource($process)) {
        throw new RuntimeException('Git could not be started.');
    }
    $output = stream_get_contents($pipes[1]);
    stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    if (proc_close($process) !== 0) {
        throw new RuntimeException('Git check failed; no file contents are printed.');
    }

    return $output;
}

$patterns = [
    'private key' => '~-----BEGIN (?:[A-Z0-9]+ )*PRIVATE KEY-----~',
    'GitHub token' => '~\b(?:gh[pousr]_[A-Za-z0-9]{30,}|github_pat_[A-Za-z0-9_]{40,})\b~',
    'AWS access key' => '~\b(?:AKIA|ASIA)[A-Z0-9]{16}\b~',
    'Slack token' => '~\bxox[baprs]-[A-Za-z0-9-]{20,}\b~',
    'Google API key' => '~\bAIza[A-Za-z0-9_-]{35}\b~',
    'Stripe live key' => '~\b(?:sk|rk)_live_[A-Za-z0-9]{20,}\b~',
    'Laravel application key' => '~\bAPP_KEY\s*=\s*["\x27]?base64:[A-Za-z0-9+/]{40,}={0,2}~',
    'credential in URL' => '~https?://[^\s/:@]+:[^\s/@]+@~',
    'hosting account identifier' => '~\bu\d{6}p\d{6}(?:_[A-Za-z0-9]+)?\b~',
];

$issues = [];
$inspect = static function (string $text, string $label) use ($patterns, &$issues): void {
    foreach ($patterns as $description => $pattern) {
        if (preg_match($pattern, $text)) {
            // Print only file and category, never the matched credential.
            $issues[] = "$label: $description";
        }
    }
};

$tracked = array_filter(explode("\0", gitOutput(['ls-files', '-z', '--cached'])));
$candidates = array_unique([...$tracked, ...array_filter(explode("\0", gitOutput(['ls-files', '-z', '--others', '--exclude-standard'])))]);
$forbidden = '~(?:^|/)(?:\.env(?:\..*)?|auth\.json|credentials\.json|service-account[^/]*\.json|id_rsa[^/]*|id_ed25519[^/]*|\.netrc|\.mcp\.json)$|\.(?:pem|key|p12|pfx|pdf|sqlite(?:3)?(?:-.*)?|db|sql(?:\..*)?|dump|bak|zip|7z|tgz|tar(?:\.gz)?)$|^(?:\.work|\.tools|\.codex|\.agents|\.aws|\.ssh|\.claude|vendor|node_modules|backups|artifacts)/|^public/(?:build|storage)/~i';
foreach ($candidates as $path) {
    $example = preg_match('~^\.env(?:\.[A-Za-z0-9_-]+)?\.example$~', $path);
    $runtime = preg_match('~^(?:storage/|bootstrap/cache/)~', $path) && basename($path) !== '.gitignore';
    if ((! $example && preg_match($forbidden, $path)) || $runtime) {
        $issues[] = "$path: private or generated file";
        continue;
    }
    if (is_link($path)) {
        $issues[] = "$path: symlink requires manual review";
        continue;
    }
    if (is_file($path)) {
        $contents = file_get_contents($path);
        if (! str_contains($contents, "\0")) {
            $inspect($contents, $path);
        }
    }
}

// A clean working tree does not prove that the staged versions are clean.
foreach ($tracked as $path) {
    $contents = gitOutput(['show', ':'.$path]);
    if (! str_contains($contents, "\0")) {
        $inspect($contents, "$path (index)");
    }
}
if (in_array('--history', $argv, true)) {
    $inspect(gitOutput(['log', '--all', '--format=', '--patch', '--no-ext-diff']), 'Git history');
}

if ($issues !== []) {
    fwrite(STDERR, implode("\n", array_unique($issues))."\nRepository check FAILED.\n");
    exit(1);
}
echo 'Repository check passed: '.count($candidates).' candidate files; working tree and index checked'.(in_array('--history', $argv, true) ? '; history patterns checked' : '').".\n";
echo "This focused check cannot detect every secret or replace a manual review.\n";
