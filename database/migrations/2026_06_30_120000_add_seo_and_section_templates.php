<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_pages', function (Blueprint $table) {
            $table->boolean('robots_index')->nullable()->after('og_image');
            $table->string('canonical_url')->nullable()->after('robots_index');
            $table->string('og_type')->default('website')->after('canonical_url');
            $table->string('twitter_card')->default('summary_large_image')->after('og_type');
            $table->string('focus_keyword')->nullable()->after('twitter_card');
            $table->string('schema_type')->default('none')->after('focus_keyword');
            $table->boolean('include_in_sitemap')->default(true)->after('schema_type');
            $table->decimal('sitemap_priority', 2, 1)->default(0.8)->after('include_in_sitemap');
            $table->string('sitemap_changefreq')->default('weekly')->after('sitemap_priority');
        });

        Schema::create('content_section_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('section_type');
            $table->string('heading')->nullable();
            $table->json('content')->nullable();
            $table->json('layout')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_section_templates');

        Schema::table('content_pages', function (Blueprint $table) {
            $table->dropColumn([
                'robots_index',
                'canonical_url',
                'og_type',
                'twitter_card',
                'focus_keyword',
                'schema_type',
                'include_in_sitemap',
                'sitemap_priority',
                'sitemap_changefreq',
            ]);
        });
    }
};
