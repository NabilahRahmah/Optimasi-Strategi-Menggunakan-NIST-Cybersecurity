<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Framework;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('superadmin.dashboard', [
            'total_user'       => User::count(),
            'total_framework'  => Framework::count(),
            'total_assessment' => Assessment::count(),
        ]);
    }
}