<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Schema::table('dokumen_pendukungs', function (Blueprint $table) {
        //     $table->enum('status', ['reviewing', 'disetujui', 'rejected'])
        //         ->default('reviewing')
        //         ->after('file_path');
        //     $table->text('catatan_approver')->nullable()->after('status');
        //     $table->unsignedBigInteger('disetujui_by')->nullable()->after('catatan_approver');
        //     $table->timestamp('disetujui_at')->nullable()->after('disetujui_by');
        // });
    }

    public function down(): void
    {
        // Schema::table('dokumen_pendukungs', function (Blueprint $table) {
        //     $table->dropColumn(['status', 'catatan_approver', 'disetujui_by', 'disetujui_at']);
        // });
    }
};
