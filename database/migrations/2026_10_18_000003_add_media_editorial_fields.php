<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media_assets', function (Blueprint $table): void {
            $table->text('caption')->nullable();
            $table->string('source_label')->nullable();
            $table->text('source_url')->nullable();
            $table->string('background')->default('light');
        });
    }

    public function down(): void
    {
        Schema::table('media_assets', fn (Blueprint $table) => $table->dropColumn(['caption', 'source_label', 'source_url', 'background']));
    }
};
