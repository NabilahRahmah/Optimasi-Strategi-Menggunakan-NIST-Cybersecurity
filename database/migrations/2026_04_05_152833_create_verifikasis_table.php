<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('verifikasis', function (Blueprint $table) {
            $table->id('verifikasi_id');
            $table->unsignedBigInteger('assessment_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('jenis_verifikasi', [
                'disetujui',
                'ditolak'
            ]);
            $table->text('komentar')->nullable();
            $table->datetime('tgl_verif')->nullable();
            $table->timestamps();

            $table->foreign('assessment_id')
                ->references('assessment_id')
                ->on('assessments')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifikasis');
    }
};