<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_redirects', function (Blueprint $table): void {
            $table->id();
            $table->string('old_slug')->unique();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('project_redirects'); }
};
