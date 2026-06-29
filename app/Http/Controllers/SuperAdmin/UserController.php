<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users',
            'nik' => 'nullable|string|max:50',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:admin_super,admin,approver,user',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username ?? \Str::slug($request->name),
            'nik' => $request->nik,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Karyawan baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // Cegah superadmin edit dirinya sendiri dari sini
        // (ada halaman profile sendiri untuk itu)
        $user = User::where('user_id', $id)
            ->where('user_id', '!=', auth()->user()->user_id)
            ->firstOrFail();

        return view('superadmin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        // Cegah update user selain yang valid & bukan diri sendiri
        $user = User::where('user_id', $id)
            ->where('user_id', '!=', auth()->user()->user_id)
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($user->user_id, 'user_id')],
            'nik' => 'nullable|string|max:50',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->user_id, 'user_id')],
            'role' => 'required|in:admin_super,admin,approver,user',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username ?? $user->username,
            'nik' => $request->nik,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Hanya update password kalau diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Cegah hapus diri sendiri
        $user = User::where('user_id', $id)
            ->where('user_id', '!=', auth()->user()->user_id)
            ->firstOrFail();

        $user->delete();

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Akun karyawan berhasil dihapus!');
    }
}