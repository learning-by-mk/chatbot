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
        Schema::table('history_points', function (Blueprint $table) {
            if (!Schema::hasColumn('history_points', 'type')) {
                $table->enum('type', ['download', 'publish', 'other'])->default('other');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('history_points', function (Blueprint $table) {
            //
        });
    }
};
