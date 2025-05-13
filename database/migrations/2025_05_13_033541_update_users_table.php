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
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn($table->getTable(), 'avatar')) {
                $table->dropColumn('avatar');
            }
            if (!Schema::hasColumn($table->getTable(), 'avatar_file_id')) {
                $table->foreignId('avatar_file_id')->nullable()->constrained('files')->onUpdate('cascade')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn($table->getTable(), 'avatar_file_id')) {
                $table->dropForeign(['avatar_file_id']);
                $table->dropColumn('avatar_file_id');
            }
        });
    }
};
