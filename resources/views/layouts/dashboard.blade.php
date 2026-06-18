<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'CyberAudit Pro' }} | Sistem Informasi Pra-Audit</title>
 
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
 
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .material-symbols-filled   { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { background-color: #F5F5F5; }
    </style>
 
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#af101a",
                        "primary-container": "#d32f2f",
                        "on-surface": "#271816",
                        tertiary: "#005f7b",
                    },
                    fontFamily: {
                        body: ["Inter", "sans-serif"],
                        sans: ["Inter", "sans-serif"],
                    }
                }
            }
        }
    </script>
 
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body text-on-surface antialiased min-h-screen flex">
 
    <div class="fixed inset-0 z-40 bg-slate-900/80 backdrop-blur-sm transition-all duration-300 pointer-events-none opacity-0 lg:hidden" id="sidebarScrim"></div>
 
    {{-- ═══════════════════════════════════════════
         SIDEBAR DINAMIS PER ROLE
         Analogi: Papan menu restoran yang berbeda
         isi menunya tergantung siapa tamunya
    ════════════════════════════════════════════ --}}
    @php
        $user         = auth()->user();
        $role         = $user->role ?? 'user';
        $currentRoute = request()->route()?->getName();
 
        // Konfigurasi menu per role
        // Analogi: Setiap lantai punya daftar ruangan berbeda
        $navItems = match($role) {
            'admin_super' => [
                ['label' => 'Dashboard',        'route' => 'superadmin.dashboard',        'icon' => 'dashboard'],
                ['label' => 'Kelola Karyawan',  'route' => 'superadmin.users.index',      'icon' => 'group'],
                ['label' => 'Kelola Framework', 'route' => 'superadmin.frameworks.index',  'icon' => 'inventory_2'],
            ],
            'admin' => [
                ['label' => 'Dashboard',         'route' => 'admin.dashboard',       'icon' => 'dashboard'],
                ['label' => 'Assessment',        'route' => 'admin.assessment.index',  'icon' => 'fact_check'],
                ['label' => 'Dokumen Pendukung', 'route' => 'admin.dokpendukung.index',  'icon' => 'folder_open'],
            ],
            'approver' => [
                ['label' => 'Dashboard',        'route' => 'approver.dashboard',         'icon' => 'dashboard'],
                ['label' => 'Queue Verifikasi', 'route' => 'approver.verifikasi.index',  'icon' => 'rate_review'],
                ['label' => 'Rekomendasi',      'route' => 'approver.rekomendasi.index', 'icon' => 'lightbulb'],
                ['label' => 'Hasil Assessment', 'route' => 'approver.hasil.index',       'icon' => 'bar_chart'],
            ],
            default => [
                ['label' => 'Dashboard',         'route' => 'user.dashboard',        'icon' => 'dashboard'],
                ['label' => 'Self Assessment',   'route' => 'assessment.create',     'icon' => 'fact_check'],
                ['label' => 'Dokumen Pendukung', 'route' => 'user.dokpendukung.index',    'icon' => 'folder_open'],
                ['label' => 'Hasil Assessment',  'route' => 'user.hasil.index',      'icon' => 'bar_chart'],
            ],
        };
 
        // Label portal per role
        $portalLabel = match($role) {
            'admin_super' => 'Super Admin',
            'admin'       => 'Admin Portal',
            'approver'    => 'Approver Portal',
            default       => 'User Portal',
        };
    @endphp
 
    <aside id="mainSidebar"
        class="fixed left-0 top-0 h-full w-[260px] bg-slate-950 border-r border-slate-800 shadow-xl flex flex-col py-6 z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
 
        {{-- Logo --}}
        <div class="px-6 mb-8 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-container rounded flex items-center justify-center">
                    <span class="material-symbols-outlined material-symbols-filled text-white">security</span>
                </div>
                <div>
                    <h2 class="text-lg font-black text-white leading-none">CyberAudit</h2>
                    <p class="text-[10px] uppercase tracking-widest text-slate-400 mt-1">{{ $portalLabel }}</p>
                </div>
            </div>
            <button class="lg:hidden text-slate-400 hover:text-white" id="closeSidebar">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <nav class="flex-1 space-y-1 px-2 mt-4">
            @foreach($navItems as $item)
                @php
                    $isActive    = $currentRoute === $item['route'];
                    $routeExists = \Illuminate\Support\Facades\Route::has($item['route']);
                @endphp

                @if($routeExists)
                    {{-- Tombol Normal / Aktif --}}
                    <a href="{{ route($item['route']) }}"
                        class="flex items-center gap-3 px-4 py-3 mx-2 rounded-md transition-all text-sm font-semibold tracking-wide
                            {{ $isActive
                                ? 'bg-red-700 text-white shadow-lg'
                                : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        <span class="material-symbols-outlined {{ $isActive ? 'material-symbols-filled' : '' }} text-xl">
                            {{ $item['icon'] }}
                        </span>
                        {{ $item['label'] }}
                    </a>
                @else
                    {{-- Tombol Pingsan (Rute belum dibuat di web.php) --}}
                    <span class="flex items-center gap-3 px-4 py-3 mx-2 rounded-md text-sm font-semibold tracking-wide text-slate-500 cursor-not-allowed opacity-40 select-none bg-slate-900/30 border border-dashed border-slate-800">
                        <span class="material-symbols-outlined text-xl">{{ $item['icon'] }}</span>
                        {{ $item['label'] }}
                        <span class="ml-auto text-[8px] uppercase tracking-widest text-slate-600">Dev Only</span>
                    </span>
                @endif
            @endforeach
        </nav>
 
        {{-- Tombol Logout --}}
        <div class="px-4 mt-6 border-t border-slate-800 pt-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex w-full items-center gap-3 px-4 py-3 rounded-md text-sm font-semibold tracking-wide text-slate-400 hover:text-white hover:bg-slate-800 transition-all">
                    <span class="material-symbols-outlined text-xl">logout</span>
                    Log out
                </button>
            </form>
        </div>
    </aside>
 
    {{-- KONTEN UTAMA --}}
    <div class="flex flex-1 flex-col transition-all duration-300 lg:ml-[260px] w-full">
 
        {{-- TOP NAVBAR --}}
        <header class="sticky top-0 z-30 flex h-16 w-full items-center justify-between border-b border-gray-200 bg-white/95 px-4 lg:px-8 shadow-sm backdrop-blur">
            <div class="flex items-center gap-4">
                <button type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-gray-200 bg-white text-gray-500 hover:bg-gray-100 lg:hidden"
                    id="sidebarToggle">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <div class="hidden md:flex items-center bg-gray-100 rounded-full px-4 py-1.5 gap-2">
                    <span class="material-symbols-outlined text-gray-500 text-sm">search</span>
                    <input class="bg-transparent border-none focus:ring-0 text-sm w-48 font-medium outline-none"
                        placeholder="Cari kriteria audit..." type="text"/>
                </div>
            </div>
 
            <div class="flex items-center gap-4">
                <button class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors text-gray-500">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <div class="h-6 w-px bg-gray-200"></div>
 
                {{-- Profile Dropdown --}}
                <div class="relative dropdown group" id="profileDropdown">
                    <button class="flex items-center gap-3 rounded-full hover:bg-gray-50 p-1 pr-2 transition-colors focus:outline-none" data-dropdown-toggle>
                        <div class="hidden text-right lg:block">
                            <p class="text-xs font-bold leading-none text-slate-800">{{ auth()->user()->name ?? 'User' }}</p>
                            <p class="mt-1 text-[10px] font-medium leading-none text-gray-500 uppercase">{{ auth()->user()->role ?? '-' }}</p>
                        </div>
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border-2 border-red-200 bg-slate-100 text-xs font-bold text-slate-600 shadow-sm">
                            <span class="material-symbols-outlined text-slate-400">person</span>
                        </div>
                    </button>
                    <div class="dropdown-menu invisible absolute right-0 top-full z-50 mt-2 w-56 origin-top-right rounded-xl border border-gray-100 bg-white p-2 shadow-lg transition-all scale-95 opacity-0 group-[.is-open]:visible group-[.is-open]:scale-100 group-[.is-open]:opacity-100">
                        <div class="px-2 py-2 border-b border-gray-100 mb-1">
                            <p class="text-sm font-bold text-slate-800">{{ auth()->user()->name ?? 'User' }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email ?? '' }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-primary font-medium transition-colors">
                            <span class="material-symbols-outlined text-lg">manage_accounts</span>
                            My Profile
                        </a>
                        <div class="border-t border-gray-100 mt-1 p-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex w-full items-center gap-2 rounded-lg px-2 py-2 text-sm font-bold text-red-600 hover:bg-red-50 transition-colors">
                                    <span class="material-symbols-outlined text-lg">logout</span>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
 
        {{-- AREA KONTEN UTAMA --}}
        <main class="flex-1 p-4 lg:p-8 overflow-y-auto">
            @yield('content')
        </main>
    </div>
 
    <script>
        document.addEventListener('click', function(e) {
            const sidebarToggle = e.target.closest('#sidebarToggle');
            const closeSidebar  = e.target.closest('#closeSidebar');
            const sidebarScrim  = e.target.closest('#sidebarScrim');
            const sidebar       = document.getElementById('mainSidebar');
            const scrim         = document.getElementById('sidebarScrim');
 
            if (sidebarToggle || closeSidebar || sidebarScrim) {
                if (sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.remove('-translate-x-full');
                    scrim.classList.remove('pointer-events-none', 'opacity-0');
                    scrim.classList.add('opacity-100');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    scrim.classList.add('pointer-events-none', 'opacity-0');
                    scrim.classList.remove('opacity-100');
                }
            }
 
            const toggle = e.target.closest('[data-dropdown-toggle]');
            if (toggle) {
                const dropdown = toggle.closest('.dropdown');
                document.querySelectorAll('.dropdown.is-open').forEach(d => {
                    if (d !== dropdown) d.classList.remove('is-open');
                });
                dropdown.classList.toggle('is-open');
            } else if (!e.target.closest('.dropdown-menu')) {
                document.querySelectorAll('.dropdown.is-open').forEach(d => d.classList.remove('is-open'));
            }
        });
    </script>

    @stack('scripts')
</body>
</html>