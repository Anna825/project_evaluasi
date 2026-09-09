@extends('layouts.app')

@section('title', 'Dashboard Dosen - Evaluasi PBM')
@section('page-title', 'Dashboard Dosen')
@section('page-desc', 'Ringkasan mata kuliah, penelitian, dan prestasi Anda')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <a
            href="{{ route('mata-kuliah.index') }}"
            class="block rounded-2xl p-5 border card-hover transition hover:shadow-md"
            style="background: var(--card); border-color: var(--border);"
        >
            <p
                class="text-xs font-medium uppercase tracking-wider mb-2"
                style="color: var(--muted-foreground);"
            >
                Mata Kuliah Diampu
            </p>

            <p
                class="text-3xl font-bold mono"
                style="color: #1565c0;"
            >
                {{ $stats['mata_kuliah'] }}
            </p>
        </a>        
        <a
            href="{{ route('mata-kuliah.index') }}"
            class="block rounded-2xl p-5 border card-hover transition hover:shadow-md"
            style="background: var(--card); border-color: var(--border);"
        >
            <p
                class="text-xs font-medium uppercase tracking-wider mb-2"
                style="color: var(--muted-foreground);"
            >
                Total Kelas
            </p>

            <p
                class="text-3xl font-bold mono"
                style="color: #2e7d32;"
            >
                {{ $stats['kelas'] }}
            </p>
        </a>
        <a
            href="{{ route('penelitian-pkm.index') }}"
            class="block rounded-2xl p-5 border card-hover transition hover:shadow-md"
            style="background: var(--card); border-color: var(--border);"
        >
            <p
                class="text-xs font-medium uppercase tracking-wider mb-2"
                style="color: var(--muted-foreground);"
            >
                Penelitian &amp; PKM Aktif
            </p>

            <p
                class="text-3xl font-bold mono"
                style="color: #6a1b9a;"
            >
                {{ $stats['penelitian_aktif'] }}
            </p>
        </a>
        <a
            href="{{ route('prestasi-dosen.index') }}"
            class="block rounded-2xl p-5 border card-hover transition hover:shadow-md"
            style="background: var(--card); border-color: var(--border);"
        >
            <p
                class="text-xs font-medium uppercase tracking-wider mb-2"
                style="color: var(--muted-foreground);"
            >
                Prestasi Tercatat
            </p>

            <p
                class="text-3xl font-bold mono"
                style="color: var(--accent);"
            >
                {{ $stats['prestasi'] }}
            </p>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
            <div class="px-6 py-4 border-b flex items-center justify-between" style="border-color: var(--border);">
                <h2 class="font-serif-display text-lg">Mata Kuliah yang Diampu</h2>
                <a href="{{ route('mata-kuliah.index') }}" class="text-xs font-medium" style="color: var(--primary);">Lihat semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr style="background: var(--secondary); border-bottom: 1px solid var(--border);">
                            @foreach (['Kode', 'Nama Mata Kuliah', 'SKS', 'Semester', 'Kelas'] as $h)
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--muted-foreground);">{{ $h }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mataKuliahList as $i => $mk)
                            <tr style="border-bottom: {{ $i < $mataKuliahList->count() - 1 ? '1px solid var(--border)' : 'none' }};">
                                <td class="px-5 py-3.5 mono text-xs font-semibold" style="color: var(--primary);">{{ $mk->kode }}</td>
                                <td class="px-5 py-3.5 text-sm font-medium">
                                    <a href="{{ route('mata-kuliah.show', $mk) }}" class="hover:underline">{{ $mk->nama }}</a>
                                </td>
                                <td class="px-5 py-3.5 text-sm">{{ $mk->sks }}</td>
                                <td class="px-5 py-3.5 text-sm">{{ $mk->semester_ke }}</td>
                                <td class="px-5 py-3.5 text-sm">{{ $kelasList->where('mata_kuliah_id', $mk->id)->count() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-sm text-center" style="color: var(--muted-foreground);">Belum ada mata kuliah yang diampu.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
            <div class="px-6 py-4 border-b flex items-center justify-between" style="border-color: var(--border);">
                <h2 class="font-serif-display text-lg">Penelitian &amp; PKM Terbaru</h2>
                <a href="{{ route('penelitian-pkm.index') }}" class="text-xs font-medium" style="color: var(--primary);">Lihat semua</a>
            </div>
            <div class="p-4 space-y-3">
                @php
                    $statusColor = [
                        'diajukan' => ['bg' => '#fffbeb', 'text' => '#92400e'],
                        'disetujui' => ['bg' => '#f0fdf4', 'text' => '#166534'],
                        'berjalan' => ['bg' => '#eff6ff', 'text' => '#1e40af'],
                        'selesai' => ['bg' => '#f0fdf4', 'text' => '#166534'],
                        'ditolak' => ['bg' => '#fef2f2', 'text' => '#991b1b'],
                    ];
                @endphp
                @forelse ($penelitianTerbaru as $p)
                    <div class="p-3 rounded-xl" style="background: var(--secondary);">
                        <p class="text-sm font-medium mb-1">{{ $p->judul }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs" style="color: var(--muted-foreground);">{{ ucfirst($p->jenis) }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full font-semibold"
                                style="background: {{ $statusColor[$p->status]['bg'] ?? '#eee' }}; color: {{ $statusColor[$p->status]['text'] ?? '#333' }};">
                                {{ ucfirst($p->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm" style="color: var(--muted-foreground);">Belum ada Penelitian/PKM yang diajukan.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection