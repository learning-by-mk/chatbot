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
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'title')) {
                $table->string('title')->nullable();
            }
            if (!Schema::hasColumn('settings', 'sub_title')) {
                $table->string('sub_title')->nullable();
            }
            if (!Schema::hasColumn('settings', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            //
        });
    }
};
