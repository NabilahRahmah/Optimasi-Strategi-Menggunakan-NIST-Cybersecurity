<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessment_jawabans', function (Blueprint $table) {
            $table->json('ukuran_file')->nullable()->change();
        });
    }
 
    public function down(): void
    {
        Schema::table('assessment_jawabans', function (Blueprint $table) {
            $table->unsignedInteger('ukuran_file')->nullable()->change();
        });
    }
};
