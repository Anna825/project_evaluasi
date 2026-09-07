<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Mahasiswa - Evaluasi PBM LAM Teknik</title>
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
            --primary-dark: #0f1c2e;
            --secondary: #e8edf5;
            --muted: #dde3ee;
            --muted-foreground: #5a6e8a;
            --accent: #b8952a;
            --border: #c8d3e6;
        }
        * { font-family: 'Outfit', sans-serif; }
        h1, h2, .font-serif-display { font-family: 'Fraunces', serif; }
        .mono { font-family: 'JetBrains Mono', monospace; }
        input:focus { outline: none; box-shadow: 0 0 0 2px var(--primary); border-color: var(--primary); }
        .btn-primary { background-color: var(--primary); transition: background-color .2s ease; }
        .btn-primary:hover { background-color: var(--primary-dark); }
    </style>
</head>
<body class="min-h-screen" style="background-color: var(--background); color: var(--foreground);">
@php
    $stats = $stats ?? [
        'mahasiswa' => \App\Models\Mahasiswa::count(),
        'dosen' => \App\Models\Dosen::count(),
        'akreditasi' => 'A',
    ];
@endphp
<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

    {{-- Panel kiri: branding --}}
    <div class="relative hidden lg:flex flex-col justify-between p-12 overflow-hidden"
         style="background: linear-gradient(160deg, var(--primary-dark), var(--primary));">

        {{-- dekorasi lingkaran --}}
        <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full" style="background: rgba(255,255,255,0.05);"></div>
        <div class="absolute -bottom-10 -right-10 w-64 h-64 rounded-full" style="background: rgba(255,255,255,0.05);"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-14">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background: var(--accent);">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8">
                        <path d="M3 21h18M4 21V8l8-5 8 5v13M9 21v-6h6v6M9 12h.01M15 12h.01M9 9h.01M15 9h.01" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="text-white leading-tight">
                    <p class="text-xs uppercase tracking-wider" style="color: rgba(255,255,255,0.65);">Politeknik Manufaktur Bandung</p>
                    <p class="font-semibold font-serif-display">Automation Engineering</p>
                </div>
            </div>

            <div class="border-l-4 pl-5 mb-8" style="border-color: var(--accent);">
                <h1 class="text-4xl font-bold text-white font-serif-display leading-tight">Sistem Informasi</h1>
                <h1 class="text-4xl font-bold font-serif-display leading-tight" style="color: var(--accent);">Akreditasi</h1>
                <p class="mt-4 text-sm max-w-sm" style="color: rgba(255,255,255,0.7);">
                    Platform terintegrasi untuk pengelolaan data akreditasi dan layanan kemahasiswaan
                </p>
            </div>

            <div class="grid grid-cols-3 gap-4 max-w-md">
                <div class="rounded-xl p-4 text-center" style="background: rgba(255,255,255,0.08);">
                    <p class="text-2xl font-bold text-white mono">{{ number_format($stats['mahasiswa']) }}</p>
                    <p class="text-xs mt-1" style="color: rgba(255,255,255,0.65);">Mahasiswa Aktif</p>
                </div>
                <div class="rounded-xl p-4 text-center" style="background: rgba(255,255,255,0.08);">
                    <p class="text-2xl font-bold text-white mono">{{ number_format($stats['dosen']) }}</p>
                    <p class="text-xs mt-1" style="color: rgba(255,255,255,0.65);">Dosen Tetap</p>
                </div>
                <div class="rounded-xl p-4 text-center" style="background: rgba(255,255,255,0.08);">
                    <p class="text-2xl font-bold text-white mono">{{ $stats['akreditasi'] }}</p>
                    <p class="text-xs mt-1" style="color: rgba(255,255,255,0.65);">Akreditasi</p>
                </div>
            </div>
        </div>

        <div class="relative z-10 text-xs" style="color: rgba(255,255,255,0.55);">
            <p class="flex items-center gap-2 mb-3">
                <span class="w-2 h-2 rounded-full" style="background:#4ade80;"></span>
                Sistem aktif — Tahun Akademik {{ now()->month >= 8 ? now()->year . '/' . (now()->year + 1) : (now()->year - 1) . '/' . now()->year }}
            </p>
        </div>
    </div>

    {{-- Panel kanan: form login mahasiswa --}}
    <div class="flex items-center justify-center p-6 sm:p-12">
        <div class="w-full max-w-sm">

            {{-- logo mobile only --}}
            <div class="flex items-center gap-3 mb-8 lg:hidden">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background: var(--accent);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8">
                        <path d="M3 21h18M4 21V8l8-5 8 5v13M9 21v-6h6v6M9 12h.01M15 12h.01M9 9h.01M15 9h.01" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="leading-tight">
                    <p class="text-xs uppercase tracking-wider" style="color: var(--muted-foreground);">Politeknik Manufaktur Bandung</p>
                    <p class="font-semibold font-serif-display">Automation Engineering</p>
                </div>
            </div>

            <h2 class="text-3xl font-bold font-serif-display mb-2">Selamat Datang</h2>
            <p class="text-sm mb-8" style="color: var(--muted-foreground);">Masuk dengan NIM untuk mengakses layanan mahasiswa</p>

            @if (session('status'))
                <div class="text-sm rounded-lg px-4 py-3 mb-5" style="background:#e8f5e9; color:#2e7d32;">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="text-sm rounded-lg px-4 py-3 mb-5" style="background:#fdecea; color:#c62828;">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('public.proses-cek-nim') }}">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2">Nomor Induk Mahasiswa (NIM)</label>
                    <input type="text" name="nim" value="{{ old('nim') }}"
                        placeholder="Masukkan NIM Anda"
                        class="w-full rounded-lg px-4 py-3 text-sm border font-mono"
                        style="background: var(--card); border-color: var(--border);"
                        required autofocus>
                </div>

                <button type="submit"
                    class="btn-primary w-full text-white font-medium py-3 rounded-lg text-sm">
                    Masuk
                </button>

                <div class="flex flex-col gap-2 mt-6 text-center text-sm">
                    <p style="color: var(--muted-foreground);">
                        Bukan Mahasiswa?
                        <a href="{{ route('login') }}" class="font-medium" style="color: var(--primary);">Login sebagai Admin / Kaprodi / Dosen</a>
                    </p>
                </div>
            </form>

            <p class="text-center text-xs mt-10" style="color: var(--muted-foreground);">
                © {{ now()->year }} Politeknik Manufaktur Bandung — Sistem Informasi Akreditasi
            </p>
        </div>
    </div>
</div>
</body>
</html>