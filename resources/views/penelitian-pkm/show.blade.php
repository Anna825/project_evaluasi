@extends('layouts.app')

@section('title', 'Detail Penelitian/PKM - Evaluasi PBM')
@section('page-title', $penelitianPkm->judul)
@section('page-desc', $penelitianPkm->jenis . ' — ' . ($penelitianPkm->tahunAkademik->label ?? '-'))

@section('content')
    {{-- Kembali --}}
    <div class="mb-4 text-left">
        <a
            href="{{ route('penelitian-pkm.index') }}"
            class="text-sm underline hover:opacity-70"
            style="color: var(--muted-foreground);"
        >
            ← Kembali ke Penelitian & PKM
        </a>
    </div>
    <div class="rounded-2xl border p-5 mb-4" style="background: var(--card); border-color: var(--border);">
        <span class="px-2 py-1 rounded-full text-xs font-medium" style="background: var(--secondary);">{{ ucfirst($penelitianPkm->status) }}</span>
        <div class="mt-3">
            <p class="text-sm font-medium mb-1">Tim</p>
            @foreach ($penelitianPkm->dosen as $d)
                <p class="text-sm" style="color: var(--muted-foreground);">{{ $d->nama }} ({{ $d->pivot->peran }})</p>
            @endforeach
        </div>
    </div>

    <div class="rounded-2xl border p-5 mb-4" style="background: var(--card); border-color: var(--border);">
        <h2 class="font-serif-display text-lg mb-3">Laporan Akhir</h2>
        @if ($penelitianPkm->laporanAkhir)
            <p class="text-sm">Link: <a href="{{ $penelitianPkm->laporanAkhir->link_laporan }}" target="_blank" class="hover:underline" style="color: var(--primary);">{{ $penelitianPkm->laporanAkhir->link_laporan }}</a></p>
            <p class="text-sm" style="color: var(--muted-foreground);">Diupload: {{ $penelitianPkm->laporanAkhir->tanggal_upload }}</p>
        @else
            <form method="POST" action="{{ route('laporan-akhir.store', $penelitianPkm) }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Link Laporan</label>
                    <input type="url" name="link_laporan" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Tanggal Upload</label>
                    <input type="date" name="tanggal_upload" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl text-sm font-medium text-white" style="background: var(--primary);">Simpan Laporan Akhir</button>
            </form>
        @endif
    </div>

    <div class="rounded-2xl border p-5 mb-4" style="background: var(--card); border-color: var(--border);">
        <h2 class="font-serif-display text-lg mb-3">Hilirisasi</h2>
        @if ($penelitianPkm->hilirisasi)
            <p class="text-sm">Bentuk Luaran: {{ $penelitianPkm->hilirisasi->bentuk_luaran }}</p>
            <p class="text-sm" style="color: var(--muted-foreground);">Status: {{ $penelitianPkm->hilirisasi->status_hilirisasi }}</p>
        @else
            <form method="POST" action="{{ route('hilirisasi.store', $penelitianPkm) }}">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Bentuk Luaran</label>
                    <input type="text" name="bentuk_luaran" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Status Hilirisasi</label>
                    <input type="text" name="status_hilirisasi" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl text-sm font-medium text-white" style="background: var(--primary);">Simpan Hilirisasi</button>
            </form>
        @endif
    </div>
@endsection