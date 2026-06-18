<?php
namespace App\Http\Controllers\Approver;
 
use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Rekomendasi;
use App\Models\Verifikasi;
 
class DashboardController extends Controller
{
    public function index()
    {
        return view('approver.dashboard', [
            'queue_review'       => Assessment::where('status', 'submitted')->count(),
            'perlu_keputusan'    => Assessment::where('status', 'submitted')->count(),
            'disetujui'          => Assessment::where('status', 'verified')->orWhere('status', 'completed')->count(),
            'rekomendasi_terbuka'=> Rekomendasi::count(),
            'pending_assessments'=> Assessment::with(['user', 'framework'])
                                        ->where('status', 'submitted')
                                        ->latest()->take(5)->get(),
        ]);
    }
}