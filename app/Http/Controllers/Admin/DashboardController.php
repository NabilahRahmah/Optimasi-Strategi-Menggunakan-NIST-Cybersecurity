<?php
 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Framework;
use App\Models\Kategori;
use App\Models\Pertanyaan;
 
class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'total_domain'    => Domain::count(),
            'total_pertanyaan' => Pertanyaan::count(), 
            'total_framework' => Framework::count(),
            'total_kategori'  => Kategori::count(),
            'domains'         => Domain::withCount('kategoris')->get(),
        ]);
    }
}