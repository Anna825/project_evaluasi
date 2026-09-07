@extends('layouts.app')

@section('title', 'Pusat Laporan - Evaluasi PBM')
@section('page-title', 'Pusat Laporan')
@section('page-desc', 'Export data untuk kebutuhan evidence akreditasi.')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="rounded-2xl border p-5 card-hover" style="background: var(--card); border-color: var(--border);">
            <h2 class="font-serif-display text-lg mb-1">Data Mahasiswa</h2>
            <p class="text-sm mb-3" style="color: var(--muted-foreground);">Rekap seluruh data mahasiswa beserta status dan IPK.</p>
            <a href="{{ route('report.mahasiswa.excel') }}" class="inline-block px-4 py-2 rounded-xl text-sm font-medium text-white" style="background: var(--primary);">Download Excel</a>
        </div>

        <div class="rounded-2xl border p-5 card-hover" style="background: var(--card); border-color: var(--border);">
            <h2 class="font-serif-display text-lg mb-1">Rekap Prestasi</h2>
            <p class="text-sm mb-3" style="color: var(--muted-foreground);">Rekap seluruh prestasi dosen dan mahasiswa.</p>
            <a href="{{ route('report.prestasi.excel') }}" class="inline-block px-4 py-2 rounded-xl text-sm font-medium text-white" style="background: var(--primary);">Download Excel</a>
        </div>

        <div class="rounded-2xl border p-5 card-hover" style="background: var(--card); border-color: var(--border);">
            <h2 class="font-serif-display text-lg mb-1">Rekap Penelitian &amp; PKM</h2>
            <p class="text-sm mb-3" style="color: var(--muted-foreground);">Rekap seluruh kegiatan penelitian dan PKM dosen.</p>
            <a href="{{ route('report.penelitian.excel') }}" class="inline-block px-4 py-2 rounded-xl text-sm font-medium text-white" style="background: var(--primary);">Download Excel</a>
        </div>

        <div class="rounded-2xl border p-5 card-hover" style="background: var(--card); border-color: var(--border);">
            <h2 class="font-serif-display text-lg mb-1">Laporan Capaian CPL</h2>
            <p class="text-sm mb-3" style="color: var(--muted-foreground);">Pilih kurikulum untuk laporan capaian CPL (PDF).</p>
            <div class="space-y-1">
                @forelse (\App\Models\Kurikulum::all() as $k)
                    <a href="{{ route('report.laporan-cpl.pdf', $k) }}" class="block text-sm hover:underline" style="color: var(--primary);">{{ $k->nama }} — Download PDF</a>
                @empty
                    <p class="text-sm" style="color: var(--muted-foreground);">Belum ada kurikulum.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection