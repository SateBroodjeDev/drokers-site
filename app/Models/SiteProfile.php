<?php

namespace App\Models;

use App\Models\Concerns\HasPublication;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteProfile extends Model
{
    use HasPublication, SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['settings' => 'array', 'published_at' => 'datetime'];
    }
}
