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
        Schema::table('files', function (Blueprint $table) {
            if (!Schema::hasColumn('files', 'absolute_path_pdf')) {
                $table->string('absolute_path_pdf')->nullable();
            }
            if (!Schema::hasColumn('files', 'path_pdf')) {
                $table->string('path_pdf')->nullable();
            }
            if (!Schema::hasColumn('files', 'url_pdf')) {
                $table->string('url_pdf')->nullable();
            }
            if (!Schema::hasColumn('files', 'ext_pdf')) {
                $table->string('ext_pdf')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            //
        });
    }
};
