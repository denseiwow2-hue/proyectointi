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
        Schema::table('link_submissions', function (Blueprint $table) {
            $table->dropColumn('image_path');
            $table->json('image_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_submissions', function (Blueprint $table) {
            $table->dropColumn('image_path');
            $table->string('image_path')->nullable();
        });
    }
};
