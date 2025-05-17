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
        Schema::table('document_categories', function (Blueprint $table) {
            if (Schema::hasColumn('document_categories', 'document_id')) {
                $table->dropForeign(['document_id']);
                $table->dropColumn('document_id');
                $table->foreignId('document_id')->constrained()->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_categories', function (Blueprint $table) {
            if (Schema::hasColumn('document_categories', 'document_id')) {
                $table->dropForeign(['document_id']);
                $table->dropColumn('document_id');
            }
        });
    }
};
