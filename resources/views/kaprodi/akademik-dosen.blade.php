@extends('layouts.app')

@section('title', 'Kegiatan Akademik Dosen - Evaluasi PBM')
@section('page-title', 'Kegiatan Akademik Dosen')
@section('page-desc', ($prodi->nama ?? 'Program Studi') . ' — ringkasan kegiatan akademik dosen pada program studi')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <p class="text-sm" style="color: var(--muted-foreground);">Ringkasan ini membantu memantau beban mengajar, penelitian/PKM, dan capaian dosen.</p>
        <a href="{{ route('kaprodi.dashboard') }}" class="ui-btn ui-btn-outline">← Dashboard Utama</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="ui-card p-5"><p class="text-xs uppercase tracking-wider font-semibold" style="color: var(--muted-foreground);">Dosen</p><p class="text-3xl font-bold mono mt-2" style="color: #2e7d32;">{{ $stats['dosen'] }}</p></div>
        <a href="{{ route('mata-kuliah.index') }}" class="ui-card ui-card-hover block p-5"><p class="text-xs uppercase tracking-wider font-semibold" style="color: var(--muted-foreground);">Mata Kuliah</p><p class="text-3xl font-bold mono mt-2" style="color: #1565c0;">{{ $stats['mata_kuliah'] }}</p><p class="text-xs mt-2" style="color: var(--muted-foreground);">Kelola →</p></a>
        <a href="{{ route('mata-kuliah.index') }}" class="ui-card ui-card-hover block p-5"><p class="text-xs uppercase tracking-wider font-semibold" style="color: var(--muted-foreground);">Kelas</p><p class="text-3xl font-bold mono mt-2">{{ $stats['kelas'] }}</p><p class="text-xs mt-2" style="color: var(--muted-foreground);">Lihat perkuliahan →</p></a>
        <a href="{{ route('penelitian-pkm.index') }}" class="ui-card ui-card-hover block p-5"><p class="text-xs uppercase tracking-wider font-semibold" style="color: var(--muted-foreground);">Penelitian & PKM</p><p class="text-3xl font-bold mono mt-2" style="color: #6a1b9a;">{{ $stats['penelitian'] }}</p><p class="text-xs mt-2" style="color: var(--muted-foreground);">Buka data →</p></a>
        <a href="{{ route('prestasi-dosen.index') }}" class="ui-card ui-card-hover block p-5"><p class="text-xs uppercase tracking-wider font-semibold" style="color: var(--muted-foreground);">Prestasi Dosen</p><p class="text-3xl font-bold mono mt-2" style="color: var(--accent);">{{ $stats['prestasi_dosen'] }}</p><p class="text-xs mt-2" style="color: var(--muted-foreground);">Buka data →</p></a>
    </div>

    <div class="ui-card p-6">
        <div class="ui-section-title">
            <div>
                <h2 class="font-serif-display text-xl">Akses Kegiatan</h2>
                <p class="text-sm mt-1" style="color: var(--muted-foreground);">Gunakan menu berikut untuk masuk ke modul yang berkaitan.</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('mata-kuliah.index') }}" class="ui-btn ui-btn-secondary justify-start">Mata Kuliah, CPMK & RPS →</a>
            <a href="{{ route('penelitian-pkm.index') }}" class="ui-btn ui-btn-secondary justify-start">Penelitian & PKM →</a>
            <a href="{{ route('prestasi-dosen.index') }}" class="ui-btn ui-btn-secondary justify-start">Prestasi Dosen →</a>
        </div>
    </div>
@endsection
