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
            if (!Schema::hasColumn('history_points', 'document_id')) {
                $table->foreignId('document_id')->nullable()->constrained('documents')->onDelete('set null');
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
