@extends('layouts.app')

@section('title', 'Detail Prestasi Dosen - Admin')
@section('page-title', 'Detail Prestasi Dosen')
@section('page-desc', 'Informasi lengkap prestasi dosen.')

@section('content')

    <div class="w-full">

        {{-- Kembali --}}
        <div class="mb-6">
            <a
                href="{{ route('admin.prestasi.dosen') }}"
                class="inline-flex items-center gap-2 text-sm font-medium hover:underline"
                style="color: var(--primary);"
            >
                ← Kembali ke Prestasi Dosen
            </a>
        </div>


        {{-- Dosen Peraih Prestasi --}}
        <div
            class="rounded-2xl border overflow-hidden mb-6"
            style="background: var(--card); border-color: var(--border);"
        >

            <div
                class="px-6 py-5 border-b"
                style="border-color: var(--border);"
            >
                <h2 class="font-serif-display text-2xl">
                    Dosen Peraih Prestasi
                </h2>
            </div>

            <div class="p-6">

                @forelse ($prestasi->dosen as $dosen)

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                        <div>
                            <p
                                class="text-sm mb-1"
                                style="color: var(--muted-foreground);"
                            >
                                Nama Dosen
                            </p>

                            <p class="font-medium">
                                {{ $dosen->nama }}
                            </p>
                        </div>

                        <div>
                            <p
                                class="text-sm mb-1"
                                style="color: var(--muted-foreground);"
                            >
                                NIDN
                            </p>

                            <p class="font-medium">
                                {{ $dosen->nidn ?? '-' }}
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
                                {{ $dosen->prodi->nama ?? '-' }}
                            </p>
                        </div>

                    </div>

                @empty

                    <p
                        class="text-sm"
                        style="color: var(--muted-foreground);"
                    >
                        Data dosen tidak ditemukan.
                    </p>

                @endforelse

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


        {{-- Kembali --}}
        <div class="pt-2">

            <a
                href="{{ route('admin.prestasi.dosen') }}"
                class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-medium"
                style="background: var(--secondary); color: var(--foreground);"
            >
                ← Kembali
            </a>

        </div>

    </div>

@endsection