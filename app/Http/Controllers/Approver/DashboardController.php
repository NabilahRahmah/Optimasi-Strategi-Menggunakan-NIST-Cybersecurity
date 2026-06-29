<?php

namespace App\Http\Controllers\Approver;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Rekomendasi;
use App\Models\FrameworkAssignment;

class DashboardController extends Controller
{
    public function index()
    {
        $approverId = auth()->user()->user_id;

        $frameworkIds = FrameworkAssignment::where('user_id', $approverId)
            ->pluck('framework_id');

        $myAssessments = Assessment::whereIn('framework_id', $frameworkIds);

        return view('approver.dashboard', [
            'queue_review' => (clone $myAssessments)->where('status', 'submitted')->count(),
            'perlu_keputusan' => (clone $myAssessments)->where('status', 'submitted')->count(),
            'disetujui' => (clone $myAssessments)->where('status', 'disetujui')->count(),
            'rekomendasi_terbuka' => Rekomendasi::whereHas(
                'assessment',
                fn($q) =>
                $q->whereIn('framework_id', $frameworkIds)
            )->count(),
            'pending_assessments' => (clone $myAssessments)
                ->with(['framework'])
                ->where('status', 'submitted')
                ->latest()->take(5)->get(),
        ]);
    }
}