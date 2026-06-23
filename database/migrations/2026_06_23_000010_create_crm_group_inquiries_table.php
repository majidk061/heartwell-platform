<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crm_group_inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained('crm_leads')->nullOnDelete();
            $table->string('host_name');
            $table->string('host_email');
            $table->string('host_phone')->nullable();
            $table->string('event_name')->nullable();
            $table->date('event_date')->nullable();
            $table->unsignedSmallInteger('guest_count')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('pending');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('host_email');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_group_inquiries');
    }
};
