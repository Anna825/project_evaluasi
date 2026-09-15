@extends('layouts.mahasiswa')

@section('title', 'Edit Prestasi - Evaluasi PBM')

@section('page-title', 'Edit Prestasi Mahasiswa')

@section('page-desc', 'Perbarui informasi prestasi yang telah dicatat.')

@section('content')

    <div class="w-full">

        {{-- Header halaman --}}
        <div class="mb-6">
            <a
                href="{{ route('public.prestasi.show', [$mahasiswa->nim, $prestasi->id]) }}"
                class="inline-flex items-center gap-2 text-sm font-medium hover:underline"
                style="color: var(--primary);"
            >
                ← Kembali ke Detail Prestasi
            </a>
        </div>

        {{-- Form --}}
        <div
            class="rounded-2xl border overflow-hidden"
            style="background: var(--card); border-color: var(--border);"
        >

            {{-- Header form --}}
            <div
                class="px-6 py-5 border-b"
                style="border-color: var(--border);"
            >
                <h2 class="font-serif-display text-2xl">
                    Edit Data Prestasi
                </h2>

                <p
                    class="text-sm mt-1"
                    style="color: var(--muted-foreground);"
                >
                    Perbarui informasi prestasi yang telah Anda catat.
                </p>
            </div>

            <div class="p-6 md:p-8">

                {{-- Error --}}
                @if ($errors->any())
                    <div
                        class="mb-6 px-4 py-3 rounded-xl text-sm"
                        style="background: #fdecea; color: #a13d3d;"
                    >
                        <p class="font-semibold mb-1">
                            Data belum dapat diperbarui:
                        </p>

                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('public.prestasi.update', [$mahasiswa->nim, $prestasi->id]) }}"
                >
                    @csrf
                    @method('PUT')

                    {{-- Nama Kegiatan --}}
                    <div class="mb-5">
                        <label
                            for="nama_kegiatan"
                            class="block text-sm font-medium mb-1.5"
                        >
                            Nama Kegiatan
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            id="nama_kegiatan"
                            name="nama_kegiatan"
                            value="{{ old('nama_kegiatan', $prestasi->nama_kegiatan) }}"
                            placeholder="Contoh: Lomba Inovasi Teknologi Nasional"
                            class="w-full rounded-xl border px-4 py-3 text-sm"
                            style="border-color: var(--border);"
                            required
                        >
                    </div>

                    {{-- Tingkat + Jenis --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">

                        <div>
                            <label
                                for="tingkat"
                                class="block text-sm font-medium mb-1.5"
                            >
                                Tingkat
                            </label>

                            <input
                                type="text"
                                id="tingkat"
                                name="tingkat"
                                value="{{ old('tingkat', $prestasi->tingkat) }}"
                                placeholder="Lokal / Wilayah / Nasional / Internasional"
                                class="w-full rounded-xl border px-4 py-3 text-sm"
                                style="border-color: var(--border);"
                            >
                        </div>

                        <div>
                            <label
                                for="jenis"
                                class="block text-sm font-medium mb-1.5"
                            >
                                Jenis
                            </label>

                            <input
                                type="text"
                                id="jenis"
                                name="jenis"
                                value="{{ old('jenis', $prestasi->jenis) }}"
                                placeholder="Akademik / Non-Akademik"
                                class="w-full rounded-xl border px-4 py-3 text-sm"
                                style="border-color: var(--border);"
                            >
                        </div>

                    </div>

                    {{-- Peringkat + Tempat Pelaksanaan --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">

                        <div>
                            <label
                                for="peringkat"
                                class="block text-sm font-medium mb-1.5"
                            >
                                Peringkat / Capaian
                            </label>

                            <input
                                type="text"
                                id="peringkat"
                                name="peringkat"
                                value="{{ old('peringkat', $prestasi->peringkat) }}"
                                placeholder="Juara 1 / Finalis / Peserta"
                                class="w-full rounded-xl border px-4 py-3 text-sm"
                                style="border-color: var(--border);"
                            >
                        </div>

                        <div>
                            <label
                                for="tempat_pelaksanaan"
                                class="block text-sm font-medium mb-1.5"
                            >
                                Tempat Pelaksanaan
                            </label>

                            <input
                                type="text"
                                id="tempat_pelaksanaan"
                                name="tempat_pelaksanaan"
                                value="{{ old('tempat_pelaksanaan', $prestasi->tempat_pelaksanaan) }}"
                                placeholder="Contoh: Bandung / Jakarta / Online"
                                class="w-full rounded-xl border px-4 py-3 text-sm"
                                style="border-color: var(--border);"
                            >
                        </div>

                    </div>

                    {{-- Tanggal Penerimaan --}}
                    <div class="mb-5">
                        <label
                            for="tanggal_penerimaan"
                            class="block text-sm font-medium mb-1.5"
                        >
                            Tanggal Penerimaan Penghargaan / Sertifikat
                        </label>

                        <input
                            type="date"
                            name="tanggal_penerimaan"
                            id="tanggal_penerimaan"
                            value="{{ old('tanggal_penerimaan', optional($prestasi->tanggal_penerimaan)->format('Y-m-d')) }}"
                            class="w-full rounded-xl border px-4 py-3 text-sm"
                            style="border-color: var(--border);"
                        >

                        @error('tanggal_penerimaan')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Dosen Pembimbing --}}
                    <div class="mb-5">
                        <label
                            for="dosen_id"
                            class="block text-sm font-medium mb-1.5"
                        >
                            Dosen Pembimbing
                        </label>

                        @php
                            $dosenPembimbingId = $prestasi->dosenPembimbing->first()?->id;
                        @endphp

                        <select
                            name="dosen_id"
                            id="dosen_id"
                            class="w-full rounded-xl border px-4 py-3 text-sm"
                            style="border-color: var(--border);"
                        >
                            <option value="">
                                -- Tidak Ada / Pilih Dosen Pembimbing --
                            </option>

                            @foreach ($dosenList as $dosen)
                                <option
                                    value="{{ $dosen->id }}"
                                    {{ old('dosen_id', $dosenPembimbingId) == $dosen->id ? 'selected' : '' }}
                                >
                                    {{ $dosen->nama }}

                                    @if ($dosen->nidn)
                                        — {{ $dosen->nidn }}
                                    @endif
                                </option>
                            @endforeach
                        </select>

                        @error('dosen_id')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Tahun Akademik --}}
                    <div class="mb-7">
                        <label
                            for="tahun_akademik_id"
                            class="block text-sm font-medium mb-1.5"
                        >
                            Tahun Akademik
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            id="tahun_akademik_id"
                            name="tahun_akademik_id"
                            class="w-full rounded-xl border px-4 py-3 text-sm"
                            style="border-color: var(--border);"
                            required
                        >
                            <option value="">
                                -- Pilih Tahun Akademik --
                            </option>

                            @foreach ($tahunAkademikList as $ta)
                                <option
                                    value="{{ $ta->id }}"
                                    {{ old('tahun_akademik_id', $prestasi->tahun_akademik_id) == $ta->id ? 'selected' : '' }}
                                >
                                    {{ $ta->label }}
                                </option>
                            @endforeach
                        </select>

                        @error('tahun_akademik_id')
                            <p class="mt-1 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Tombol --}}
                    <div
                        class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-5 border-t"
                        style="border-color: var(--border);"
                    >

                        <a
                            href="{{ route('public.prestasi.show', [$mahasiswa->nim, $prestasi->id]) }}"
                            class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-medium"
                            style="background: var(--secondary); color: var(--foreground);"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl text-sm font-semibold text-white hover:opacity-95 transition"
                            style="background: var(--primary);"
                        >
                            Simpan Perubahan
                        </button>

                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection