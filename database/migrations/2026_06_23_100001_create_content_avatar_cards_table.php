<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_avatar_cards', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('headline');
            $table->text('subtext')->nullable();
            $table->string('cta_label')->nullable();
            $table->string('pathway_slug')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_avatar_cards');
    }
};
