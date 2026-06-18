<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_pendukungs', function (Blueprint $table) {
            $table->id('dok_id');

            // Siapa yang upload
            $table->unsignedBigInteger('user_id');

            // Dipetakan ke domain mana (GV, ID, PR, DE, RS, RC)
            $table->unsignedBigInteger('domain_id');

            // Info dokumen
            $table->string('nama_dokumen');
            $table->string('jenis_dokumen', 50)->default('Lainnya'); // string, bukan enum
            $table->text('deskripsi')->nullable();

            // File
            $table->string('file_path')->nullable();
            $table->string('nama_file_asli')->nullable();
            $table->unsignedInteger('ukuran_file')->nullable();

            // Status — string
            $table->string('status', 20)->default('aktif');

            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('domain_id')
                  ->references('domain_id')
                  ->on('domains')
                  ->onDelete('cascade');

            // Index
            $table->index(['user_id', 'domain_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_pendukungs');
    }
};