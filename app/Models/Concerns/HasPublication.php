<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasPublication
{
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function isPublished(): bool
    {
        return ! $this->trashed() && $this->status === 'published'
            && $this->published_at !== null && $this->published_at->lte(now());
    }

    public function publish(): void
    {
        $this->forceFill(['status' => 'published', 'published_at' => now()])->save();
    }
}
