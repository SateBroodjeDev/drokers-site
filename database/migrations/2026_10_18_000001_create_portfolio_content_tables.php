<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_assets', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title');
            $table->string('alt_text')->nullable();
            $table->string('path');
            $table->string('mime_type')->default('image/webp');
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->timestamps();
        });

        Schema::create('organizations', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('website')->nullable();
            $table->foreignId('logo_media_id')->nullable()->constrained('media_assets')->restrictOnDelete();
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('site_profiles', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('headline')->nullable();
            $table->text('intro')->nullable();
            $table->text('biography')->nullable();
            $table->foreignId('photo_media_id')->nullable()->constrained('media_assets')->restrictOnDelete();
            $table->foreignId('brand_media_id')->nullable()->constrained('media_assets')->restrictOnDelete();
            $table->foreignId('hero_media_id')->nullable()->constrained('media_assets')->restrictOnDelete();
            $table->json('settings')->nullable();
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('content_records', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->nullable()->unique();
            $table->string('kind')->index();
            $table->string('title');
            $table->foreignId('organization_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('image_media_id')->nullable()->constrained('media_assets')->restrictOnDelete();
            $table->string('location')->nullable();
            $table->string('period')->nullable();
            $table->boolean('is_current')->default(false);
            $table->text('summary')->nullable();
            $table->json('data')->nullable();
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('projects', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('domain')->nullable();
            $table->string('type')->default('Project');
            $table->string('project_status')->default('In ontwikkeling');
            $table->string('visual')->default('portfolio');
            $table->string('headline')->nullable();
            $table->string('subheadline')->nullable();
            $table->string('role')->nullable();
            $table->string('started')->nullable();
            $table->text('summary');
            $table->text('description')->nullable();
            $table->json('technologies')->nullable();
            $table->json('chapters')->nullable();
            $table->json('gallery')->nullable();
            $table->string('url')->nullable();
            $table->string('repository_url')->nullable();
            $table->foreignId('cover_media_id')->nullable()->constrained('media_assets')->restrictOnDelete();
            $table->boolean('is_featured')->default(true);
            $table->string('status')->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
        Schema::dropIfExists('content_records');
        Schema::dropIfExists('site_profiles');
        Schema::dropIfExists('organizations');
        Schema::dropIfExists('media_assets');
    }
};
