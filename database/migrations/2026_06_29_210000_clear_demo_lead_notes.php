<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('crm_leads')->update(['notes' => null]);
    }

    public function down(): void
    {
        //
    }
};
