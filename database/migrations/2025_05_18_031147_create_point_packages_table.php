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
        Schema::create('point_packages', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->integer('points');
            $table->integer('price');
            $table->boolean('is_active')->default(true);
            $table->integer('discount')->default(0);
            $table->boolean('popular')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_packages');
    }
};
