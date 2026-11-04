@extends('layouts.app')

@section('title', 'Detail Penelitian/PKM - Evaluasi PBM')
@section('page-title', 'Detail Penelitian & PKM')
@section('page-desc', 'Tinjau informasi lengkap pengajuan penelitian atau PKM.')

@section('content')

    {{-- Kembali --}}
    <div class="mb-4 text-left">
        <a
            href="{{ route('penelitian-pkm.verifikasi') }}"
            class="text-sm underline hover:opacity-70"
            style="color: var(--muted-foreground);"
        >
            ← Kembali ke Verifikasi Penelitian & PKM
        </a>
    </div>


    {{-- Card Utama --}}
    <div
        class="w-full rounded-2xl border overflow-hidden"
        style="background: var(--card); border-color: var(--border);"
    >

        {{-- Header --}}
        <div
            class="p-6 border-b"
            style="border-color: var(--border);"
        >
            <div class="flex items-start justify-between gap-4">

                <div>
                    <p
                        class="text-xs font-medium uppercase tracking-wide mb-2"
                        style="color: var(--muted-foreground);"
                    >
                        {{ strtoupper($penelitianPkm->jenis ?? 'PENELITIAN / PKM') }}
                    </p>

                    <h2 class="text-xl font-semibold">
                        {{ $penelitianPkm->judul }}
                    </h2>
                </div>

                {{-- Status --}}
                <span
                    class="inline-flex shrink-0 px-3 py-1.5 rounded-full text-xs font-medium"
                    style="background: var(--secondary);"
                >
                    {{ ucfirst($penelitianPkm->status) }}
                </span>

            </div>
        </div>


        {{-- Isi Detail --}}
        <div class="p-6">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

                {{-- ================================================= --}}
                {{-- KOLOM KIRI --}}
                {{-- ================================================= --}}

                <div class="space-y-7">

                    {{-- Judul --}}
                    <div>
                        <p
                            class="text-xs mb-1.5"
                            style="color: var(--muted-foreground);"
                        >
                            Judul Penelitian/PKM
                        </p>

                        <p class="font-medium">
                            {{ $penelitianPkm->judul ?? '-' }}
                        </p>
                    </div>


                    {{-- Deskripsi Kegiatan --}}
                    <div>
                        <p class="text-xs mb-1.5" style="color: var(--muted-foreground);">Deskripsi Kegiatan</p>
                        <div class="rounded-xl border p-4 text-sm whitespace-pre-line"
                             style="background: var(--secondary); border-color: var(--border);">
                            {{ $penelitianPkm->deskripsi_kegiatan ?: 'Belum ada deskripsi kegiatan.' }}
                        </div>
                    </div>

                    {{-- Jenis --}}
                    <div>
                        <p
                            class="text-xs mb-1.5"
                            style="color: var(--muted-foreground);"
                        >
                            Jenis
                        </p>

                        <p class="font-medium">
                            {{ $penelitianPkm->jenis ?? '-' }}
                        </p>
                    </div>


                    {{-- Ketua --}}
                    <div>
                        <p
                            class="text-xs mb-1.5"
                            style="color: var(--muted-foreground);"
                        >
                            Ketua
                        </p>

                        @php
                            $ketua = $penelitianPkm->dosen->firstWhere('pivot.peran', 'Ketua');
                        @endphp

                        <p class="font-medium">
                            {{ $ketua?->nama ?? '-' }}
                        </p>
                    </div>


                    {{-- Anggota Tim --}}
                    <div>
                        <p
                            class="text-xs mb-2"
                            style="color: var(--muted-foreground);"
                        >
                            Anggota Tim
                        </p>

                        @php
                            $anggota = $penelitianPkm->dosen->filter(
                                fn ($dosen) => $dosen->pivot->peran === 'Anggota'
                            );
                        @endphp

                        @if ($anggota->count() > 0)

                            <div class="space-y-2">
                                @foreach ($anggota as $dosen)
                                    <div
                                        class="px-4 py-3 rounded-xl border"
                                        style="border-color: var(--border); background: var(--secondary);"
                                    >
                                        <span class="text-sm font-medium">
                                            {{ $dosen->nama }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                        @else

                            <p
                                class="text-sm"
                                style="color: var(--muted-foreground);"
                            >
                                Tidak ada anggota tambahan.
                            </p>

                        @endif
                    </div>


                    {{-- Tahun Akademik --}}
                    <div>
                        <p
                            class="text-xs mb-1.5"
                            style="color: var(--muted-foreground);"
                        >
                            Tahun Akademik
                        </p>

                        <p class="font-medium">
                            {{ $penelitianPkm->tahunAkademik?->label ?? '-' }}
                        </p>
                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- KOLOM KANAN --}}
                {{-- ================================================= --}}

                <div class="space-y-7">

                    {{-- Laporan Akhir --}}
                    <div>
                        <p
                            class="text-xs mb-2"
                            style="color: var(--muted-foreground);"
                        >
                            Laporan Akhir
                        </p>

                        @if (
                            $penelitianPkm->laporanAkhir &&
                            $penelitianPkm->laporanAkhir->link_laporan
                        )

                            <div
                                class="p-5 rounded-xl border"
                                style="border-color: var(--border); background: var(--secondary);"
                            >

                                <p class="font-medium mb-3">
                                    Sudah tersedia
                                </p>

                                <a
                                    href="{{ $penelitianPkm->laporanAkhir->link_laporan }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium border hover:opacity-80"
                                    style="border-color: var(--border); color: var(--primary);"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                        />
                                    </svg>

                                    Lihat Dokumen
                                </a>

                                @if ($penelitianPkm->laporanAkhir->tanggal_upload)

                                    <p
                                        class="text-xs mt-2"
                                        style="color: var(--muted-foreground);"
                                    >
                                        Diunggah:
                                        {{ \Carbon\Carbon::parse($penelitianPkm->laporanAkhir->tanggal_upload)->format('d/m/Y') }}
                                    </p>

                                @endif

                            </div>

                        @else

                            <div
                                class="p-5 rounded-xl border"
                                style="border-color: var(--border); background: var(--secondary);"
                            >
                                <span
                                    class="text-sm"
                                    style="color: var(--muted-foreground);"
                                >
                                    Belum tersedia
                                </span>
                            </div>

                        @endif
                    </div>


                    {{-- Hilirisasi --}}
                    <div>
                        <p
                            class="text-xs mb-2"
                            style="color: var(--muted-foreground);"
                        >
                            Hilirisasi
                        </p>

                        @if ($penelitianPkm->hilirisasi)

                            <div
                                class="p-5 rounded-xl border"
                                style="border-color: var(--border); background: var(--secondary);"
                            >

                                {{-- Bentuk Luaran --}}
                                <div class="mb-5">
                                    <p
                                        class="text-xs mb-1.5"
                                        style="color: var(--muted-foreground);"
                                    >
                                        Bentuk Luaran
                                    </p>

                                    <p class="font-medium">
                                        {{ $penelitianPkm->hilirisasi->bentuk_luaran ?? '-' }}
                                    </p>
                                </div>


                                {{-- Status Hilirisasi --}}
                                <div>
                                    <p
                                        class="text-xs mb-1.5"
                                        style="color: var(--muted-foreground);"
                                    >
                                        Status Hilirisasi
                                    </p>

                                    <span
                                        class="inline-flex px-3 py-1 rounded-full text-xs font-medium"
                                        style="background: var(--card);"
                                    >
                                        {{ ucfirst($penelitianPkm->hilirisasi->status_hilirisasi ?? '-') }}
                                    </span>
                                </div>

                            </div>

                        @else

                            <div
                                class="p-5 rounded-xl border"
                                style="border-color: var(--border); background: var(--secondary);"
                            >
                                <span
                                    class="text-sm"
                                    style="color: var(--muted-foreground);"
                                >
                                    Belum tersedia
                                </span>
                            </div>

                        @endif
                    </div>

                </div>

            </div>

        </div>


        @if ($penelitianPkm->status === 'ditolak' && $penelitianPkm->alasan_penolakan)
            <div class="mx-6 mb-6 rounded-xl border p-4"
                 style="background: #fef2f2; border-color: #fecaca; color: #991b1b;">
                <p class="font-semibold text-sm mb-1">Alasan Penolakan</p>
                <p class="text-sm whitespace-pre-line">{{ $penelitianPkm->alasan_penolakan }}</p>
            </div>
        @endif

        {{-- Footer Aksi --}}
        @if ($penelitianPkm->status === 'diajukan')
            <div class="p-6 border-t" style="border-color: var(--border);">
                <form method="POST"
                      action="{{ route('penelitian-pkm.reject', $penelitianPkm) }}"
                      class="w-full">
                    @csrf
                    @method('PATCH')

                    <label for="alasan_penolakan" class="block text-sm font-semibold mb-2">
                        Alasan Penolakan <span class="text-red-600">*</span>
                    </label>
                    <p class="text-xs mb-2" style="color: var(--muted-foreground);">
                        Jika pengajuan belum dapat disetujui, jelaskan bagian yang perlu diperbaiki oleh dosen pengusul.
                    </p>
                    <textarea id="alasan_penolakan" name="alasan_penolakan" rows="4"
                              maxlength="2000" minlength="5" required
                              placeholder="Tuliskan alasan penolakan secara jelas..."
                              class="w-full rounded-xl border px-3 py-2.5 text-sm"
                              style="border-color: var(--border);">{{ old('alasan_penolakan') }}</textarea>
                    @error('alasan_penolakan')
                        <p class="text-sm mt-1" style="color: #b91c1c;">{{ $message }}</p>
                    @enderror

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-4">
                        <span class="text-xs" style="color: var(--muted-foreground);">
                            Alasan wajib diisi apabila memilih Tolak Pengajuan.
                        </span>

                        <div class="flex items-center justify-end gap-3">
                            <button type="submit"
                                    class="px-5 py-2.5 rounded-lg text-sm font-medium text-white hover:opacity-90"
                                    style="background: #a13d3d;">
                                Tolak Pengajuan
                            </button>

                            <button type="submit"
                                    form="form-approve-penelitian"
                                    class="px-5 py-2.5 rounded-lg text-sm font-medium text-white hover:opacity-90"
                                    style="background: #2e7d32;">
                                Setujui Pengajuan
                            </button>
                        </div>
                    </div>
                </form>

                <form id="form-approve-penelitian" method="POST"
                      action="{{ route('penelitian-pkm.approve', $penelitianPkm) }}"
                      onsubmit="return confirm('Setujui pengajuan penelitian/PKM ini?');">
                    @csrf
                    @method('PATCH')
                </form>
            </div>
        @endif

    </div>

@endsection