<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pertanyaans', function (Blueprint $table) {
            $table->id('pertanyaan_id');
            $table->foreignId('kategori_id')
                ->constrained('kategoris', 'kategori_id')
                ->onDelete('cascade');
            $table->string('kode_pertanyaan')->unique();
            $table->string('judul');
            $table->text('deskripsi');
            $table->text('indeks_0')->nullable();
            $table->text('indeks_1')->nullable();
            $table->text('indeks_2')->nullable();
            $table->text('indeks_3')->nullable();
            $table->text('indeks_4')->nullable();
            $table->text('indeks_5')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertanyaans');
    }
};
