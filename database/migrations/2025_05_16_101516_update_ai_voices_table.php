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
        Schema::table('ai_voices', function (Blueprint $table) {
            if (!Schema::hasColumn('ai_voices', 'url')) {
                $table->string('url')->nullable();
            }
            if (!Schema::hasColumn('ai_voices', 'absolute_path')) {
                $table->string('absolute_path')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_voices', function (Blueprint $table) {
            //
        });
    }
};
