@extends('layouts.mahasiswa')

@section('title', 'Tambah Prestasi - Evaluasi PBM')

@section('page-title', 'Tambah Prestasi Mahasiswa')

@section('page-desc', 'Catat capaian prestasi akademik maupun non-akademik.')

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
                    Isi Data Prestasi
                </h2>

                <p
                    class="text-sm mt-1"
                    style="color: var(--muted-foreground);"
                >
                    Lengkapi informasi prestasi yang pernah Anda raih.
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
                    action="{{ route('public.prestasi.store', $mahasiswa->nim) }}"
                >
                    @csrf

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
                            value="{{ old('nama_kegiatan') }}"
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
                                value="{{ old('tingkat') }}"
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
                                value="{{ old('jenis') }}"
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
                                value="{{ old('peringkat') }}"
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
                                value="{{ old('tempat_pelaksanaan') }}"
                                placeholder="Contoh: Bandung / Jakarta / Online"
                                class="w-full rounded-xl border px-4 py-3 text-sm"
                                style="border-color: var(--border);"
                            >
                        </div>

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
                                    {{ old('tahun_akademik_id') == $ta->id ? 'selected' : '' }}
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
                            href="{{ route('public.prestasi.index', $mahasiswa->nim) }}"
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
                            Simpan Prestasi
                        </button>

                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection