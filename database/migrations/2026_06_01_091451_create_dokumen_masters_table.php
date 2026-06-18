<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dokumen_masters', function (Blueprint $table) {
            $table->id('master_id');
            $table->foreignId('domain_id')->constrained('domains', 'domain_id')->onDelete('cascade');
            $table->string('kode_kategori', 20); // GV.OC, ID.AM, dll (tidak FK, cukup string)
            $table->string('nama_dokumen', 255);
            $table->text('deskripsi')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_masters');
    }
};
