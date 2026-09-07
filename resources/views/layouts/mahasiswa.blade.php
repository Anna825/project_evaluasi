<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            --mhs: #2e7d32;
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
    $roleColor = '#2e7d32';

    $icons = [
        'dashboard' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
        'award' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z',
        'report' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        'user' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
    ];

    $navItems = [
        ['route' => 'public.mahasiswa.menu', 'pattern' => 'public.mahasiswa.menu', 'label' => 'Ringkasan', 'icon' => $icons['dashboard']],
        ['route' => 'public.prestasi.index', 'pattern' => 'public.prestasi.*', 'label' => 'Prestasi Saya', 'icon' => $icons['award']],
        ['route' => 'public.tracer.create', 'pattern' => 'public.tracer.*', 'label' => 'Tracer Study', 'icon' => $icons['report']],
    ];
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
                            {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-white text-sm font-semibold truncate leading-tight">{{ $mahasiswa->nama }}</p>
                            <p class="text-xs opacity-60 text-white">Mahasiswa</p>
                        </div>
                    </div>
                    <p class="text-xs opacity-50 text-white mt-2 mono">NIM: {{ $mahasiswa->nim }}</p>
                </div>

                <nav class="px-3 mt-3 flex-1 overflow-y-auto">
                    <p class="text-xs uppercase tracking-widest mb-2 px-2" style="color: rgba(255,255,255,0.35);">Menu Utama</p>
                    <ul class="space-y-0.5">
                        @foreach ($navItems as $item)
                            <li>
                                <a href="{{ route($item['route'], $mahasiswa->nim) }}"
                                    class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs($item['pattern']) ? 'active' : '' }}"
                                    style="color: {{ request()->routeIs($item['pattern']) ? '#fff' : 'rgba(255,255,255,0.65)' }}; border-left: 3px solid {{ request()->routeIs($item['pattern']) ? $roleColor : 'transparent' }};">
                                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" class="shrink-0">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                                    </svg>
                                    <span class="truncate">{{ $item['label'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <p class="text-xs uppercase tracking-widest mb-2 px-2 mt-6" style="color: rgba(255,255,255,0.35);">Akun</p>
                    <ul class="space-y-0.5">
                        <li>
                            <a href="{{ route('public.profil', $mahasiswa->nim) }}"
                                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('public.profil') ? 'active' : '' }}"
                                style="color: {{ request()->routeIs('public.profil') ? '#fff' : 'rgba(255,255,255,0.65)' }}; border-left: 3px solid {{ request()->routeIs('public.profil') ? $roleColor : 'transparent' }};">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" class="shrink-0">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['user'] }}" />
                                </svg>
                                <span class="truncate">Profil Saya</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                <div class="p-4">
                    <a href="{{ route('public.cek-nim') }}" class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-medium hover:opacity-90 block text-center" style="background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.8);">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Keluar
                    </a>
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
                <a href="{{ route('public.profil', $mahasiswa->nim) }}" class="flex items-center gap-3 hover:opacity-80 transition cursor-pointer group" title="Lihat Profil Saya">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold group-hover:underline">{{ $mahasiswa->nama }}</p>
                        <p class="text-xs" style="color: var(--muted-foreground);">Mahasiswa</p>
                    </div>
                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm text-white transition transform group-hover:scale-105" style="background: var(--primary);">
                        {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
                    </div>
                </a>
            </div>

            <div class="p-6">
                @if (session('status'))
                    <div class="mb-6 px-4 py-3 rounded-xl border-l-4 text-sm" style="background: var(--card); border-color: #2e7d32;">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 px-4 py-3 rounded-xl border-l-4 text-sm" style="background: var(--card); border-color: #c62828; color: #c62828;">
                        {{ session('error') }}
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