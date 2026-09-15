@extends('layouts.app')

@section('title', 'Detail Prestasi Mahasiswa - Evaluasi PBM')
@section('page-title', 'Detail Prestasi Mahasiswa')
@section('page-desc', 'Informasi lengkap prestasi mahasiswa.')

@section('content')

    <div class="w-full">

        {{-- Kembali --}}
        <div class="mb-6">
            <a
                href="{{ route('admin.prestasi.mahasiswa') }}"
                class="inline-flex items-center gap-2 text-sm font-medium hover:underline"
                style="color: var(--primary);"
            >
                ← Kembali ke Prestasi Mahasiswa
            </a>
        </div>

        {{-- Data Mahasiswa --}}
        <div
            class="rounded-2xl border overflow-hidden mb-6"
            style="background: var(--card); border-color: var(--border);"
        >
            <div
                class="px-6 py-5 border-b"
                style="border-color: var(--border);"
            >
                <h2 class="font-serif-display text-2xl">
                    Data Mahasiswa
                </h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <p
                            class="text-sm mb-1"
                            style="color: var(--muted-foreground);"
                        >
                            NIM
                        </p>
                        <p class="font-medium">
                            {{ $prestasi->mahasiswa->first()->nim ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p
                            class="text-sm mb-1"
                            style="color: var(--muted-foreground);"
                        >
                            Nama Mahasiswa
                        </p>
                        <p class="font-medium">
                            {{ $prestasi->mahasiswa->first()->nama ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p
                            class="text-sm mb-1"
                            style="color: var(--muted-foreground);"
                        >
                            Program Studi
                        </p>
                        <p class="font-medium">
                            {{ $prestasi->mahasiswa->first()->prodi->nama ?? '-' }}                        </p>
                    </div>

                </div>
            </div>
        </div>

        {{-- Data Prestasi --}}
        <div
            class="rounded-2xl border overflow-hidden mb-6"
            style="background: var(--card); border-color: var(--border);"
        >
            <div
                class="px-6 py-5 border-b"
                style="border-color: var(--border);"
            >
                <h2 class="font-serif-display text-2xl">
                    Data Prestasi
                </h2>
            </div>

            <div class="p-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <p class="text-sm mb-1"
                           style="color: var(--muted-foreground);">
                            Nama Kegiatan
                        </p>
                        <p class="font-medium">
                            {{ $prestasi->nama_kegiatan }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm mb-1"
                           style="color: var(--muted-foreground);">
                            Tingkat
                        </p>
                        <p class="font-medium">
                            {{ $prestasi->tingkat ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm mb-1"
                           style="color: var(--muted-foreground);">
                            Jenis
                        </p>
                        <p class="font-medium">
                            {{ $prestasi->jenis ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm mb-1"
                           style="color: var(--muted-foreground);">
                            Peringkat / Capaian
                        </p>
                        <p class="font-medium">
                            {{ $prestasi->peringkat ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm mb-1"
                           style="color: var(--muted-foreground);">
                            Tempat Pelaksanaan
                        </p>
                        <p class="font-medium">
                            {{ $prestasi->tempat_pelaksanaan ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm mb-1"
                           style="color: var(--muted-foreground);">
                            Tanggal Penerimaan
                        </p>
                        <p class="font-medium">
                            {{ $prestasi->tanggal_penerimaan?->format('d-m-Y') ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm mb-1"
                           style="color: var(--muted-foreground);">
                            Tahun Akademik
                        </p>
                        <p class="font-medium">
                            {{ $prestasi->tahunAkademik->label ?? '-' }}
                        </p>
                    </div>

                </div>

            </div>
        </div>


        {{-- Dosen Pembimbing --}}
        <div
            class="rounded-2xl border overflow-hidden mb-6"
            style="background: var(--card); border-color: var(--border);"
        >
            <div
                class="px-6 py-5 border-b"
                style="border-color: var(--border);"
            >
                <h2 class="font-serif-display text-2xl">
                    Dosen Pembimbing
                </h2>
            </div>

            <div class="p-6">

                @forelse ($prestasi->dosenPembimbing as $dosen)

                    <div class="py-3 border-b last:border-b-0"
                         style="border-color: var(--border);">

                        <p class="font-medium">
                            {{ $dosen->nama }}
                        </p>

                        <p
                            class="text-sm mt-1"
                            style="color: var(--muted-foreground);"
                        >
                            NIDN: {{ $dosen->nidn ?? '-' }}
                        </p>

                    </div>

                @empty

                    <p
                        class="text-sm"
                        style="color: var(--muted-foreground);"
                    >
                        Tidak ada dosen pembimbing yang tercatat.
                    </p>

                @endforelse

            </div>
        </div>
<!-- 

        {{-- Navigasi --}}
        <div class="flex justify-start pt-2">

            <a
                href="{{ route('admin.prestasi.mahasiswa') }}"
                class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-medium"
                style="background: var(--secondary); color: var(--foreground);"
            >
                ← Kembali
            </a>

        </div> -->

    </div>

@endsection