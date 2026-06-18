<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('assessment_jawabans', function (Blueprint $table) {
            $table->unique(['assessment_id', 'pertanyaan_id'], 'unique_jawaban_pertanyaan');
        });
    }

    public function down(): void
    {
        Schema::table('assessment_jawabans', function (Blueprint $table) {
            $table->dropUnique('unique_jawaban_pertanyaan');
        });
    }
};
