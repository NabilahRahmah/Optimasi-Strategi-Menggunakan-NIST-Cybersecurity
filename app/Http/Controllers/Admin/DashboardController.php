<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Framework;
use App\Models\Kategori;
use App\Models\Pertanyaan;
use App\Models\FrameworkAssignment;

class DashboardController extends Controller
{
    public function index()
    {
        $adminId = auth()->user()->user_id;

        $frameworkIds = FrameworkAssignment::where('user_id', $adminId)
            ->pluck('framework_id');

        $frameworks = Framework::whereIn('framework_id', $frameworkIds)->get();

        $domainIds = Domain::whereIn('framework_id', $frameworkIds)->pluck('domain_id');

        return view('admin.dashboard', [
            'total_framework' => $frameworks->count(),
            'total_domain' => Domain::whereIn('framework_id', $frameworkIds)->count(),
            'total_kategori' => Kategori::whereIn('domain_id', $domainIds)->count(),
            'total_pertanyaan' => Pertanyaan::whereIn(
                'kategori_id',
                Kategori::whereIn('domain_id', $domainIds)->pluck('kategori_id')
            )->count(),
        ]);
    }
}