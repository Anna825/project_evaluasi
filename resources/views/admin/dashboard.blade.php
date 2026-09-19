@extends('layouts.app')

@section('title', 'Dashboard Admin - Evaluasi PBM')
@section('page-title', 'Dashboard Administrator')
@section('page-desc', 'Ringkasan pengelolaan data dan aktivitas Sistem Informasi Evaluasi PBM')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('mahasiswa.index') }}" class="block rounded-2xl p-5 border card-hover" style="background: var(--card); border-color: var(--border);">
            <p class="text-xs font-medium uppercase tracking-wider mb-2" style="color: var(--muted-foreground);">Total Mahasiswa</p>
            <p class="text-3xl font-bold mono" style="color: #1565c0;">{{ $stats['mahasiswa'] }}</p>
            <p class="text-xs mt-2" style="color: var(--muted-foreground);">Buka data mahasiswa →</p>
        </a>

        <a href="{{ route('admin.users.index') }}" class="block rounded-2xl p-5 border card-hover" style="background: var(--card); border-color: var(--border);">
            <p class="text-xs font-medium uppercase tracking-wider mb-2" style="color: var(--muted-foreground);">Total Dosen</p>
            <p class="text-3xl font-bold mono" style="color: #2e7d32;">{{ $stats['dosen'] }}</p>
            <p class="text-xs mt-2" style="color: var(--muted-foreground);">Kelola akun dosen →</p>
        </a>

        <a href="{{ route('admin.users.index') }}" class="block rounded-2xl p-5 border card-hover" style="background: var(--card); border-color: var(--border);">
            <p class="text-xs font-medium uppercase tracking-wider mb-2" style="color: var(--muted-foreground);">Menunggu Aktivasi</p>
            <p class="text-3xl font-bold mono" style="color: var(--accent);">{{ $stats['dosen_pending'] }}</p>
            <p class="text-xs mt-2" style="color: var(--muted-foreground);">Perlu ditinjau →</p>
        </a>

        <a href="{{ route('prodi.index') }}" class="block rounded-2xl p-5 border card-hover" style="background: var(--card); border-color: var(--border);">
            <p class="text-xs font-medium uppercase tracking-wider mb-2" style="color: var(--muted-foreground);">Program Studi</p>
            <p class="text-3xl font-bold mono" style="color: #6a1b9a;">{{ $stats['prodi'] }}</p>
            <p class="text-xs mt-2" style="color: var(--muted-foreground);">Kelola program studi →</p>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
            <div class="px-6 py-4 border-b flex items-center justify-between" style="border-color: var(--border);">
                <h2 class="font-serif-display text-lg">Aktivitas Terbaru</h2>
                <span class="text-xs" style="color: var(--muted-foreground);">Sistem</span>
            </div>
            <div class="p-4 space-y-2">
                @forelse ($activities as $a)
                    <div class="flex items-center justify-between px-4 py-3 rounded-xl" style="background: var(--secondary);">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-2 h-2 rounded-full shrink-0" style="background: {{ $a['color'] }};"></span>
                            <p class="text-sm truncate">{{ $a['label'] }}</p>
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
                <h2 class="font-serif-display text-lg">Ringkasan Program Studi</h2>
                <a href="{{ route('prodi.index') }}" class="text-xs font-semibold" style="color: var(--primary);">Kelola →</a>
            </div>
            <div class="p-4 space-y-3">
                @forelse ($prodiRingkasan as $p)
                    <div class="flex items-center justify-between px-3 py-2 rounded-lg" style="background: var(--secondary);">
                        <p class="text-sm truncate">{{ $p->nama }}</p>
                        <p class="text-sm font-semibold mono ml-3">{{ $p->mahasiswa_count }}</p>
                    </div>
                @empty
                    <p class="text-sm" style="color: var(--muted-foreground);">Belum ada program studi.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
