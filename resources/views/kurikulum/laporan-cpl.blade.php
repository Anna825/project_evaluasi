@extends('layouts.app')

@section('title', 'Laporan Capaian CPL - Evaluasi PBM')
@section('page-title', 'Laporan Capaian CPL')
@section('page-desc', 'Pilih kelas untuk melihat rekap capaian CPL - ' . $kurikulum->nama)

@section('content')
    <div class="mb-5">
        <a href="{{ route('kurikulum.show', $kurikulum) }}"
           class="text-sm hover:underline"
           style="color: var(--muted-foreground);">
            ← Kembali ke Detail Kurikulum
        </a>
    </div>

    <div class="w-full rounded-2xl border p-6 mb-6"
         style="background: var(--card); border-color: var(--border);">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-xs uppercase tracking-wide" style="color: var(--muted-foreground);">Kurikulum</p>
                <p class="font-medium mt-1">{{ $kurikulum->nama }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide" style="color: var(--muted-foreground);">Program Studi</p>
                <p class="font-medium mt-1">{{ $kurikulum->prodi->nama ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide" style="color: var(--muted-foreground);">Jumlah CPL</p>
                <p class="font-medium mt-1">{{ $kurikulum->cpl->count() }}</p>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-5">
        <div>
            <h2 class="text-lg font-semibold">Daftar Kelas</h2>
            <p class="text-sm mt-1" style="color: var(--muted-foreground);">
                Pilih salah satu kelas untuk melihat capaian CPL mahasiswanya.
            </p>
        </div>
        <span class="text-sm" style="color: var(--muted-foreground);">
            {{ $kelasList->count() }} kelas
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse ($kelasList as $kelas)
            <a href="{{ route('kurikulum.laporan-cpl.kelas', [$kurikulum, $kelas]) }}"
               class="group block rounded-2xl border p-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-md"
               style="background: var(--card); border-color: var(--border);">
                <div class="flex items-start justify-between gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0"
                         style="background: var(--secondary); color: var(--primary);">
                        <svg width="25" height="25" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 7.5A2.5 2.5 0 015.5 5h4l2 2h7A2.5 2.5 0 0121 9.5v7A2.5 2.5 0 0118.5 19h-13A2.5 2.5 0 013 16.5v-9z" />
                        </svg>
                    </div>
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2"
                         class="transition-transform duration-200 group-hover:translate-x-1"
                         style="color: var(--muted-foreground);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>

                <div class="mt-5">
                    <h3 class="text-lg font-semibold">{{ $kelas->nama_kelas }}</h3>
                    <p class="text-sm mt-1" style="color: var(--muted-foreground);">
                        {{ $kelas->prodi->nama ?? '-' }}
                    </p>
                    <div class="flex items-center justify-between mt-5 pt-4 border-t"
                         style="border-color: var(--border);">
                        <div>
                            <p class="text-xs" style="color: var(--muted-foreground);">Tingkat</p>
                            <p class="text-sm font-semibold mt-0.5">{{ $kelas->tingkat }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs" style="color: var(--muted-foreground);">Mahasiswa</p>
                            <p class="text-sm font-semibold mt-0.5">{{ $kelas->mahasiswa_count }} orang</p>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full rounded-2xl border p-10 text-center"
                 style="background: var(--card); border-color: var(--border);">
                <div class="text-4xl mb-3">📁</div>
                <h3 class="font-semibold">Belum ada kelas untuk Program Studi ini</h3>
                <p class="text-sm mt-1" style="color: var(--muted-foreground);">
                    Data kelas akan muncul setelah kelas mahasiswa tersedia.
                </p>
            </div>
        @endforelse
    </div>

    @if ($jumlahMahasiswaTanpaKelas > 0)
        <p class="text-sm mt-5" style="color: var(--muted-foreground);">
            Terdapat {{ $jumlahMahasiswaTanpaKelas }} mahasiswa yang belum ditempatkan pada kelas.
            Data tersebut belum ditampilkan dalam daftar kelas.
        </p>
    @endif
@endsection
