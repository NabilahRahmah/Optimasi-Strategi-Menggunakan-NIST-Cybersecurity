<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessment_jawabans', function (Blueprint $table) {
            // Tambah setelah kolom file_bukti
            $table->string('nama_file_asli')
                  ->nullable()
                  ->after('file_bukti')
                  ->comment('Nama file original dari user, terpisah dari path storage');

            // Sekalian tambah ukuran file untuk audit trail
            $table->unsignedInteger('ukuran_file')
                  ->nullable()
                  ->after('nama_file_asli')
                  ->comment('Ukuran file dalam bytes');
        });
    }

    public function down(): void
    {
        Schema::table('assessment_jawabans', function (Blueprint $table) {
            $table->dropColumn(['nama_file_asli', 'ukuran_file']);
        });
    }
};