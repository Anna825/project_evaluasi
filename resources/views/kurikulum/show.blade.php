@extends('layouts.app')

@section('title', 'Detail Kurikulum - Evaluasi PBM')
@section('page-title', $kurikulum->nama)
@section('page-desc', ($kurikulum->prodi->nama ?? '-') . ' — Berlaku sejak ' . $kurikulum->tahun_berlaku_mulai)

@section('content')
    <a href="{{ route('kurikulum.laporan-cpl', $kurikulum) }}" class="inline-block mb-4 text-sm hover:underline" style="color: var(--primary);">
        Lihat Laporan Capaian CPL Semua Mahasiswa
    </a>

    <div class="rounded-2xl border overflow-hidden mb-6" style="background: var(--card); border-color: var(--border);">
        <div class="px-6 py-4 border-b flex items-center justify-between" style="border-color: var(--border);">
            <h2 class="font-serif-display text-lg">Daftar CPL</h2>
            <a href="{{ route('cpl.create', $kurikulum) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium text-white" style="background: var(--primary);">+ Tambah CPL</a>
        </div>
        <div class="p-2">
            @forelse ($kurikulum->cpl as $cpl)
                <div class="flex justify-between items-start p-4 border-b last:border-b-0" style="border-color: var(--border);">
                    <div>
                        <p class="font-medium text-sm">{{ $cpl->kode }} @if($cpl->domain) <span class="text-xs" style="color: var(--muted-foreground);">({{ $cpl->domain }})</span> @endif</p>
                        <p class="text-sm mt-1" style="color: var(--muted-foreground);">{{ $cpl->deskripsi }}</p>
                    </div>
                    <div class="flex gap-3 shrink-0 ml-4">
                        <a href="{{ route('cpl.edit', $cpl) }}" class="text-sm hover:underline" style="color: var(--accent);">Edit</a>
                        <form method="POST" action="{{ route('cpl.destroy', $cpl) }}" onsubmit="return confirm('Yakin hapus CPL ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm hover:underline" style="color: #a13d3d;">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="p-6 text-center text-sm" style="color: var(--muted-foreground);">Belum ada CPL.</p>
            @endforelse
        </div>
    </div>

    <div class="rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
        <div class="px-6 py-4 border-b" style="border-color: var(--border);">
            <h2 class="font-serif-display text-lg">Daftar Mata Kuliah</h2>
        </div>
        <div class="p-2">
            @forelse ($kurikulum->mataKuliah as $mk)

            <a
                href="{{ route('kurikulum.mata-kuliah.show', [$kurikulum, $mk]) }}"
                class="block p-4 border-b last:border-b-0 text-sm hover:bg-[var(--secondary)] transition"
                style="border-color: var(--border);"
            >
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="font-medium">
                            {{ $mk->kode }} — {{ $mk->nama }}
                        </p>

                        <p
                            class="text-xs mt-1"
                            style="color: var(--muted-foreground);"
                        >
                            {{ $mk->sks }} SKS
                        </p>
                    </div>

                    <span
                        class="text-sm"
                        style="color: var(--primary);"
                    >
                        Lihat Detail →
                    </span>
                </div>
            </a>

            @empty                
                <p class="p-6 text-center text-sm" style="color: var(--muted-foreground);">Belum ada mata kuliah.</p>
            @endforelse
        </div>
    </div>
@endsection