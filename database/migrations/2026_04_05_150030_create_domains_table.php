<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->id('domain_id');
            $table->unsignedBigInteger('framework_id');
            $table->string('kode_domain', 20);  
            $table->string('nama_domain');
            $table->timestamps();

            $table->foreign('framework_id')
                ->references('framework_id')
                ->on('frameworks')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};