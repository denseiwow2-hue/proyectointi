<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('link_submissions', function (Blueprint $table) {
            // Revertir action a string
            DB::statement("ALTER TABLE link_submissions MODIFY action VARCHAR(255) DEFAULT 'like-comentario'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_submissions', function (Blueprint $table) {
            DB::statement("ALTER TABLE link_submissions MODIFY action JSON");
        });
    }
};
