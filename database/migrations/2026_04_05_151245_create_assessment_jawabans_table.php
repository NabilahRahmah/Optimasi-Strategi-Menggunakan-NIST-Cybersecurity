<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('assessment_jawabans', function (Blueprint $table) {
            $table->id('jawaban_id');
            $table->unsignedBigInteger('assessment_id');
            $table->unsignedBigInteger('kategori_id');
            $table->tinyInteger('indeks_nilai')
                ->nullable()
                ->comment('Skala 1-5');
            $table->string('file_bukti')->nullable();
            $table->enum('status_verifikasi', [
                'pending',
                'disetujui',
                'ditolak'
            ])->default('pending');
            $table->text('komentar_approver')->nullable();
            $table->timestamps();

            $table->foreign('assessment_id')
                ->references('assessment_id')
                ->on('assessments')
                ->onDelete('cascade');

            $table->foreign('kategori_id')
                ->references('kategori_id')
                ->on('kategoris')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_jawabans');
    }
};