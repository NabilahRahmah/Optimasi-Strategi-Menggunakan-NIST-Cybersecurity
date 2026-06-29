<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // TAHAP 1: Tambahkan user_id ke tabel assessment_jawabans
        Schema::table('assessment_jawabans', function (Blueprint $table) {
            // Pakai nullable() supaya kalau lu udah punya data lama, migration-nya nggak error
            $table->unsignedBigInteger('user_id')->nullable()->after('assessment_id');

            // Bikin relasi foreign key ke tabel users (karena primary key lu 'user_id')
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
        });

        // TAHAP 2: Buang user_id dari tabel assessments
        Schema::table('assessments', function (Blueprint $table) {
            // Hapus constraint foreign key dulu (Wajib dilakukan sebelum drop kolom)
            // Catatan: Kalau dulu lu bikin tabel assessments TANPA foreign key, 
            // baris dropForeign ini kasih comment (//) aja biar nggak error.
            $table->dropForeign(['user_id']);

            // Baru hapus kolomnya
            $table->dropColumn('user_id');
        });
    }

    public function down()
    {
        // INI BUAT JAGA-JAGA KALAU MAU DI-ROLLBACK

        // Kembalikan user_id ke assessments
        Schema::table('assessments', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('assessment_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
        });

        // Hapus user_id dari assessment_jawabans
        Schema::table('assessment_jawabans', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};