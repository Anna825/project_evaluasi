@extends('layouts.app')

@section('title', 'Dashboard Admin - Evaluasi PBM')
@section('page-title', 'Dashboard Administrator')
@section('page-desc', 'Kelola seluruh data dan fitur sistem evaluasi PBM')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="rounded-2xl p-5 border card-hover" style="background: var(--card); border-color: var(--border);">
            <p class="text-xs font-medium uppercase tracking-wider mb-2" style="color: var(--muted-foreground);">Total Mahasiswa</p>
            <p class="text-3xl font-bold mono" style="color: #1565c0;">{{ $stats['mahasiswa'] }}</p>
        </div>
        <div class="rounded-2xl p-5 border card-hover" style="background: var(--card); border-color: var(--border);">
            <p class="text-xs font-medium uppercase tracking-wider mb-2" style="color: var(--muted-foreground);">Total Dosen</p>
            <p class="text-3xl font-bold mono" style="color: #2e7d32;">{{ $stats['dosen'] }}</p>
        </div>
        <div class="rounded-2xl p-5 border card-hover" style="background: var(--card); border-color: var(--border);">
            <p class="text-xs font-medium uppercase tracking-wider mb-2" style="color: var(--muted-foreground);">Menunggu Aktivasi</p>
            <p class="text-3xl font-bold mono" style="color: var(--accent);">{{ $stats['dosen_pending'] }}</p>
            <p class="text-xs mt-1" style="color: var(--muted-foreground);">Perlu ditinjau</p>
        </div>
        <div class="rounded-2xl p-5 border card-hover" style="background: var(--card); border-color: var(--border);">
            <p class="text-xs font-medium uppercase tracking-wider mb-2" style="color: var(--muted-foreground);">Program Studi</p>
            <p class="text-3xl font-bold mono" style="color: #6a1b9a;">{{ $stats['prodi'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
            <div class="px-6 py-4 border-b" style="border-color: var(--border);">
                <h2 class="font-serif-display text-lg">Aktivitas Terbaru</h2>
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
            <div class="px-6 py-4 border-b" style="border-color: var(--border);">
                <h2 class="font-serif-display text-lg">Ringkasan Program Studi</h2>
            </div>
            <div class="p-4 space-y-3">
                @forelse ($prodiRingkasan as $p)
                    <div class="flex items-center justify-between">
                        <p class="text-sm">{{ $p->nama }}</p>
                        <p class="text-sm font-semibold mono">{{ $p->mahasiswa_count }}</p>
                    </div>
                @empty
                    <p class="text-sm" style="color: var(--muted-foreground);">Belum ada program studi.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection