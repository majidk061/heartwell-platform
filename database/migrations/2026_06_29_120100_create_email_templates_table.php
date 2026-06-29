<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('audience', 16)->default('user');
            $table->string('subject');
            $table->string('heading')->nullable();
            $table->longText('body')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('button_label')->nullable();
            $table->string('button_url')->nullable();
            $table->text('footer_text')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
