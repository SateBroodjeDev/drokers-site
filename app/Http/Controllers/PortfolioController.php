<?php

namespace App\Http\Controllers;

use App\Models\ContentRecord;
use App\Models\Organization;
use App\Models\Project;
use App\Models\SiteProfile;
use App\Services\PortfolioContent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    public function __construct(protected PortfolioContent $content) {}

    protected function render(string $template, string $pageKey, array $data = []): View
    {
        view()->share($this->content->common($pageKey));
        return view($template, $data);
    }

    public function home(): View
    {
        return $this->render('home', 'home', [
            'projects' => $this->content->projects()->where('is_featured', true)->take(3),
            'careerPreview' => array_slice($this->content->resume()['experiences'], 0, 3),
        ]);
    }

    public function page(string $pageKey): View
    {
        return $this->render($pageKey, $pageKey, ['projectCount' => $this->content->projects()->count(), 'statistics' => $this->content->query(ContentRecord::class)->where('kind', 'statistic')->get()]);
    }

    public function resume(): View { return $this->render('cv', 'cv', $this->content->resume()); }
    public function printResume(): View { return $this->render('cv-print', 'cv', $this->content->resume()); }

    public function projects(Request $request): View
    {
        $filters = $request->validate(['q' => ['nullable', 'string', 'max:120'], 'techniek' => ['nullable', 'string', 'max:80']]);
        $query = trim($filters['q'] ?? '');
        $technology = $filters['techniek'] ?? '';
        $all = $this->content->projects();
        $technologies = $all->flatMap(fn ($project) => $project['technologies'])->unique()->sort()->values();
        $projects = $all->filter(function (array $project) use ($query, $technology): bool {
            $searchable = Str::lower(implode(' ', [$project['name'], $project['domain'], $project['summary'], $project['type'], $project['role'], implode(' ', $project['technologies'])]));
            return ($query === '' || str_contains($searchable, Str::lower($query)))
                && ($technology === '' || in_array(Str::lower($technology), array_map(Str::lower(...), $project['technologies']), true));
        });
        return $this->render('projects.index', 'projects', ['projects' => $projects, 'query' => $query, 'technology' => $technology, 'technologies' => $technologies, 'projectCount' => $all->count()]);
    }

    public function project(string $slug): View|\Illuminate\Http\RedirectResponse
    {
        $this->content->common('projects');
        $projects = $this->content->projects();
        $project = $projects->firstWhere('slug', $slug);
        if (! $project) {
            $redirect = \App\Models\ProjectRedirect::where('old_slug', $slug)->with('project')->first();
            if ($redirect?->project?->isPublished()) return redirect()->route('projects.show', $redirect->project->slug, 301);
        }
        abort_unless($project, 404);
        $index = $projects->search(fn ($item) => $item['slug'] === $slug);
        $nextProject = $projects->count() > 1 ? $projects->get(($index + 1) % $projects->count()) : null;
        return $this->render('projects.show', 'projects', compact('project', 'nextProject'));
    }

    public function preview(string $type, int $id): View
    {
        $models = ['site_profiles' => SiteProfile::class, 'content_records' => ContentRecord::class, 'organizations' => Organization::class, 'projects' => Project::class];
        abort_unless(isset($models[$type]), 404);
        $record = $models[$type]::findOrFail($id);
        $this->content->preview = $record;
        if ($record instanceof Project) return $this->project($record->slug);
        if ($record instanceof Organization) return $this->resume();
        if ($record instanceof ContentRecord) {
            if ($record->kind === 'statistic') return $this->page('development');
            if ($record->kind === 'page') {
                $key = Str::after($record->key, 'page:');
                return match ($key) { 'home' => $this->home(), 'cv' => $this->resume(), 'projects' => $this->projects(request()), default => $this->page($key) };
            }
            if ($record->kind !== 'world') return $this->resume();
        }
        return $this->home();
    }

    public function sitemap()
    {
        $common = $this->content->common('home');
        $routes = ['home' => 'home', 'about' => 'about', 'cv' => 'cv', 'projects' => 'projects', 'development' => 'development', 'contact' => 'contact', 'privacy' => 'privacy'];
        $urls = [];
        foreach ($routes as $key => $route) {
            if ($common['pages']->has('page:'.$key) && ($key !== 'development' || $common['site']['show_development'])) $urls[] = route($route);
        }
        if ($common['pages']->has('page:projects')) foreach ($this->content->projects() as $project) $urls[] = route('projects.show', $project['slug']);
        return response()->view('sitemap', compact('urls'))->header('Content-Type', 'application/xml');
    }
}
