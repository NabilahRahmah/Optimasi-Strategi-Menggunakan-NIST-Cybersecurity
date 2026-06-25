<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Mengubah ENUM menjadi bahasa Indonesia
        // Pastikan semua status lama (draft, submitted, in_review) tetap disertakan
        DB::statement("ALTER TABLE assessments MODIFY status ENUM('draft', 'submitted', 'in_review', 'disetujui', 'ditolak')");
        
        // Mengubah data yang sudah ada di database agar sinkron
        DB::table('assessments')->where('status', 'rejected')->update(['status' => 'ditolak']);
        DB::table('assessments')->where('status', 'approved')->update(['status' => 'disetujui']);
    }

    public function down()
    {
        // Mengembalikan ENUM ke bahasa Inggris (untuk jaga-jaga/rollback)
        DB::statement("ALTER TABLE assessments MODIFY status ENUM('draft', 'submitted', 'in_review', 'approved', 'rejected')");
        
        // Mengembalikan data ke bahasa Inggris
        DB::table('assessments')->where('status', 'ditolak')->update(['status' => 'rejected']);
        DB::table('assessments')->where('status', 'disetujui')->update(['status' => 'approved']);
    }
};
