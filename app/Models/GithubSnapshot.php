<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GithubSnapshot extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['synced_at' => 'datetime', 'attempted_at' => 'datetime', 'retry_after' => 'datetime', 'repositories' => 'integer', 'stars' => 'integer'];
    }
}
