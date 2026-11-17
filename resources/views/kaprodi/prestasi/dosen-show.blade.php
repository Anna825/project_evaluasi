@extends('layouts.app')

@section('title', 'Detail Prestasi Dosen - Kaprodi')
@section('page-title', 'Detail Prestasi Dosen')
@section('page-desc', 'Informasi lengkap prestasi dosen.')

@section('content')

<div class="w-full">

    <div class="mb-6">
        <x-back-button href="{{ route('kaprodi.prestasi.dosen') }}" label="Kembali ke Prestasi Dosen" />
    </div>

    <div class="rounded-2xl border overflow-hidden"
         style="background: var(--card); border-color: var(--border);">

        {{-- DATA DOSEN --}}
        <div class="px-6 py-5 border-b"
             style="border-color: var(--border);">

            <h2 class="font-serif-display text-2xl">
                Dosen Peraih Prestasi
            </h2>

        </div>

        <div class="p-6 md:p-8">

            @foreach ($prestasi->dosen as $dosen)

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

                    <div>
                        <p class="text-sm"
                           style="color: var(--muted-foreground);">
                            Nama Dosen
                        </p>

                        <p class="font-medium mt-1">
                            {{ $dosen->nama }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm"
                           style="color: var(--muted-foreground);">
                            NIDN
                        </p>

                        <p class="font-medium mt-1">
                            {{ $dosen->nidn ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm"
                           style="color: var(--muted-foreground);">
                            Program Studi
                        </p>

                        <p class="font-medium mt-1">
                            {{ $dosen->prodi->nama ?? '-' }}
                        </p>
                    </div>

                </div>

            @endforeach


            {{-- DATA PRESTASI --}}
            <div class="pt-6 border-t"
                 style="border-color: var(--border);">

                <h2 class="font-serif-display text-2xl mb-6">
                    Data Prestasi
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <p class="text-sm"
                           style="color: var(--muted-foreground);">
                            Nama Kegiatan
                        </p>

                        <p class="font-medium mt-1">
                            {{ $prestasi->nama_kegiatan }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm"
                           style="color: var(--muted-foreground);">
                            Tingkat
                        </p>

                        <p class="font-medium mt-1">
                            {{ $prestasi->tingkat ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm"
                           style="color: var(--muted-foreground);">
                            Jenis
                        </p>

                        <p class="font-medium mt-1">
                            {{ $prestasi->jenis ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm"
                           style="color: var(--muted-foreground);">
                            Peringkat / Capaian
                        </p>

                        <p class="font-medium mt-1">
                            {{ $prestasi->peringkat ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm"
                           style="color: var(--muted-foreground);">
                            Tempat Pelaksanaan
                        </p>

                        <p class="font-medium mt-1">
                            {{ $prestasi->tempat_pelaksanaan ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm"
                           style="color: var(--muted-foreground);">
                            Tanggal Penerimaan
                        </p>

                        <p class="font-medium mt-1">
                            {{ $prestasi->tanggal_penerimaan?->format('d-m-Y') ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm"
                           style="color: var(--muted-foreground);">
                            Tahun Akademik
                        </p>

                        <p class="font-medium mt-1">
                            {{ $prestasi->tahunAkademik->label ?? '-' }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- AKSI --}}
            <div class="flex pt-6 mt-6 border-t"
                 style="border-color: var(--border);">

                <x-back-button href="{{ route('kaprodi.prestasi.dosen') }}" label="Kembali" />

            </div>

        </div>

    </div>

</div>

@endsection