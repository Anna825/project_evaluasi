@extends('layouts.app')

@section('title', 'Manajemen Program Studi - Evaluasi PBM')
@section('page-title', 'Manajemen Program Studi')
@section('page-desc', ($prodi->nama ?? 'Program Studi') . ' — pusat ringkasan data akademik dan capaian program studi')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <p class="text-sm" style="color: var(--muted-foreground);">Gunakan ringkasan ini untuk berpindah ke data yang perlu dikelola.</p>
        </div>
        <a href="{{ route('kaprodi.dashboard') }}" class="ui-btn ui-btn-outline">← Dashboard Utama</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('mahasiswa.index') }}" class="ui-card ui-card-hover block p-5">
            <p class="text-xs uppercase tracking-wider font-semibold" style="color: var(--muted-foreground);">Mahasiswa</p>
            <p class="text-3xl font-bold mono mt-2" style="color: #1565c0;">{{ $stats['mahasiswa'] }}</p>
            <p class="text-xs mt-2" style="color: var(--muted-foreground);">{{ $stats['mahasiswa_aktif'] }} aktif · Buka data →</p>
        </a>
        <a href="{{ route('kaprodi.dosen.index') }}" class="ui-card ui-card-hover block p-5">
            <p class="text-xs uppercase tracking-wider font-semibold" style="color: var(--muted-foreground);">Dosen</p>
            <p class="text-3xl font-bold mono mt-2" style="color: #2e7d32;">{{ $stats['dosen'] }}</p>
            <p class="text-xs mt-2" style="color: var(--muted-foreground);">Lihat daftar dosen →</p>
        </a>
        <a href="{{ route('kurikulum.index') }}" class="ui-card ui-card-hover block p-5">
            <p class="text-xs uppercase tracking-wider font-semibold" style="color: var(--muted-foreground);">Kurikulum</p>
            <p class="text-3xl font-bold mono mt-2" style="color: #6a1b9a;">{{ $stats['kurikulum'] }}</p>
            <p class="text-xs mt-2" style="color: var(--muted-foreground);">{{ $stats['kurikulum_aktif'] }} aktif · Kelola →</p>
        </a>
        <a href="{{ route('penelitian-pkm.verifikasi') }}" class="ui-card ui-card-hover block p-5">
            <p class="text-xs uppercase tracking-wider font-semibold" style="color: var(--muted-foreground);">Penelitian & PKM</p>
            <p class="text-3xl font-bold mono mt-2" style="color: var(--accent);">{{ $stats['penelitian'] }}</p>
            <p class="text-xs mt-2" style="color: var(--muted-foreground);">Yang melibatkan dosen prodi →</p>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <a href="{{ route('kaprodi.prestasi.mahasiswa') }}" class="ui-card ui-card-hover block p-6">
            <p class="text-xs uppercase tracking-wider font-semibold" style="color: var(--muted-foreground);">Capaian Mahasiswa</p>
            <p class="text-2xl font-bold mono mt-2">{{ $stats['prestasi_mahasiswa'] }}</p>
            <p class="text-sm mt-2" style="color: var(--muted-foreground);">Prestasi mahasiswa yang tercatat pada program studi.</p>
            <p class="text-sm font-semibold mt-5" style="color: var(--primary);">Lihat prestasi mahasiswa →</p>
        </a>
        <a href="{{ route('kaprodi.prestasi.dosen') }}" class="ui-card ui-card-hover block p-6">
            <p class="text-xs uppercase tracking-wider font-semibold" style="color: var(--muted-foreground);">Capaian Dosen</p>
            <p class="text-2xl font-bold mono mt-2">{{ $stats['prestasi_dosen'] }}</p>
            <p class="text-sm mt-2" style="color: var(--muted-foreground);">Prestasi dosen dari program studi.</p>
            <p class="text-sm font-semibold mt-5" style="color: var(--primary);">Lihat prestasi dosen →</p>
        </a>
        <a href="{{ route('report.index') }}" class="ui-card ui-card-hover block p-6 md:col-span-2">
            <p class="text-xs uppercase tracking-wider font-semibold" style="color: var(--muted-foreground);">Pusat Laporan</p>
            <p class="text-sm mt-2" style="color: var(--muted-foreground);">Akses laporan mahasiswa, prestasi, penelitian, dan laporan CPL dari satu tempat.</p>
            <p class="text-sm font-semibold mt-5" style="color: var(--primary);">Buka pusat laporan →</p>
        </a>
    </div>
@endsection
