<?php

namespace App\Models;

use App\Models\Concerns\HasPublication;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasPublication, SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array { return ['published_at' => 'datetime']; }

    public function logo(): BelongsTo { return $this->belongsTo(MediaAsset::class, 'logo_media_id'); }
}
