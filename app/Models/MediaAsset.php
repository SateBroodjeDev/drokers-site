<?php

namespace App\Models;

use App\Services\ImageStorage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MediaAsset extends Model
{
    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::creating(function (self $media): void { $media->uuid ??= (string) Str::uuid(); });
        static::saving(function (self $media): void {
            if ($media->isDirty('path')) {
                $result = app(ImageStorage::class)->normalize($media->path);
                $media->forceFill($result);
            }
        });
        static::deleting(function (self $media): void {
            if ($media->isReferenced()) {
                throw ValidationException::withMessages(['media' => 'Deze afbeelding is nog in gebruik, ook mogelijk in de prullenbak. Verwijder eerst de koppelingen.']);
            }
        });
        static::deleted(fn (self $media) => Storage::disk('local')->delete($media->path));
    }

    public function isReferenced(bool $publicOnly = false): bool
    {
        $profiles = $publicOnly ? SiteProfile::published() : SiteProfile::withTrashed();
        if ($profiles->where(fn ($query) => $query->where('photo_media_id', $this->id)->orWhere('brand_media_id', $this->id)->orWhere('hero_media_id', $this->id))->exists()) return true;

        $records = $publicOnly ? ContentRecord::published() : ContentRecord::withTrashed();
        if ($records->where('image_media_id', $this->id)->exists()) return true;

        $projects = $publicOnly ? Project::published() : Project::withTrashed();
        if ($projects->where(fn ($query) => $query->where('cover_media_id', $this->id)->orWhereJsonContains('gallery', $this->id))->exists()) return true;

        $organizations = $publicOnly ? Organization::published() : Organization::withTrashed();
        $organizations->where('logo_media_id', $this->id);
        if (! $publicOnly) return $organizations->exists();

        return $organizations->whereIn('id', ContentRecord::published()->select('organization_id'))->exists();
    }

    public function publicUrl(): string { return route('media.show', $this->uuid); }
}
