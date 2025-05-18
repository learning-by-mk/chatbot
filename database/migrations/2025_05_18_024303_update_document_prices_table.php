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
        Schema::table('document_prices', function (Blueprint $table) {
            if (!Schema::hasColumn('document_prices', 'points')) {
                $table->integer('points')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_prices', function (Blueprint $table) {
            //
        });
    }
};
