<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ImageStorage
{
    public function normalize(string $path): array
    {
        $disk = Storage::disk('local');
        if (str_contains($path, '..') || ! preg_match('~^(uploads|portfolio)/[a-zA-Z0-9_.\-/]+$~', $path)) {
            throw ValidationException::withMessages(['path' => 'Ongeldig afbeeldingsbestand.']);
        }
        $absolute = $disk->path($path);
        $info = is_file($absolute) ? @getimagesize($absolute) : false;
        if (! $info || ! in_array($info['mime'], ['image/jpeg', 'image/png', 'image/webp'], true)
            || filesize($absolute) > 5 * 1024 * 1024 || $info[0] * $info[1] > 16_000_000) {
            throw ValidationException::withMessages(['path' => 'Gebruik een JPEG, PNG of WebP van maximaal 5 MB en 16 megapixels.']);
        }
        if (str_starts_with($path, 'portfolio/') && $info['mime'] === 'image/webp') {
            return ['path' => $path, 'mime_type' => 'image/webp', 'width' => $info[0], 'height' => $info[1]];
        }
        $source = @imagecreatefromstring(file_get_contents($absolute));
        if (! $source) throw ValidationException::withMessages(['path' => 'De afbeelding kan niet worden gelezen.']);
        $ratio = min(1, 2400 / max($info[0], $info[1]));
        $width = (int) max(1, round($info[0] * $ratio));
        $height = (int) max(1, round($info[1] * $ratio));
        $image = imagecreatetruecolor($width, $height);
        imagealphablending($image, false);
        imagesavealpha($image, true);
        imagecopyresampled($image, $source, 0, 0, 0, 0, $width, $height, $info[0], $info[1]);
        $destination = 'portfolio/'.Str::uuid().'.webp';
        $disk->makeDirectory('portfolio');
        imagewebp($image, $disk->path($destination), 88);
        imagedestroy($source);
        imagedestroy($image);
        $disk->delete($path);

        return ['path' => $destination, 'mime_type' => 'image/webp', 'width' => $width, 'height' => $height];
    }
}
