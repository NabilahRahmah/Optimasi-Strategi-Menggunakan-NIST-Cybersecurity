<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Update data lama dulu sebelum ubah enum
        DB::table('assessment_jawabans')
            ->where('status_verifikasi', 'disetujui')
            ->update(['status_verifikasi' => 'approved']);

        DB::table('assessment_jawabans')
            ->where('status_verifikasi', 'ditolak')
            ->update(['status_verifikasi' => 'rejected']);

        // Ubah enum
        DB::statement("ALTER TABLE assessment_jawabans MODIFY COLUMN status_verifikasi ENUM('pending', 'approved', 'rejected') NULL DEFAULT NULL");
    }

    public function down(): void
    {
        // Rollback ke nilai lama
        DB::table('assessment_jawabans')
            ->where('status_verifikasi', 'approved')
            ->update(['status_verifikasi' => 'disetujui']);

        DB::table('assessment_jawabans')
            ->where('status_verifikasi', 'rejected')
            ->update(['status_verifikasi' => 'ditolak']);

        DB::statement("ALTER TABLE assessment_jawabans MODIFY COLUMN status_verifikasi ENUM('pending', 'disetujui', 'ditolak') NOT NULL DEFAULT 'pending'");
    }
};