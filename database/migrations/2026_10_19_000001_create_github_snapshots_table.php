<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('github_snapshots', function (Blueprint $table): void {
            $table->id();
            $table->string('username', 39)->unique();
            $table->unsignedInteger('repositories')->default(0);
            $table->unsignedBigInteger('stars')->default(0);
            $table->timestamp('synced_at')->nullable();
            $table->timestamp('attempted_at')->nullable();
            $table->timestamp('retry_after')->nullable();
            $table->string('error_category')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('github_snapshots'); }
};
