<?php

namespace App\Http\Controllers;

use App\Models\AssessmentJawaban;

class BuktiController extends Controller
{
    public function preview($jawaban_id, $index = 0)
    {
        $jawaban = AssessmentJawaban::with('assessment')->findOrFail($jawaban_id);
        $user = auth()->user();

        $boleh = match ($user->role) {
            'user'        => $jawaban->assessment->user_id === $user->user_id,
            'approver'    => true,
            'admin'       => true,
            'admin_super' => true,
            default       => false,
        };

        abort_if(!$boleh, 403, 'Akses ditolak.');

        $paths = $jawaban->file_bukti ?? [];
        $namas = $jawaban->nama_file_asli ?? [];

        if (empty($paths) || !isset($paths[$index])) {
            abort(404, 'File tidak ditemukan.');
        }

        $fullPath = storage_path('app/public/' . $paths[$index]);

        if (!file_exists($fullPath)) {
            abort(404, 'File tidak ada di storage.');
        }

        $namaFile = $namas[$index] ?? basename($paths[$index]);
        $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
        $mimeMap = [
            'pdf'  => 'application/pdf',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        return response()->file($fullPath, [
            'Content-Type'        => $mimeMap[$ext] ?? 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="' . $namaFile . '"',
        ]);
    }
}