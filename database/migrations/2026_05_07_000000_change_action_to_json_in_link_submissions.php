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
            // Convertir el valor actual a JSON antes de cambiar el tipo
            DB::statement("UPDATE link_submissions SET action = JSON_ARRAY(action) WHERE action IS NOT NULL");
            
            // Cambiar el tipo de columna a json
            $table->json('action')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('link_submissions', function (Blueprint $table) {
            // No hay forma segura de hacer reverse, así que simplemente revertir a string
            DB::statement("UPDATE link_submissions SET action = JSON_UNQUOTE(JSON_EXTRACT(action, '$[0]')) WHERE action IS NOT NULL");
            $table->string('action')->nullable()->change();
        });
    }
};
