<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_page_sections', function (Blueprint $table) {
            $table->foreignId('section_template_id')
                ->nullable()
                ->after('page_id')
                ->constrained('content_section_templates')
                ->nullOnDelete();

            $table->index('section_template_id');
        });
    }

    public function down(): void
    {
        Schema::table('content_page_sections', function (Blueprint $table) {
            $table->dropConstrainedForeignId('section_template_id');
        });
    }
};
