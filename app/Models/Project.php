<?php

namespace App\Models;

use App\Models\Concerns\HasPublication;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasPublication, SoftDeletes;

    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::saving(function (self $project): void {
            $project->gallery = array_values(array_unique(array_filter(array_map('intval', $project->gallery ?? []))));
        });
        static::updated(function (self $project): void {
            if ($project->wasChanged('slug')) {
                ProjectRedirect::firstOrCreate(['old_slug' => $project->getRawOriginal('slug')], ['project_id' => $project->id]);
            }
        });
    }

    protected function casts(): array
    {
        return ['technologies' => 'array', 'chapters' => 'array', 'gallery' => 'array', 'is_featured' => 'boolean', 'published_at' => 'datetime'];
    }
}
