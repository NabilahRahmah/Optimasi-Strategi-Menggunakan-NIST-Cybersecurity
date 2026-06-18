<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('frameworks', function (Blueprint $table) {
            $table->id('framework_id');
            $table->string('name_framework');
            $table->string('versi')->nullable();      // tambahan
            $table->text('description');
            $table->boolean('is_active')->default(true); // tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frameworks');
    }
};
