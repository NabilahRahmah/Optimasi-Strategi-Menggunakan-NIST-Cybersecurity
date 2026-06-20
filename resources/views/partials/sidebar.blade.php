@php
    $user        = auth()->user();
    $role        = $user->role ?? 'user';
    $currentRoute = request()->route()->getName();
 
    $navGroups = match($role) {
        'admin_super' => [
            [
                'section' => 'Master Data',
                'items'   => [
                    ['label' => 'Dashboard',       'route' => 'superadmin.dashboard','mark' => 'DB', 'badge' => null],
                    ['label' => 'Kelola Karyawan', 'route' => 'users.index',        'mark' => 'KR', 'badge' => null],
                    ['label' => 'Kelola Framework','route' => 'framework.index',   'mark' => 'FW', 'badge' => null],
                ],
            ],
        ],
        'admin' => [
            [
                'section' => 'Konfigurasi',
                'items'   => [
                    ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'mark' => 'DB', 'badge' => null],
                    ['label' => 'Assessment','route' => 'assessment.index', 'mark' => 'CK', 'badge' => null],
                ],
            ],
        ],
        'approver' => [
            [
                'section' => 'Verifikasi',
                'items'   => [
                    ['label' => 'Dashboard',        'route' => 'approver.dashboard',          'mark' => 'DB', 'badge' => null],
                    ['label' => 'Queue Verifikasi', 'route' => 'approver.verifikasi.index',   'mark' => 'VF', 'badge' => null],
                    ['label' => 'Rekomendasi',      'route' => 'approver.rekomendasi.index',  'mark' => 'RK', 'badge' => null],
                    ['label' => 'Hasil Assessment', 'route' => 'approver.hasil.index',        'mark' => 'HL', 'badge' => null],
                ],
            ],
        ],
        default => [ // user
            [
                'section' => 'Self Assessment',
                'items'   => [
                    ['label' => 'Dashboard',      'route' => 'user.dashboard',        'mark' => 'DB', 'badge' => null],
                    ['label' => 'Self Assessment','route' => 'assessment.create',     'mark' => 'SA', 'badge' => null], 
                    ['label' => 'Hasil Saya',     'route' => 'user.hasil.index',      'mark' => 'HS', 'badge' => null],
                ],
            ],
        ],
    };
@endphp
 
<aside class="sidebar-container fixed inset-y-0 left-0 z-50 w-72 flex-col border-r bg-card shadow-sm transition-transform duration-300 -translate-x-full lg:sticky lg:top-0 lg:flex lg:h-screen lg:translate-x-0">
 
    {{-- Logo --}}
    <div class="flex h-16 items-center border-b px-6">
        <div class="flex items-center gap-2 font-bold tracking-tight">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary text-primary-foreground shadow-sm text-sm font-bold">PA</div>
            <span class="text-foreground font-serif text-lg">PraAudit</span>
        </div>
    </div>
 
    {{-- Menu navigasi --}}
    <div class="flex-1 overflow-y-auto px-4 py-6">
        <nav class="space-y-6">
            @foreach ($navGroups as $group)
                <div class="space-y-1.5">
                    <h4 class="px-2 text-[10px] font-bold uppercase tracking-wider text-muted-foreground">
                        {{ $group['section'] }}
                    </h4>
                    <div class="grid gap-1">
                        @foreach ($group['items'] as $item)
                            @php
                                $isActive     = $currentRoute === $item['route'];
                                $routeExists  = \Illuminate\Support\Facades\Route::has($item['route']);
                            @endphp
 
                            @if ($routeExists)
                                <a href="{{ route($item['route']) }}"
                                    class="group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors
                                        {{ $isActive
                                            ? 'bg-accent text-accent-foreground shadow-sm'
                                            : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground' }}">
                                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-sm border text-[10px] font-bold
                                        {{ $isActive
                                            ? 'border-primary/20 bg-primary/10 text-primary'
                                            : 'border-input bg-background group-hover:border-accent-foreground/20' }}">
                                        {{ $item['mark'] }}
                                    </span>
                                    <span class="flex-1 truncate">{{ $item['label'] }}</span>
                                    @if ($item['badge'])
                                        <span class="inline-flex items-center rounded-full px-1.5 py-0.5 text-[10px] font-bold bg-primary text-primary-foreground shadow-sm">
                                            {{ $item['badge'] }}
                                        </span>
                                    @endif
                                </a>
                            @else
                                {{-- Route belum ada = menu disabled (abu-abu) --}}
                                <span class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium text-muted-foreground/40 cursor-not-allowed">
                                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-sm border text-[10px] font-bold border-input bg-background opacity-40">
                                        {{ $item['mark'] }}
                                    </span>
                                    <span class="flex-1 truncate">{{ $item['label'] }}</span>
                                </span>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </nav>
    </div>
 
    {{-- Info user + tombol logout --}}
    <div class="border-t p-4">
        <div class="mb-4 flex items-center gap-3 rounded-lg border bg-accent/50 p-3 shadow-sm">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary text-[10px] font-bold text-primary-foreground shadow-sm">
                {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="truncate text-xs font-bold text-foreground leading-none mb-1">{{ $user->name ?? 'User' }}</p>
                <p class="truncate text-[10px] text-muted-foreground leading-none">{{ $user->email ?? '' }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="inline-flex w-full items-center justify-center gap-2 rounded-md border border-input bg-background px-4 py-2 text-sm font-medium text-foreground transition-colors hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-muted-foreground"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Logout
            </button>
        </form>
    </div>
 
</aside>