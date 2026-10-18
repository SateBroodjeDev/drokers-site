<?php

namespace App\Models;

use App\Models\Concerns\HasPublication;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentRecord extends Model
{
    use HasPublication, SoftDeletes;

    protected $guarded = ['id'];

    public const KINDS = [
        'work' => 'Werkervaring', 'volunteer' => 'Vrijwilligerswerk', 'initiative' => 'Eigen initiatief',
        'education' => 'Opleiding', 'training' => 'Diploma / training', 'skill' => 'Vaardigheidsgroep',
        'language' => 'Taal', 'statistic' => 'Statistiek', 'page' => 'Pagina', 'world' => 'Persoonlijk verhaal',
    ];

    protected function casts(): array { return ['data' => 'array', 'is_current' => 'boolean', 'published_at' => 'datetime']; }

    public function organization(): BelongsTo { return $this->belongsTo(Organization::class); }
}
