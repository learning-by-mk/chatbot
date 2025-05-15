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
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('topic', ['general', 'technical', 'content', 'partnership', 'other']);
            $table->text('message');
            $table->enum('status', ['pending', 'in_progress', 'resolved', 'rejected'])->default('pending');
            $table->text('admin_response')->nullable();
            $table->foreignId('responded_by_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
