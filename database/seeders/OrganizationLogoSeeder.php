<?php

namespace Database\Seeders;

use App\Models\MediaAsset;
use App\Models\Organization;
use App\Support\OrganizationLogos;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class OrganizationLogoSeeder extends Seeder
{
    public function run(): void
    {
        foreach (OrganizationLogos::all() as $key => $asset) {
            $organizations = Organization::whereIn('name', $asset['organizations'])->whereNull('logo_media_id')->get();
            $file = resource_path('brand-assets/organizations/'.$key.'.webp');
            if ($organizations->isEmpty() || ! is_file($file)) continue;

            $media = MediaAsset::where('source_url', $asset['source'])->where('title', 'Logo '.$asset['label'])->first();
            if (! $media) {
                $path = 'uploads/brand-'.$key.'.webp';
                Storage::disk('local')->put($path, file_get_contents($file));
                $media = MediaAsset::create([
                    'title' => 'Logo '.$asset['label'], 'alt_text' => 'Logo '.$asset['label'], 'path' => $path,
                    'source_label' => 'Officiële website '.$asset['label'], 'source_url' => $asset['source'], 'background' => $asset['background'],
                ]);
            }

            foreach ($organizations as $organization) $organization->update(['logo_media_id' => $media->id]);
        }
    }
}
