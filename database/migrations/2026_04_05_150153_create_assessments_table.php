<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id('assessment_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('framework_id');
            $table->string('judul_assessment');
            $table->date('tgl_pelaksanaan');
            $table->enum('status', [
                'draft',
                'submitted',
                'in_review',
                'disetujui',
                'rejected'
            ])->default('draft');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('framework_id')
                ->references('framework_id')
                ->on('frameworks')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};