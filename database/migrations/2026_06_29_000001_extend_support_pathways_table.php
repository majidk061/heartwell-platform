<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_support_pathways', function (Blueprint $table) {
            $table->string('tagline')->nullable()->after('intro');
            $table->json('options_may_include')->nullable()->after('tagline');
            $table->text('common_support')->nullable()->after('options_may_include');
            $table->text('portal_cue')->nullable()->after('common_support');
            $table->text('selection_note')->nullable()->after('portal_cue');
            $table->text('coming_soon')->nullable()->after('selection_note');
        });
    }

    public function down(): void
    {
        Schema::table('content_support_pathways', function (Blueprint $table) {
            $table->dropColumn([
                'tagline',
                'options_may_include',
                'common_support',
                'portal_cue',
                'selection_note',
                'coming_soon',
            ]);
        });
    }
};
