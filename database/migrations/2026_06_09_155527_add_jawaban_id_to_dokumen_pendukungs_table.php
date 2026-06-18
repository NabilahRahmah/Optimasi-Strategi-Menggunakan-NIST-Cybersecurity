<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dokumen_pendukungs', function (Blueprint $table) {
            // Link ke jawaban assessment (nullable — dokumen manual tidak punya ini)
            $table->unsignedBigInteger('jawaban_id')
                ->nullable()
                ->after('user_id');

            // Sumber dokumen: 'assessment' atau 'manual'
            $table->string('sumber', 20)
                ->default('manual')
                ->after('jawaban_id');

            $table->foreign('jawaban_id')
                ->references('jawaban_id')
                ->on('assessment_jawabans')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('dokumen_pendukungs', function (Blueprint $table) {
            $table->dropForeign(['jawaban_id']);
            $table->dropColumn(['jawaban_id', 'sumber']);
        });
    }
};