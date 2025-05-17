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
        Schema::table('comment_likes', function (Blueprint $table) {
            if (Schema::hasColumn('comment_likes', 'comment_id')) {
                $table->dropForeign(['comment_id']);
                $table->dropColumn('comment_id');
                $table->foreignId('comment_id')->constrained('comments')->onDelete('cascade');
            }
            if (Schema::hasColumn('comment_likes', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comment_likes', function (Blueprint $table) {
            //
        });
    }
};
