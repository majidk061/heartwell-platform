<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->string('clinical_clearance_status')->default('pending')->after('status');
            $table->timestamp('clinical_cleared_at')->nullable()->after('clinical_clearance_status');
            $table->timestamp('clinical_clearance_expires_at')->nullable()->after('clinical_cleared_at');

            $table->index('clinical_clearance_status');
            $table->index('clinical_clearance_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('crm_leads', function (Blueprint $table) {
            $table->dropIndex(['clinical_clearance_status']);
            $table->dropIndex(['clinical_clearance_expires_at']);
            $table->dropColumn([
                'clinical_clearance_status',
                'clinical_cleared_at',
                'clinical_clearance_expires_at',
            ]);
        });
    }
};
