<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** @var list<string> */
    private array $contentTables = [
        'content_pages',
        'content_section_templates',
        'content_faqs',
        'content_testimonials',
        'content_avatar_cards',
        'content_support_pathways',
    ];

    public function up(): void
    {
        foreach ($this->contentTables as $table) {
            Schema::table($table, function (Blueprint $blueprint) use ($table): void {
                if (! Schema::hasColumn($table, 'status')) {
                    $blueprint->string('status', 20)->default('published')->after('is_published');
                }

                if (! Schema::hasColumn($table, 'created_by_id')) {
                    $blueprint->foreignId('created_by_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
                }

                if (! Schema::hasColumn($table, 'updated_by_id')) {
                    $blueprint->foreignId('updated_by_id')->nullable()->after('created_by_id')->constrained('users')->nullOnDelete();
                }
            });

            DB::table($table)->where('is_published', true)->update(['status' => 'published']);
            DB::table($table)->where('is_published', false)->update(['status' => 'draft']);
        }

        Schema::create('content_revisions', function (Blueprint $table) {
            $table->id();
            $table->morphs('revisable');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('snapshot');
            $table->string('note')->nullable();
            $table->timestamp('created_at');
            $table->index(['revisable_type', 'revisable_id', 'created_at']);
        });

        $this->normalizeAvatarIntroColumns();
    }

    public function down(): void
    {
        Schema::dropIfExists('content_revisions');

        foreach ($this->contentTables as $table) {
            Schema::table($table, function (Blueprint $blueprint) use ($table): void {
                if (Schema::hasColumn($table, 'updated_by_id')) {
                    $blueprint->dropConstrainedForeignId('updated_by_id');
                }

                if (Schema::hasColumn($table, 'created_by_id')) {
                    $blueprint->dropConstrainedForeignId('created_by_id');
                }

                if (Schema::hasColumn($table, 'status')) {
                    $blueprint->dropColumn('status');
                }
            });
        }
    }

    private function normalizeAvatarIntroColumns(): void
    {
        DB::table('content_section_templates')
            ->where('section_type', 'avatar_intro')
            ->orderBy('id')
            ->lazy()
            ->each(function (object $row): void {
                $content = json_decode($row->content ?? '{}', true);

                if (! is_array($content)) {
                    return;
                }

                if (isset($content['columns']) && ! is_array($content['columns'])) {
                    $content['card_columns'] = (string) $content['columns'];
                    unset($content['columns']);
                }

                DB::table('content_section_templates')
                    ->where('id', $row->id)
                    ->update(['content' => json_encode($content)]);
            });
    }
};
