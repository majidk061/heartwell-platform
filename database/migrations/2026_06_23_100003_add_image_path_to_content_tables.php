<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_support_pathways', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('intro');
        });

        Schema::table('content_testimonials', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('author_name');
        });
    }

    public function down(): void
    {
        Schema::table('content_support_pathways', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('content_testimonials', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
};
