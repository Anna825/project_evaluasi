<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Evaluasi PBM')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,wght@0,400;0,600;0,700;1,400&family=Outfit:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --background: #f0f2f7;
            --foreground: #0f1c2e;
            --card: #ffffff;
            --primary: #1a3a5c;
            --secondary: #e8edf5;
            --muted: #dde3ee;
            --muted-foreground: #5a6e8a;
            --accent: #b8952a;
            --border: #c8d3e6;
        }
        * { font-family: 'Outfit', sans-serif; }
        h1, h2, h3, .font-serif-display { font-family: 'Fraunces', serif; }
        .mono { font-family: 'JetBrains Mono', monospace; }
        .sidebar-link { transition: all .2s ease; }
        .sidebar-link:hover { background-color: rgba(255,255,255,0.12); }
        .sidebar-link.active { background-color: rgba(255,255,255,0.18); }
        .card-hover { transition: box-shadow .2s ease, transform .2s ease; }
        .card-hover:hover { box-shadow: 0 8px 24px rgba(26,58,92,0.12); transform: translateY(-2px); }
        input:focus, select:focus, textarea:focus { outline: none; box-shadow: 0 0 0 2px var(--primary); }
        #appSidebar { transition: width .25s ease; }
        #appSidebar.sidebar-collapsed { width: 0 !important; }
        #appSidebar .sidebar-inner { width: 260px; }
    </style>
