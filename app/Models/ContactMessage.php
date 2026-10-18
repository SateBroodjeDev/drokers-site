<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

#[Fillable(['name', 'email', 'subject', 'message', 'handled_at'])]
class ContactMessage extends Model
{
    use Prunable;

    protected function casts(): array
    {
        return ['handled_at' => 'datetime'];
    }

    public function prunable(): Builder
    {
        return static::where('created_at', '<', now()->subDays(90));
    }
}
