<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Framework;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrameworkController extends Controller
{
    public function index()
    {
        $frameworks = Framework::withCount('domains')
            ->with('picUser')
            ->latest()
            ->paginate(10);

        return view('superadmin.frameworks.index', compact('frameworks'));
    }

    public function create()
    {
        $users = User::select('user_id', 'name', 'email')
            ->where('role', 'admin')
            ->orderBy('name')
            ->get();

        return view('superadmin.frameworks.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_framework' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'pic_user_id' => 'nullable|exists:users,user_id',
        ]);

        DB::beginTransaction();
        try {
            $framework = Framework::create([
                'name_framework' => $request->name_framework,
                'description' => $request->description,
                'pic_user_id' => $request->pic_user_id,
                'is_active' => true,
            ]);

            // Simpan domain baru
            if ($request->has('domains')) {
                foreach ($request->domains as $domain) {
                    if (!empty($domain['nama_domain'])) {
                        Domain::create([
                            'framework_id' => $framework->framework_id,
                            'kode_domain' => strtoupper(trim($domain['kode_domain'] ?? '')),
                            'nama_domain' => $domain['nama_domain'],
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('superadmin.frameworks.index')
                ->with('success', 'Framework berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    public function domains($id)
    {
        $framework = Framework::with('domains')->findOrFail($id);
        return response()->json($framework->domains);
    }

    public function storeDomain(Request $request, $id)
    {
        $request->validate([
            'kode_domain' => 'required|string|max:20',
            'nama_domain' => 'required|string|max:255',
        ]);

        $framework = Framework::findOrFail($id);

        $domain = Domain::create([
            'framework_id' => $framework->framework_id,
            'kode_domain' => strtoupper(trim($request->kode_domain)),
            'nama_domain' => $request->nama_domain,
        ]);

        return response()->json(['success' => true, 'domain' => $domain]);
    }

    public function destroyDomain($id, $domainId)
    {
        $domain = Domain::where('domain_id', $domainId)
            ->where('framework_id', $id)
            ->firstOrFail();

        $domain->delete();

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $framework = Framework::with('domains')->findOrFail($id);
        $users = User::select('user_id', 'name', 'email')
        ->where('role', 'admin')
        ->orderBy('name')
        ->get();

        return view('superadmin.frameworks.edit', compact('framework', 'users'));
    }

    public function update(Request $request, $id)
    {
        $framework = Framework::with('domains')->findOrFail($id);

        $request->validate([
            'name_framework' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'required|boolean',
            'pic_user_id' => 'nullable|exists:users,user_id',
        ]);

        DB::beginTransaction();
        try {
            // Update data framework
            $framework->update([
                'name_framework' => $request->name_framework,
                'description' => $request->description,
                'is_active' => $request->is_active,
                'pic_user_id' => $request->pic_user_id,
            ]);

            // 1. Hapus domain yang di-klik X oleh user
            if ($request->filled('deleted_domain_ids')) {
                Domain::whereIn('domain_id', explode(',', $request->deleted_domain_ids))
                    ->where('framework_id', $framework->framework_id)
                    ->delete();
            }

            // 2. Update domain existing (yang namanya diedit)
            if ($request->has('existing_domains')) {
                foreach ($request->existing_domains as $domainId => $domainData) {
                    if (empty($domainData['nama_domain']))
                        continue;
                    Domain::where('domain_id', $domainId)
                        ->where('framework_id', $framework->framework_id)
                        ->update([
                            'kode_domain' => strtoupper(trim($domainData['kode_domain'] ?? '')),
                            'nama_domain' => $domainData['nama_domain'],
                        ]);
                }
            }

            // 3. Insert domain baru (yang ditambah via tombol + Domain)
            if ($request->has('new_domains')) {
                foreach ($request->new_domains as $domainData) {
                    if (empty($domainData['nama_domain']))
                        continue;
                    Domain::create([
                        'framework_id' => $framework->framework_id,
                        'kode_domain' => strtoupper(trim($domainData['kode_domain'] ?? '')),
                        'nama_domain' => $domainData['nama_domain'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('superadmin.frameworks.index')
                ->with('success', 'Framework berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $framework = Framework::findOrFail($id);

        if ($framework->domains()->count() > 0) {
            return back()->with('error', 'Tidak bisa menghapus framework yang masih memiliki domain!');
        }

        $framework->delete();
        return redirect()->route('superadmin.frameworks.index')
            ->with('success', 'Framework berhasil dihapus!');
    }
}