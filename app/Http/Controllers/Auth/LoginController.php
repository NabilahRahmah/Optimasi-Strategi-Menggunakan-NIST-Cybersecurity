<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth; // TAMBAHKAN INI AGAR TIDAK ERROR

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * HAPUS properti $redirectTo = '/home'; 
     * GANTI dengan fungsi redirectTo() di bawah ini:
     */
    protected function redirectTo()
    {
        $role = Auth::user()->role->role_name;

        return match($role) {
            'Super Admin' => '/superadmin/dashboard',
            'Admin'       => '/admin/dashboard',
            'Approver'    => '/approver/dashboard',
            default       => '/user/dashboard',
        };
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */

}