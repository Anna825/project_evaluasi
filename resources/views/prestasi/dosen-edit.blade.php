@extends('layouts.app')

@section('title', 'Edit Prestasi Dosen - Evaluasi PBM')
@section('page-title', 'Edit Prestasi Dosen')
@section('page-desc', 'Perbarui data prestasi yang telah Anda catat.')

@section('content')

    <div class="w-full">

        <div class="mb-6">
            <a
                href="{{ route('prestasi-dosen.show', $prestasi->id) }}"
                class="inline-flex items-center gap-2 text-sm font-medium hover:underline"
                style="color: var(--primary);"
            >
                ← Kembali ke Detail Prestasi
            </a>
        </div>

        <div
            class="rounded-2xl border overflow-hidden"
            style="background: var(--card); border-color: var(--border);"
        >

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
                    Perbarui informasi prestasi Anda.
                </p>
            </div>

            <div class="p-6 md:p-8">

                @if ($errors->any())
                    <div
                        class="mb-6 px-4 py-3 rounded-xl text-sm"
                        style="background: #fdecea; color: #a13d3d;"
                    >
                        <p class="font-semibold mb-1">
                            Data belum dapat disimpan:
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
                    action="{{ route('prestasi-dosen.update', $prestasi->id) }}"
                >
                    @csrf
                    @method('PUT')

                    {{-- Nama Kegiatan --}}
                    <div class="mb-5">
                        <label
                            for="nama_kegiatan"
                            class="block text-sm font-medium mb-1.5"
                        >
                            Nama Kegiatan <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            id="nama_kegiatan"
                            name="nama_kegiatan"
                            value="{{ old('nama_kegiatan', $prestasi->nama_kegiatan) }}"
                            class="w-full rounded-xl border px-4 py-3 text-sm"
                            style="border-color: var(--border);"
                            required
                        >
                    </div>

                    {{-- Tingkat & Jenis --}}
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

                    {{-- Peringkat & Tempat --}}
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
                            id="tanggal_penerimaan"
                            name="tanggal_penerimaan"
                            value="{{ old(
                                'tanggal_penerimaan',
                                $prestasi->tanggal_penerimaan?->format('Y-m-d')
                            ) }}"
                            class="w-full rounded-xl border px-4 py-3 text-sm"
                            style="border-color: var(--border);"
                        >

                    </div>

                    {{-- Tahun Akademik --}}
                    <div class="mb-7">

                        <label
                            for="tahun_akademik_id"
                            class="block text-sm font-medium mb-1.5"
                        >
                            Tahun Akademik <span class="text-red-500">*</span>
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
                                    {{ old(
                                        'tahun_akademik_id',
                                        $prestasi->tahun_akademik_id
                                    ) == $ta->id ? 'selected' : '' }}
                                >
                                    {{ $ta->label }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- Tombol --}}
                    <div
                        class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-5 border-t"
                        style="border-color: var(--border);"
                    >

                        <a
                            href="{{ route('prestasi-dosen.show', $prestasi->id) }}"
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