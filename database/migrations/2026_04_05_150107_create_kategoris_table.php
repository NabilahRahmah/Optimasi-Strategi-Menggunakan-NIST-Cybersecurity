<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id('kategori_id');
            $table->unsignedBigInteger('domain_id');
            $table->string('kode_kategori', 20);
            $table->string('nama_kategori');
            $table->text('deskripsi')->nullable();
            
            // --- TAMBAHAN BARU: Kolom untuk menyimpan teks rekomendasi Indeks 0-5 ---
            $table->text('indeks_0')->nullable();
            $table->text('indeks_1')->nullable();
            $table->text('indeks_2')->nullable();
            $table->text('indeks_3')->nullable();
            $table->text('indeks_4')->nullable();
            $table->text('indeks_5')->nullable();

            $table->timestamps();

            $table->foreign('domain_id')
                  ->references('domain_id')
                  ->on('domains')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategoris');
    }
};