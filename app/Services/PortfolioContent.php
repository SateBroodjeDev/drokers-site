<?php

namespace App\Services;

use App\Models\ContentRecord;
use App\Models\MediaAsset;
use App\Models\Organization;
use App\Models\Project;
use App\Models\SiteProfile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class PortfolioContent
{
    public ?Model $preview = null;

    public function query(string $model): Builder
    {
        return $model::query()->where(function (Builder $query) use ($model): void {
            $query->published();
            if ($this->preview instanceof $model) $query->orWhere('id', $this->preview->id);
        })->orderBy('sort_order')->orderBy('id');
    }

    public function image(?int $id): ?array
    {
        if (! $id || ! ($media = MediaAsset::find($id))) return null;
        if (! $this->preview && ! $media->isReferenced(true)) return null;
        return ['url' => $this->preview ? route('cms.media', $media->uuid) : $media->publicUrl(), 'alt' => $media->alt_text ?? $media->title,
            'width' => $media->width, 'height' => $media->height, 'caption' => $media->caption,
            'background' => $media->background === 'dark' ? 'dark' : 'light'];
    }

    public function common(string $pageKey): array
    {
        $profileModel = $this->query(SiteProfile::class)->where('key', 'main')->first();
        $profile = $profileModel?->toArray() ?? ['name' => 'Portfolio', 'headline' => '', 'intro' => '', 'biography' => '', 'settings' => []];
        $site = array_replace([
            'theme' => 'paddock', 'appearance' => 'night', 'typography' => 'technical', 'motion' => true, 'show_world' => true,
            'show_development' => true, 'show_organizations' => true, 'links' => [], 'interests' => [], 'traits' => [], 'career' => [],
            'hero_heading' => 'Werk, ideeën & projecten', 'hero_kicker' => 'Welkom', 'hero_badge' => '', 'hero_note' => '',
            'world_heading' => 'De persoon achter het werk', 'world_intro' => '', 'footer_line' => '', 'footer_heading' => 'Even kennismaken.',
        ], $profile['settings'] ?? []);
        $themes = [
            'paddock' => ['accent' => '#f08045', 'background' => '#f2f3f6', 'ink' => '#172438', 'navy' => '#14243b'],
            'graphite' => ['accent' => '#ef725f', 'background' => '#f4f2f0', 'ink' => '#25262c', 'navy' => '#22232b'],
            'electric' => ['accent' => '#77c9ff', 'background' => '#f1f4f8', 'ink' => '#14283d', 'navy' => '#0d223e'],
        ];
        $basePalette = $themes[$site['theme']] ?? $themes['paddock'];
        $site['appearance'] = in_array($site['appearance'], ['night', 'light'], true) ? $site['appearance'] : 'night';
        foreach (['night', 'light'] as $appearance) {
            $palette = $basePalette;
            if ($appearance === 'night') {
                $palette['background'] = $site['theme'] === 'graphite' ? '#141319' : '#10121f';
                $palette['ink'] = '#f2eff7';
            }
            foreach (['accent', 'background', 'ink'] as $color) {
                if (($color === 'accent' || $appearance === $site['appearance']) && preg_match('/^#[0-9a-fA-F]{6}$/', $site[$color] ?? '')) $palette[$color] = $site[$color];
            }
            $site['palettes'][$appearance] = $palette;
        }
        $site['palette'] = $site['palettes'][$site['appearance']];
        $profile['interests'] = $site['interests'];
        $site['typography'] = in_array($site['typography'], ['technical', 'editorial']) ? $site['typography'] : 'technical';
        $site['links'] = collect($site['links'])->filter(fn ($link) => is_array($link) && preg_match('~^(https?://|mailto:[^\s@]+@)~i', $link['url'] ?? ''))->all();
        $username = trim($site['github_username'] ?? '');
        $site['github_url'] = GithubStatistics::validUsername($username) ? 'https://github.com/'.$username : null;
        if ($site['github_url'] && !collect($site['links'])->contains(fn ($link) => rtrim(strtolower($link['url']), '/') === strtolower($site['github_url']))) {
            $site['links'][] = ['label' => 'GitHub', 'url' => $site['github_url'], 'description' => 'Mijn openbare code en eigen digitale initiatieven'];
        }
        $githubSnapshot = $profileModel && $site['show_development'] && ($site['github_statistics'] ?? false) && $site['github_url']
            ? \App\Models\GithubSnapshot::where('username', strtolower($username))->whereNotNull('synced_at')->first() : null;
        $pages = $this->query(ContentRecord::class)->where('kind', 'page')->get()->keyBy('key');
        abort_unless($pages->has('page:'.$pageKey), 404);
        abort_if($pageKey === 'development' && ! $site['show_development'], 404);

        $currentPage = $pages->get('page:'.$pageKey);
        $worlds = $this->query(ContentRecord::class)->where('kind', 'world')->get();

        return [
            'profile' => $profile, 'site' => $site, 'page' => $pages->get('page:'.$pageKey), 'pages' => $pages,
            'githubSnapshot' => $githubSnapshot,
            'photo' => $this->image($profileModel?->photo_media_id), 'brandImage' => $this->image($profileModel?->brand_media_id),
            'heroImage' => $this->image($pageKey === 'home' && $currentPage->image_media_id ? $currentPage->image_media_id : $profileModel?->hero_media_id), 'isPreview' => $this->preview !== null,
            'pageImage' => $this->image($currentPage->image_media_id), 'worlds' => $worlds,
            'worldImages' => $worlds->mapWithKeys(fn ($world) => [$world->id => $this->image($world->image_media_id)]),
        ];
    }

    public function projects(): Collection
    {
        return $this->query(Project::class)->reorder()->orderByDesc('is_featured')->orderBy('sort_order')->orderBy('id')->get()->map(function (Project $project): array {
            return array_merge($project->toArray(), [
                'status' => $project->project_status, 'cover' => $this->image($project->cover_media_id),
                'gallery_images' => collect($project->gallery ?? [])->map(fn ($id) => $this->image((int) $id))->filter()->values()->all(),
                'technologies' => $project->technologies ?? [], 'chapters' => $project->chapters ?? [],
            ]);
        });
    }

    public function resume(): array
    {
        $records = $this->query(ContentRecord::class)->with('organization')->get();
        $organization = function (ContentRecord $record): ?Organization {
            $org = $record->organization;
            return $org && ($org->isPublished() || ($this->preview instanceof Organization && $this->preview->id === $org->id)) ? $org : null;
        };
        $experiences = fn (string $kind) => $records->where('kind', $kind)->map(function (ContentRecord $record) use ($organization): array {
            $org = $organization($record);
            return ['role' => $record->title, 'organization' => $org?->name ?? '', 'logo' => $this->image($org?->logo_media_id),
                'location' => $record->location, 'period' => $record->period, 'current' => $record->is_current,
                'arrangement' => $record->data['arrangement'] ?? null, 'summary' => $record->summary,
                'responsibilities' => $record->data['responsibilities'] ?? []];
        })->values()->all();
        $qualifications = fn (string $kind) => $records->where('kind', $kind)->map(function (ContentRecord $record) use ($organization): array {
            $org = $organization($record);
            return ['title' => $record->title, 'issuer' => $org?->name, 'logo' => $this->image($org?->logo_media_id), 'period' => $record->period, 'status' => $record->data['qualification_status'] ?? '', 'description' => $record->summary];
        })->values()->all();

        return [
            'experiences' => $experiences('work'), 'volunteering' => $experiences('volunteer'), 'initiatives' => $experiences('initiative'),
            'education' => $qualifications('education'), 'training' => $qualifications('training'),
            'skills' => $records->where('kind', 'skill')->map(fn ($record) => ['name' => $record->title, 'icon' => $record->data['icon'] ?? 'spark', 'items' => $record->data['items'] ?? []])->values()->all(),
            'languages' => $records->where('kind', 'language')->map(fn ($record) => ['name' => $record->title, 'code' => $record->data['code'] ?? '', 'level' => $record->data['level'] ?? ''])->values()->all(),
        ];
    }

    public function organizations(): Collection
    {
        return $this->query(Organization::class)->whereIn('id', $this->query(ContentRecord::class)->select('organization_id'))->get()
            ->map(fn ($org) => ['name' => $org->name, 'website' => $org->website, 'logo' => $this->image($org->logo_media_id)]);
    }
}
