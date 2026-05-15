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
        Schema::create('link_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('platform');
            $table->text('url');
            $table->string('block')->nullable();
            $table->string('action')->default('subir imagen');
            $table->date('due_date')->nullable();
            $table->string('status')->default('pendiente');
            $table->string('image_path')->nullable();
            $table->text('admin_comment')->nullable();
            $table->text('user_comment')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_submissions');
    }
};
