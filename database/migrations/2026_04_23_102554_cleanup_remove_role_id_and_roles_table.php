<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign key dulu sebelum drop kolom
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });

        // Drop tabel roles karena tidak dipakai
        Schema::dropIfExists('roles');

        // Pastikan kolom role enum sudah ada
        // Kalau belum ada, tambahkan
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', [
                    'admin_super',
                    'admin',
                    'user',
                    'approver',
                ])->default('user')->after('username');
            });
        }
    }

    public function down(): void
    {
        // Kembalikan kalau rollback
        Schema::create('roles', function (Blueprint $table) {
            $table->id('role_id');
            $table->string('role_name');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->after('username');
            $table->foreign('role_id')->references('role_id')->on('roles');
            $table->dropColumn('role');
        });
    }
};