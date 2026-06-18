<?php
 
namespace App\Http\Controllers\SuperAdmin;
 
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
 
class UserController extends Controller
{
    public function index()
    {
        $users = User::where('user_id', '!=', auth()->user()->user_id)
            ->latest()
            ->get();
 
        return view('superadmin.users.index', compact('users'));
    }
 
    public function create()
    {
        return view('superadmin.users.create');
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'username'              => 'nullable|string|max:255|unique:users',
            'nik'                   => 'nullable|string|max:50',
            'email'                 => 'required|string|email|max:255|unique:users',
            'role'                  => 'required|in:admin_super,admin,approver,user',
            'password'              => 'required|string|min:8|confirmed',
        ]);
 
        User::create([
            'name'     => $request->name,
            'username' => $request->username ?? \Str::slug($request->name),
            'nik'      => $request->nik,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password), 
        ]);
 
        return redirect()->route('superadmin.users.index')
            ->with('success', 'Karyawan baru berhasil ditambahkan!');
    }
 
    public function destroy($id)
    {
        User::where('user_id', $id)->firstOrFail()->delete();
 
        return redirect()->route('superadmin.users.index')
            ->with('success', 'Akun karyawan berhasil dihapus!');
    }
}