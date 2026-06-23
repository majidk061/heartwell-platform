<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('content_pages')->cascadeOnDelete();
            $table->string('section_type');
            $table->string('heading')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->json('content')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['page_id', 'sort_order']);
            $table->index('section_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_page_sections');
    }
};
