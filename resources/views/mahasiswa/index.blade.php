@extends('layouts.app')

@section('title', 'Data Mahasiswa - Evaluasi PBM')
@section('page-title', 'Data Mahasiswa')
@section('page-desc', 'Kelola data seluruh mahasiswa program studi')

@section('content')
    <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between mb-6">
        <form method="GET" action="{{ route('mahasiswa.index') }}" class="flex-1 max-w-sm">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama atau NIM..."
                class="w-full rounded-lg px-4 py-2.5 text-sm border"
                style="background: var(--card); border-color: var(--border);"
                onchange="this.form.submit()">
        </form>
        <div class="flex gap-2 shrink-0">
            <a href="{{ route('mahasiswa.import.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium border hover:bg-gray-50 transition"
                style="border-color: var(--border); color: var(--primary); background: var(--card);">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Import Excel
            </a>
            <a href="{{ route('mahasiswa.create') }}"
                class="btn-primary px-4 py-2.5 rounded-lg text-sm font-medium text-white"
                style="background: var(--primary);">
                + Tambah Mahasiswa
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="text-sm rounded-lg px-4 py-3 mb-5" style="background:#e8f5e9; color:#2e7d32;">
            {{ session('status') }}
        </div>
    @endif

    <div class="rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="background: var(--secondary); border-bottom: 1px solid var(--border);">
                        @foreach (['NIM', 'Nama', 'Prodi', 'Angkatan', 'IPK', 'Prestasi', 'Status', 'Aksi'] as $h)
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--muted-foreground);">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mahasiswa as $i => $m)
                        @php
                            $statusBadge = [
                                'aktif' => ['bg' => '#eff6ff', 'text' => '#1e40af'],
                                'cuti' => ['bg' => '#fffbeb', 'text' => '#92400e'],
                                'lulus' => ['bg' => '#f0fdf4', 'text' => '#166534'],
                                'DO' => ['bg' => '#fef2f2', 'text' => '#991b1b'],
                            ];
                            $badge = $statusBadge[$m->status] ?? ['bg' => '#eee', 'text' => '#333'];
                        @endphp
                        <tr style="border-bottom: {{ $i < $mahasiswa->count() - 1 ? '1px solid var(--border)' : 'none' }};">
                            <td class="px-5 py-3.5 mono text-xs font-semibold" style="color: var(--primary);">{{ $m->nim }}</td>
                            <td class="px-5 py-3.5 text-sm font-medium">{{ $m->nama }}</td>
                            <td class="px-5 py-3.5 text-sm" style="color: var(--muted-foreground);">{{ $m->prodi->nama ?? '-' }}</td>
                            <td class="px-5 py-3.5 text-sm">{{ $m->angkatan }}</td>
                            <td class="px-5 py-3.5 text-sm mono font-semibold" style="color: var(--accent);">{{ $m->ipk_terakhir ? number_format($m->ipk_terakhir, 2) : '-' }}</td>
                            <td class="px-5 py-3.5 text-sm">{{ $m->prestasi_count }}</td>
                            <td class="px-5 py-3.5">
                                <span class="text-xs px-2.5 py-1 rounded-full font-semibold" style="background: {{ $badge['bg'] }}; color: {{ $badge['text'] }};">
                                    {{ ucfirst($m->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3 text-xs font-medium">
                                    <a href="{{ route('mahasiswa.show', $m) }}" style="color: var(--primary);" class="hover:underline">Detail</a>
                                    <a href="{{ route('mahasiswa.edit', $m) }}" style="color: var(--accent);" class="hover:underline">Edit</a>
                                    <form method="POST" action="{{ route('mahasiswa.destroy', $m) }}" onsubmit="return confirm('Yakin hapus data mahasiswa ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="color: #c62828;" class="hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-10 text-sm text-center" style="color: var(--muted-foreground);">
                                @if (request('search'))
                                    Tidak ada mahasiswa yang cocok dengan pencarian "{{ request('search') }}".
                                @else
                                    Belum ada data mahasiswa.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $mahasiswa->links() }}
    </div>
@endsection