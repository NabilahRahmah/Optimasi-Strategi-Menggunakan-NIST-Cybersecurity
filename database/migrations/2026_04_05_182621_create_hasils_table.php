<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hasils', function (Blueprint $table) {
            $table->id('hasil_id');
            $table->unsignedBigInteger('assessment_id');
            $table->unsignedBigInteger('domain_id');
            $table->decimal('nilai_kematangan', 4, 2)->default(0);
            $table->decimal('target_nilai', 4, 2)->default(4.00);
            $table->decimal('gap', 4, 2)->default(0);
            $table->string('level_kematangan')->nullable();
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
        Schema::dropIfExists('hasils');
    }
};