<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('assessment_jawabans', function (Blueprint $table) {
            // Jangan hapus kategori_id — data lama masih pakai ini
            // Tambah pertanyaan_id sebagai kolom baru, nullable dulu
            $table->unsignedBigInteger('pertanyaan_id')
                ->nullable()
                ->after('assessment_id');

            $table->foreign('pertanyaan_id')
                ->references('pertanyaan_id')
                ->on('pertanyaans')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('assessment_jawabans', function (Blueprint $table) {
            $table->dropForeign(['pertanyaan_id']);
            $table->dropColumn('pertanyaan_id');
        });
    }
};