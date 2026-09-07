@extends('layouts.app')

@section('title', $mahasiswa->nama . ' - Evaluasi PBM')
@section('page-title', $mahasiswa->nama)
@section('page-desc', 'Detail data mahasiswa')

@section('content')
    @php
        $statusBadge = [
            'aktif' => ['bg' => '#eff6ff', 'text' => '#1e40af'],
            'cuti' => ['bg' => '#fffbeb', 'text' => '#92400e'],
            'lulus' => ['bg' => '#f0fdf4', 'text' => '#166534'],
            'DO' => ['bg' => '#fef2f2', 'text' => '#991b1b'],
        ];
        $badge = $statusBadge[$mahasiswa->status] ?? ['bg' => '#eee', 'text' => '#333'];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 rounded-2xl border p-6" style="background: var(--card); border-color: var(--border);">
            <div class="w-14 h-14 rounded-full flex items-center justify-center font-bold text-xl text-white mb-4" style="background: var(--primary);">
                {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
            </div>
            <h2 class="font-serif-display text-xl mb-1">{{ $mahasiswa->nama }}</h2>
            <span class="text-xs px-2.5 py-1 rounded-full font-semibold inline-block mb-5" style="background: {{ $badge['bg'] }}; color: {{ $badge['text'] }};">
                {{ ucfirst($mahasiswa->status) }}
            </span>

            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt style="color: var(--muted-foreground);">NIM</dt>
                    <dd class="mono font-semibold">{{ $mahasiswa->nim }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt style="color: var(--muted-foreground);">Program Studi</dt>
                    <dd class="font-medium text-right">{{ $mahasiswa->prodi->nama ?? '-' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt style="color: var(--muted-foreground);">Angkatan</dt>
                    <dd class="font-medium">{{ $mahasiswa->angkatan }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt style="color: var(--muted-foreground);">IPK Terakhir</dt>
                    <dd class="mono font-semibold" style="color: var(--accent);">{{ $mahasiswa->ipk_terakhir ? number_format($mahasiswa->ipk_terakhir, 2) : '-' }}</dd>
                </div>
            </dl>

            <div class="flex gap-2 mt-6">
                <a href="{{ route('mahasiswa.edit', $mahasiswa) }}" class="flex-1 text-center px-4 py-2.5 rounded-lg text-sm font-medium text-white" style="background: var(--primary);">
                    Edit
                </a>
                <a href="{{ route('mahasiswa.index') }}" class="flex-1 text-center px-4 py-2.5 rounded-lg text-sm font-medium border" style="border-color: var(--border); color: var(--muted-foreground);">
                    Kembali
                </a>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
                <div class="px-6 py-4 border-b" style="border-color: var(--border);">
                    <h2 class="font-serif-display text-lg">Riwayat Prestasi</h2>
                </div>
                <div class="p-4 space-y-2">
                    @forelse ($mahasiswa->prestasi as $p)
                        <div class="flex items-center justify-between px-4 py-3 rounded-xl" style="background: var(--secondary);">
                            <div>
                                <p class="text-sm font-medium">{{ $p->nama_kegiatan }}</p>
                                <p class="text-xs" style="color: var(--muted-foreground);">{{ $p->tingkat ?? '-' }}</p>
                            </div>
                            <span class="text-xs font-semibold" style="color: var(--accent);">{{ $p->peringkat ?? '-' }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-center py-6" style="color: var(--muted-foreground);">Belum ada data prestasi.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
                <div class="px-6 py-4 border-b" style="border-color: var(--border);">
                    <h2 class="font-serif-display text-lg">Data Tracer Study</h2>
                </div>
                <div class="p-4 space-y-2">
                    @forelse ($mahasiswa->tracerStudy as $t)
                        <div class="flex items-center justify-between px-4 py-3 rounded-xl" style="background: var(--secondary);">
                            <div>
                                <p class="text-sm font-medium">Periode {{ $t->periode_pelacakan }}</p>
                                <p class="text-xs" style="color: var(--muted-foreground);">{{ $t->status_utama ?? '-' }} di {{ $t->nama_instansi ?? '-' }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-center py-6" style="color: var(--muted-foreground);">Belum ada data tracer study.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection