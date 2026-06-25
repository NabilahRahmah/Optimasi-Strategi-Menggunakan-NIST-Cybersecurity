<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Hasil;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Assessment yang ada jawaban ditolak (perlu revisi)
        $revisi_terbuka = Assessment::where('user_id', $userId)
            ->whereIn('status', ['in_review', 'disetujui'])
            ->whereHas('jawabans', fn($q) => $q->where('status_verifikasi', 'ditolak'))
            ->count();

        // Skor rata-rata dari hasil yang sudah disetujui
        $skor_rata = Hasil::whereHas('assessment', fn($q) =>
            $q->where('user_id', $userId)->where('status', 'disetujui')
        )->avg('nilai_kematangan') ?? 0;

        return view('user.dashboard', [
            'total_assessment'    => Assessment::where('user_id', $userId)->count(),
            'menunggu_verifikasi' => Assessment::where('user_id', $userId)
                                        ->whereIn('status', ['submitted', 'in_review'])
                                        ->count(),
            'skor_rata'           => round($skor_rata, 2),
            'revisi_terbuka'      => $revisi_terbuka,
            'my_assessments'      => Assessment::with(['framework'])
                                        ->where('user_id', $userId)
                                        ->latest()->take(5)->get(),
        ]);
    }
}