@extends('layouts.mahasiswa')

@section('title', 'Detail Prestasi - Evaluasi PBM')

@section('page-title', 'Detail Prestasi Mahasiswa')

@section('page-desc', 'Informasi lengkap prestasi yang telah dicatat.')

@section('content')

    <div class="w-full">

        {{-- Header halaman --}}
        <div class="mb-6">
            <a
                href="{{ route('public.prestasi.index', $mahasiswa->nim) }}"
                class="inline-flex items-center gap-2 text-sm font-medium hover:underline"
                style="color: var(--primary);"
            >
                ← Kembali ke Prestasi Saya
            </a>
        </div>

        {{-- Detail Prestasi --}}
        <div
            class="rounded-2xl border overflow-hidden"
            style="background: var(--card); border-color: var(--border);"
        >

            {{-- Header --}}
            <div
                class="px-6 py-5 border-b"
                style="border-color: var(--border);"
            >
                <h2 class="font-serif-display text-2xl">
                    {{ $prestasi->nama_kegiatan }}
                </h2>

                <p
                    class="text-sm mt-1"
                    style="color: var(--muted-foreground);"
                >
                    Detail capaian prestasi mahasiswa
                </p>
            </div>

            <div class="p-6 md:p-8">

                {{-- Data Mahasiswa --}}
                <div class="mb-8">

                    <h3 class="font-semibold text-lg mb-4">
                        Data Mahasiswa
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>
                            <p
                                class="text-sm mb-1"
                                style="color: var(--muted-foreground);"
                            >
                                Nama Mahasiswa
                            </p>

                            <p class="font-medium">
                                {{ $mahasiswa->nama }}
                            </p>
                        </div>

                        <div>
                            <p
                                class="text-sm mb-1"
                                style="color: var(--muted-foreground);"
                            >
                                NIM
                            </p>

                            <p class="font-medium">
                                {{ $mahasiswa->nim }}
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
                                {{ $mahasiswa->prodi->nama ?? '-' }}
                            </p>
                        </div>

                    </div>

                </div>

                {{-- Data Prestasi --}}
                <div class="mb-8">

                    <h3 class="font-semibold text-lg mb-4">
                        Data Prestasi
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>
                            <p
                                class="text-sm mb-1"
                                style="color: var(--muted-foreground);"
                            >
                                Nama Kegiatan
                            </p>

                            <p class="font-medium">
                                {{ $prestasi->nama_kegiatan }}
                            </p>
                        </div>

                        <div>
                            <p
                                class="text-sm mb-1"
                                style="color: var(--muted-foreground);"
                            >
                                Tingkat
                            </p>

                            <p class="font-medium">
                                {{ $prestasi->tingkat ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p
                                class="text-sm mb-1"
                                style="color: var(--muted-foreground);"
                            >
                                Jenis
                            </p>

                            <p class="font-medium">
                                {{ $prestasi->jenis ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p
                                class="text-sm mb-1"
                                style="color: var(--muted-foreground);"
                            >
                                Peringkat / Capaian
                            </p>

                            <p class="font-medium">
                                {{ $prestasi->peringkat ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p
                                class="text-sm mb-1"
                                style="color: var(--muted-foreground);"
                            >
                                Tempat Pelaksanaan
                            </p>

                            <p class="font-medium">
                                {{ $prestasi->tempat_pelaksanaan ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p
                                class="text-sm mb-1"
                                style="color: var(--muted-foreground);"
                            >
                                Tanggal Penerimaan
                            </p>

                            <p class="font-medium">
                                @if ($prestasi->tanggal_penerimaan)
                                    {{ $prestasi->tanggal_penerimaan->format('d-m-Y') }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <div>
                            <p
                                class="text-sm mb-1"
                                style="color: var(--muted-foreground);"
                            >
                                Tahun Akademik
                            </p>

                            <p class="font-medium">
                                {{ $prestasi->tahunAkademik->label ?? '-' }}
                            </p>
                        </div>

                    </div>

                </div>

                {{-- Dosen Pembimbing --}}
                <div class="mb-8">

                    <h3 class="font-semibold text-lg mb-4">
                        Dosen Pembimbing
                    </h3>

                    @forelse ($prestasi->dosenPembimbing as $dosen)

                        <div
                            class="rounded-xl border p-4"
                            style="border-color: var(--border); background: var(--secondary);"
                        >
                            <p class="font-medium">
                                {{ $dosen->nama }}
                            </p>

                            @if ($dosen->nidn)
                                <p
                                    class="text-sm mt-1"
                                    style="color: var(--muted-foreground);"
                                >
                                    NIDN: {{ $dosen->nidn }}
                                </p>
                            @endif

                        </div>

                    @empty

                        <p
                            class="text-sm"
                            style="color: var(--muted-foreground);"
                        >
                            Tidak ada dosen pembimbing yang dicatat.
                        </p>

                    @endforelse

                </div>

                {{-- Tombol Aksi --}}
                <div
                    class="flex flex-col sm:flex-row gap-3 pt-5 border-t"
                    style="border-color: var(--border);"
                >

                    {{-- Kembali --}}
                    <a
                        href="{{ route('public.prestasi.index', $mahasiswa->nim) }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-medium"
                        style="background: var(--secondary); color: var(--foreground);"
                    >
                        ← Kembali
                    </a>

                    {{-- Edit --}}
                    <a
                        href="{{ route('public.prestasi.edit', [$mahasiswa->nim, $prestasi->id]) }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white hover:opacity-95 transition"
                        style="background: var(--primary);"
                    >
                        Edit Prestasi
                    </a>

                    {{-- Hapus --}}
                    <form
                        method="POST"
                        action="{{ route('public.prestasi.destroy', [$mahasiswa->nim, $prestasi->id]) }}"
                        onsubmit="return confirm('Yakin ingin menghapus prestasi ini? Data yang dihapus tidak dapat dikembalikan.');"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white hover:opacity-95 transition"
                            style="background: #dc2626;"
                        >
                            Hapus Prestasi
                        </button>
                    </form>

                </div>

            </div>
        </div>

    </div>

@endsection