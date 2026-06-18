<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rekomendasis', function (Blueprint $table) {
            $table->id('rekomendasi_id');
            $table->unsignedBigInteger('assessment_id');
            $table->unsignedBigInteger('domain_id');
            $table->text('deskripsi_perbaikan');
            $table->enum('prioritas', ['Tinggi', 'Sedang', 'Rendah'])
                  ->default('Sedang');
            $table->enum('sumber', ['otomatis', 'approver'])
                  ->default('otomatis');
            $table->timestamps();

            $table->foreign('assessment_id')
                  ->references('assessment_id')
                  ->on('assessments')
                  ->onDelete('cascade');

            $table->foreign('domain_id')
                  ->references('domain_id')
                  ->on('domains')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekomendasis');
    }
};