@extends('layouts.app')

@section('title', 'Dashboard Kaprodi - Evaluasi PBM')
@section('page-title', 'Dashboard Ketua Program Studi')
@section('page-desc', ($prodi->nama ?? 'Program Studi') . ' — Tahun Akademik ' . (now()->month >= 8 ? now()->year . '/' . (now()->year + 1) : (now()->year - 1) . '/' . now()->year))

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('mahasiswa.index') }}"
        class="rounded-2xl p-5 border card-hover block"
        style="background: var(--card); border-color: var(--border);">
            <p class="text-xs font-medium uppercase tracking-wider mb-2"
            style="color: var(--muted-foreground);">
                Mahasiswa Aktif
            </p>

            <p class="text-3xl font-bold mono" style="color: #1565c0;">
                {{ $stats['mahasiswa'] }}
            </p>
        </a>
        <a href="{{ route('kaprodi.dosen.index') }}"
        class="rounded-2xl p-5 border card-hover block"
        style="background: var(--card); border-color: var(--border);">

            <p class="text-xs font-medium uppercase tracking-wider mb-2"
            style="color: var(--muted-foreground);">
                Dosen Tetap
            </p>

            <p class="text-3xl font-bold mono"
            style="color: #2e7d32;">
                {{ $stats['dosen'] }}
            </p>
        </a>
        <a href="{{ route('kurikulum.index') }}"
        class="rounded-2xl p-5 border card-hover block"
        style="background: var(--card); border-color: var(--border);">
            <p class="text-xs font-medium uppercase tracking-wider mb-2"
            style="color: var(--muted-foreground);">
                Kurikulum Aktif
            </p>

            <p class="text-3xl font-bold mono" style="color: #6a1b9a;">
                {{ $stats['kurikulum_aktif'] }}
            </p>
        </a>
        <div class="rounded-2xl p-5 border card-hover" style="background: var(--card); border-color: var(--border);">
            <p class="text-xs font-medium uppercase tracking-wider mb-2" style="color: var(--muted-foreground);">Penelitian Menunggu Verifikasi</p>
            <p class="text-3xl font-bold mono" style="color: var(--accent);">{{ $stats['penelitian_pending'] }}</p>
            <!-- @if ($stats['penelitian_pending'] > 0)
                <a href="{{ route('penelitian-pkm.verifikasi') }}" class="text-xs mt-1 inline-block font-medium" style="color: var(--primary);">Tinjau sekarang &rarr;</a>
            @else
                <p class="text-xs mt-1" style="color: var(--muted-foreground);">Semua sudah ditinjau</p>
            @endif -->
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
            <div class="px-6 py-4 border-b" style="border-color: var(--border);">
                <h2 class="font-serif-display text-lg">Aktivitas Terbaru Prodi</h2>
            </div>
            <div class="p-4 space-y-2">
                @forelse ($activities as $a)
                    <div class="flex items-center justify-between px-4 py-3 rounded-xl" style="background: var(--secondary);">
                        <div class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full shrink-0" style="background: {{ $a['color'] }};"></span>
                            <p class="text-sm">{{ $a['label'] }}</p>
                        </div>
                        <p class="text-xs shrink-0 ml-4" style="color: var(--muted-foreground);">{{ $a['time']->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="text-sm text-center py-8" style="color: var(--muted-foreground);">Belum ada aktivitas.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
            <div class="px-6 py-4 border-b flex items-center justify-between" style="border-color: var(--border);">
                <h2 class="font-serif-display text-lg">Kurikulum Prodi</h2>
                <a href="{{ route('kurikulum.index') }}" class="text-xs font-medium" style="color: var(--primary);">Lihat semua</a>
            </div>
            <div class="p-4 space-y-3">
                @forelse ($kurikulumList as $k)
                    <div class="p-3 rounded-xl" style="background: var(--secondary);">
                        <div class="flex items-center justify-between mb-1">
                            <p class="text-sm font-medium">{{ $k->nama }}</p>
                            <span class="text-xs px-2 py-0.5 rounded-full font-semibold"
                                style="background: {{ $k->status === 'aktif' ? '#f0fdf4' : '#fffbeb' }}; color: {{ $k->status === 'aktif' ? '#166534' : '#92400e' }};">
                                {{ ucfirst($k->status) }}
                            </span>
                        </div>
                        <p class="text-xs" style="color: var(--muted-foreground);">
                            Berlaku {{ $k->tahun_berlaku_mulai }} · {{ $k->mata_kuliah_count }} Mata Kuliah · {{ $k->cpl_count }} CPL
                        </p>
                    </div>
                @empty
                    <p class="text-sm" style="color: var(--muted-foreground);">Belum ada kurikulum untuk prodi ini.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection