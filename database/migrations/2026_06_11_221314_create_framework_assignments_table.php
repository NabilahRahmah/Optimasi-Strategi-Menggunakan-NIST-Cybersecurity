<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('framework_assignments', function (Blueprint $table) {
            $table->id('assignment_id');
            $table->unsignedBigInteger('framework_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Satu user hanya bisa di-assign sekali per framework
            $table->unique(['framework_id', 'user_id']);

            $table->foreign('framework_id')
                ->references('framework_id')
                ->on('frameworks')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('framework_assignments');
    }
};