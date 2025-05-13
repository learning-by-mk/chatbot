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
        Schema::table('documents', function (Blueprint $table) {
            if (Schema::hasColumn($table->getTable(), 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected', 'draft'])->default('pending')->change();
            }
            if (!Schema::hasColumn($table->getTable(), 'file_id')) {
                $table->foreignId('file_id')->constrained('files');
            }
            if (Schema::hasColumn($table->getTable(), 'file_path')) {
                $table->dropColumn('file_path');
            }
            if (!Schema::hasColumn($table->getTable(), 'publish_date')) {
                $table->dateTime('publish_date')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (Schema::hasColumn($table->getTable(), 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected', 'draft'])->default('pending')->change();
            }
            // if (Schema::hasColumn($table->getTable(), 'file_id')) {
            //     $table->dropForeign(['file_id']);
            //     $table->dropColumn('file_id');
            // }
        });
    }
};