</head>
<body style="background-color: var(--background); color: var(--foreground);">
@php
    $roleNames = auth()->user()->roles()->pluck('nama_role')->toArray();
    $activeRole = in_array('admin', $roleNames) ? 'admin' : (in_array('kaprodi', $roleNames) ? 'kaprodi' : 'dosen');

    $pendingDosenCount = $activeRole === 'admin'? \App\Models\User::whereHas('dosen')->where('status', 'pending')->count(): 0;

    $roleLabels = ['admin' => 'Administrator', 'kaprodi' => 'Ka. Program Studi', 'dosen' => 'Dosen'];
    $roleColors = ['admin' => '#b8952a', 'kaprodi' => '#6a1b9a', 'dosen' => '#1565c0'];

    $icons = [
        'dashboard' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
        'people' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
        'building' => 'M3 21h18M4 21V8l8-5 8 5v13M9 21v-6h6v6M9 12h.01M15 12h.01M9 9h.01M15 9h.01',
        'calendar' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        'book' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
        'research' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',
        'report' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        'award' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z',
    ];

    $navByRole = [
        'admin' => [
            ['route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'label' => 'Ringkasan', 'icon' => $icons['dashboard']],
            ['route' => 'admin.users.index', 'pattern' => 'admin.users.*', 'label' => 'Akun Dosen', 'icon' => $icons['people']],
            ['route' => 'mahasiswa.index', 'pattern' => 'mahasiswa.*', 'label' => 'Data Mahasiswa', 'icon' => $icons['people']],
            ['route' => 'prodi.index', 'pattern' => 'prodi.*', 'label' => 'Program Studi', 'icon' => $icons['building']],
            ['route' => 'tahun-akademik.index', 'pattern' => 'tahun-akademik.*', 'label' => 'Tahun Akademik', 'icon' => $icons['calendar']],
            ['route' => 'kurikulum.index', 'pattern' => 'kurikulum.*', 'label' => 'Kurikulum & CPL', 'icon' => $icons['book']],
            ['route' => 'penelitian-pkm.verifikasi', 'pattern' => 'penelitian-pkm.verifikasi', 'label' => 'Verifikasi Penelitian', 'icon' => $icons['research']],
            ['route' => 'report.index', 'pattern' => 'report.*', 'label' => 'Pusat Laporan', 'icon' => $icons['report']],
        ],
        'kaprodi' => [
            ['route' => 'kaprodi.dashboard', 'pattern' => 'kaprodi.dashboard', 'label' => 'Ringkasan', 'icon' => $icons['dashboard']],
            ['route' => 'mahasiswa.index', 'pattern' => 'mahasiswa.*', 'label' => 'Data Mahasiswa', 'icon' => $icons['people']],
            ['route' => 'kurikulum.index', 'pattern' => 'kurikulum.*', 'label' => 'Kurikulum & CPL', 'icon' => $icons['book']],
            ['route' => 'prodi.index', 'pattern' => 'prodi.*', 'label' => 'Program Studi', 'icon' => $icons['building']],
            ['route' => 'tahun-akademik.index', 'pattern' => 'tahun-akademik.*', 'label' => 'Tahun Akademik', 'icon' => $icons['calendar']],
            ['route' => 'penelitian-pkm.verifikasi', 'pattern' => 'penelitian-pkm.verifikasi', 'label' => 'Verifikasi Penelitian', 'icon' => $icons['research']],
            ['route' => 'report.index', 'pattern' => 'report.*', 'label' => 'Pusat Laporan', 'icon' => $icons['report']],
        ],
        'dosen' => [
            ['route' => 'dosen.dashboard', 'pattern' => 'dosen.dashboard', 'label' => 'Ringkasan', 'icon' => $icons['dashboard']],
            ['route' => 'mata-kuliah.index', 'pattern' => 'mata-kuliah.*', 'label' => 'Mata Kuliah, CPMK & RPS', 'icon' => $icons['book']],
            ['route' => 'penelitian-pkm.index', 'pattern' => 'penelitian-pkm.index', 'label' => 'Penelitian & PKM', 'icon' => $icons['research']],
            ['route' => 'prestasi-dosen.index', 'pattern' => 'prestasi-dosen.*', 'label' => 'Prestasi Saya', 'icon' => $icons['award']],
        ],
    ];

    $navItems = $navByRole[$activeRole];
    $roleColor = $roleColors[$activeRole];
@endphp
    <div class="flex h-screen overflow-hidden">
        <aside id="appSidebar" class="flex flex-col shrink-0 overflow-hidden w-[260px]" style="background: var(--primary);">
            <div class="sidebar-inner flex flex-col h-full">
                <div class="px-5 py-5 border-b" style="border-color: rgba(255,255,255,0.12);">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background: var(--accent);">
                            <svg width="20" height="20" viewBox="0 0 44 44" fill="none">
                                <path d="M22 4L38 14v8H6v-8L22 4z" fill="white" opacity="0.9" />
                                <rect x="8" y="22" width="4" height="14" fill="white" opacity="0.85" />
                                <rect x="20" y="22" width="4" height="14" fill="white" opacity="0.85" />
                                <rect x="32" y="22" width="4" height="14" fill="white" opacity="0.85" />
                                <rect x="4" y="36" width="36" height="3" rx="1.5" fill="white" opacity="0.9" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-white text-xs opacity-60 leading-none mb-1">Polman Bandung</p>
                            <p class="text-white font-bold text-sm leading-none">SI EVALUASI PBM</p>
                        </div>
                    </div>
                </div>

                <div class="mx-4 mt-4 mb-2 rounded-xl p-3" style="background: rgba(255,255,255,0.08);">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm shrink-0 text-white" style="background: {{ $roleColor }};">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-white text-sm font-semibold truncate leading-tight">{{ auth()->user()->name }}</p>
                            <p class="text-xs opacity-60 text-white">{{ $roleLabels[$activeRole] }}</p>
                        </div>
                    </div>
                </div>

                <nav class="px-3 mt-3 flex-1 overflow-y-auto">
                    <p class="text-xs uppercase tracking-widest mb-2 px-2" style="color: rgba(255,255,255,0.35);">Menu Utama</p>
                    <ul class="space-y-0.5">
                        @foreach ($navItems as $item)
                            <li>
                                <a href="{{ route($item['route']) }}"
                                    class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs($item['pattern']) ? 'active' : '' }}"
                                    style="color: {{ request()->routeIs($item['pattern']) ? '#fff' : 'rgba(255,255,255,0.65)' }}; border-left: 3px solid {{ request()->routeIs($item['pattern']) ? $roleColor : 'transparent' }};">
                                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" class="shrink-0">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                                    </svg>
                                    <!-- <span class="truncate">{{ $item['label'] }}</span> -->
                                    <span class="truncate flex-1">{{ $item['label'] }}</span>

                                    @if ($item['route'] === 'admin.users.index' && $pendingDosenCount > 0)
                                        <span
                                            class="w-2.5 h-2.5 rounded-full bg-red-500 shrink-0"
                                            title="{{ $pendingDosenCount }} akun dosen menunggu verifikasi"
                                        ></span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <p class="text-xs uppercase tracking-widest mb-2 px-2 mt-6" style="color: rgba(255,255,255,0.35);">Akun</p>
                    <ul class="space-y-0.5">
                        <li>
                            <a href="{{ route('profile.show') }}"
                                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('profile.show') ? 'active' : '' }}"
                                style="color: {{ request()->routeIs('profile.show') ? '#fff' : 'rgba(255,255,255,0.65)' }}; border-left: 3px solid {{ request()->routeIs('profile.show') ? $roleColor : 'transparent' }};">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" class="shrink-0">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="truncate">Profil Saya</span>
                            </a>
                        </li>
                    </ul>

                </nav>

                <div class="p-4">
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-medium hover:opacity-90" style="background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.8);">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto">
            <div class="sticky top-0 z-10 flex items-center gap-4 px-6 py-4 border-b" style="background: var(--card); border-color: var(--border);">
                <button id="sidebarToggle" type="button" class="p-2 rounded-lg hover:opacity-70" style="color: var(--muted-foreground);">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="flex-1">
                    <p class="text-xs font-medium tracking-widest uppercase" style="color: var(--muted-foreground);">Sistem Informasi Evaluasi PBM</p>
                </div>
                <a href="{{ route('profile.show') }}" class="flex items-center gap-3 hover:opacity-80 transition cursor-pointer group" title="Lihat Profil Saya">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold group-hover:underline">{{ auth()->user()->name }}</p>
                        <p class="text-xs" style="color: var(--muted-foreground);">{{ $roleLabels[$activeRole] }}</p>
                    </div>
                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm text-white transition transform group-hover:scale-105" style="background: var(--primary);">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </a>
            </div>

            <div class="p-6">
                @if (session('status'))
                    <div class="mb-6 px-4 py-3 rounded-xl border-l-4 text-sm" style="background: var(--card); border-color: #2e7d32;">
                        {{ session('status') }}
                    </div>
                @endif

                <h1 class="font-serif-display text-2xl mb-1">@yield('page-title', 'Dashboard')</h1>
                <p class="text-sm mb-6" style="color: var(--muted-foreground);">@yield('page-desc', '')</p>

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.getElementById('appSidebar').classList.toggle('sidebar-collapsed');
        });
    </script>
</body>
</html>