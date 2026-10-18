<?php

namespace App\Http\Controllers;

use App\Models\MediaAsset;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function show(string $uuid)
    {
        $media = MediaAsset::where('uuid', $uuid)->firstOrFail();
        abort_unless($media->isReferenced(true), 404);
        return $this->response($media);
    }

    public function preview(string $uuid)
    {
        return $this->response(MediaAsset::where('uuid', $uuid)->firstOrFail());
    }

    private function response(MediaAsset $media)
    {
        $disk = Storage::disk('local');
        abort_unless($disk->exists($media->path), 404);
        return response()->file($disk->path($media->path), [
            'Content-Type' => 'image/webp', 'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, no-store', 'X-Robots-Tag' => 'noindex, nofollow',
        ]);
    }
}
