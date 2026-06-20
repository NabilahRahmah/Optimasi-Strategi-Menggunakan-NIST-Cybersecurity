<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('assessment_jawabans', function (Blueprint $table) {
            $table->json('file_bukti')->nullable()->change();
            $table->json('nama_file_asli')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('assessment_jawabans', function (Blueprint $table) {
            $table->string('file_bukti')->nullable()->change();
            $table->string('nama_file_asli')->nullable()->change();
        });
    }
};
