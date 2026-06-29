<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Hasil;
use App\Models\FrameworkAssignment;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->user_id;

        $frameworkIds = FrameworkAssignment::where('user_id', $userId)
            ->pluck('framework_id');

        $myAssessments = Assessment::whereIn('framework_id', $frameworkIds);

        $revisi_terbuka = (clone $myAssessments)
            ->whereIn('status', ['in_review', 'disetujui'])
            ->whereHas('jawabans', fn($q) => $q->where('status_verifikasi', 'ditolak'))
            ->count();

        $skor_rata = Hasil::whereHas(
            'assessment',
            fn($q) =>
            $q->whereIn('framework_id', $frameworkIds)->where('status', 'disetujui')
        )->avg('nilai_kematangan') ?? 0;

        return view('user.dashboard', [
            'total_assessment' => (clone $myAssessments)->count(),
            'menunggu_verifikasi' => (clone $myAssessments)
                ->whereIn('status', ['submitted', 'in_review'])
                ->count(),
            'skor_rata' => round($skor_rata, 2),
            'revisi_terbuka' => $revisi_terbuka,
            'my_assessments' => (clone $myAssessments)
                ->with(['framework'])
                ->latest()->take(5)->get(),
        ]);
    }
}