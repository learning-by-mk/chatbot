<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('setting_groups', function (Blueprint $table) {
            if (Schema::hasColumn('setting_groups', 'key')) {
                $table->string('key')->unique()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('setting_groups', function (Blueprint $table) {
            //
        });
    }
};
