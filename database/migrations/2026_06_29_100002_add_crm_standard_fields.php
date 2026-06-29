<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->string('source_page')->nullable()->after('source');
            $table->string('preferred_contact_method')->nullable()->after('phone');
            $table->timestamp('last_contacted_at')->nullable()->after('assigned_to');
            $table->timestamp('next_follow_up_at')->nullable()->after('last_contacted_at');
            $table->string('priority')->default('normal')->after('status');
            $table->boolean('marketing_consent')->default(false)->after('priority');
            $table->timestamp('marketing_consent_at')->nullable()->after('marketing_consent');
            $table->string('utm_source')->nullable()->after('metadata');
            $table->string('utm_medium')->nullable()->after('utm_source');
            $table->string('utm_campaign')->nullable()->after('utm_medium');
            $table->json('tags')->nullable()->after('utm_campaign');
            $table->string('closed_reason')->nullable()->after('tags');
        });

        Schema::table('crm_consultation_requests', function (Blueprint $table) {
            $table->foreignId('assigned_to')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->timestamp('scheduled_at')->nullable()->after('assigned_to');
        });

        Schema::table('crm_waitlist_entries', function (Blueprint $table) {
            $table->timestamp('unsubscribed_at')->nullable()->after('status');
            $table->timestamp('mailchimp_synced_at')->nullable()->after('unsubscribed_at');
        });

        Schema::table('crm_group_inquiries', function (Blueprint $table) {
            $table->string('event_location')->nullable()->after('event_date');
            $table->string('event_type')->nullable()->after('event_location');
            $table->foreignId('assigned_to')->nullable()->after('status')->constrained('users')->nullOnDelete();
            $table->timestamp('coordinated_at')->nullable()->after('assigned_to');
        });
    }

    public function down(): void
    {
        Schema::table('crm_group_inquiries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_to');
            $table->dropColumn(['event_location', 'event_type', 'coordinated_at']);
        });

        Schema::table('crm_waitlist_entries', function (Blueprint $table) {
            $table->dropColumn(['unsubscribed_at', 'mailchimp_synced_at']);
        });

        Schema::table('crm_consultation_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_to');
            $table->dropColumn('scheduled_at');
        });

        Schema::table('crm_leads', function (Blueprint $table) {
            $table->dropColumn([
                'source_page', 'preferred_contact_method', 'last_contacted_at',
                'next_follow_up_at', 'priority', 'marketing_consent', 'marketing_consent_at',
                'utm_source', 'utm_medium', 'utm_campaign', 'tags', 'closed_reason',
            ]);
        });
    }
};
