<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained('booking_bookings')->nullOnDelete();
            $table->string('event_type');
            $table->json('payload')->nullable();
            $table->string('status')->default('received');
            $table->timestamps();

            $table->index(['booking_id', 'created_at']);
            $table->index('event_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_events');
    }
};
