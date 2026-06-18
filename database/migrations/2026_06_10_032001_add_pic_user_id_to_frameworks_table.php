<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('frameworks', function (Blueprint $table) {
            $table->unsignedBigInteger('pic_user_id')->nullable()->after('description');
            $table->foreign('pic_user_id')->references('user_id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('frameworks', function (Blueprint $table) {
            $table->dropForeign(['pic_user_id']);
            $table->dropColumn('pic_user_id');
        });
    }
};
