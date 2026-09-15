@extends('layouts.app')

@section('title', 'Tambah Prestasi - Evaluasi PBM')
@section('page-title', 'Tambah Prestasi')
@section('page-desc', '')

@section('content')

    {{-- Kembali --}}
    <div class="mb-4 text-left">
        <a
            href="{{ route('prestasi-dosen.index') }}"
            class="text-sm underline hover:opacity-70"
            style="color: var(--muted-foreground);"
        >
            ← Kembali ke Prestasi
        </a>
    </div>

    <div
        class="w-full rounded-2xl border p-6 sm:p-8"
        style="background: var(--card); border-color: var(--border);"
    >

        {{-- Error Validasi --}}
        @if ($errors->any())
            <div
                class="mb-6 px-4 py-3 rounded-xl text-sm"
                style="background: #fdecea; color: #a13d3d;"
            >
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('prestasi-dosen.store') }}">
            @csrf

            {{-- Nama Kegiatan --}}
            <div class="mb-4">
                <label
                    for="nama_kegiatan"
                    class="block text-sm font-medium mb-1"
                >
                    Nama Kegiatan
                </label>

                <input
                    type="text"
                    name="nama_kegiatan"
                    id="nama_kegiatan"
                    value="{{ old('nama_kegiatan') }}"
                    class="w-full rounded-xl border px-3 py-2 text-sm"
                    style="border-color: var(--border);"
                    required
                >
            </div>

            {{-- Tingkat --}}
            <div class="mb-4">
                <label
                    for="tingkat"
                    class="block text-sm font-medium mb-1"
                >
                    Tingkat
                </label>

                <input
                    type="text"
                    name="tingkat"
                    id="tingkat"
                    value="{{ old('tingkat') }}"
                    placeholder="Lokal/Nasional/Internasional"
                    class="w-full rounded-xl border px-3 py-2 text-sm"
                    style="border-color: var(--border);"
                >
            </div>

            {{-- Jenis --}}
            <div class="mb-4">
                <label
                    for="jenis"
                    class="block text-sm font-medium mb-1"
                >
                    Jenis
                </label>

                <input
                    type="text"
                    name="jenis"
                    id="jenis"
                    value="{{ old('jenis') }}"
                    class="w-full rounded-xl border px-3 py-2 text-sm"
                    style="border-color: var(--border);"
                >
            </div>

            {{-- Peringkat --}}
            <div class="mb-4">
                <label
                    for="peringkat"
                    class="block text-sm font-medium mb-1"
                >
                    Peringkat / Capaian
                </label>

                <input
                    type="text"
                    name="peringkat"
                    id="peringkat"
                    value="{{ old('peringkat') }}"
                    class="w-full rounded-xl border px-3 py-2 text-sm"
                    style="border-color: var(--border);"
                >
            </div>

            {{-- Tempat Pelaksanaan --}}
            <div class="mb-4">
                <label
                    for="tempat_pelaksanaan"
                    class="block text-sm font-medium mb-1"
                >
                    Tempat Pelaksanaan
                </label>

                <input
                    type="text"
                    name="tempat_pelaksanaan"
                    id="tempat_pelaksanaan"
                    value="{{ old('tempat_pelaksanaan') }}"
                    class="w-full rounded-xl border px-3 py-2 text-sm"
                    style="border-color: var(--border);"
                >
            </div>

            {{-- Tanggal Penerimaan --}}
            <div class="mb-6">
                <label
                    for="tanggal_penerimaan"
                    class="block text-sm font-medium mb-1"
                >
                    Tanggal Penerimaan Penghargaan / Sertifikat
                </label>

                <input
                    type="date"
                    name="tanggal_penerimaan"
                    id="tanggal_penerimaan"
                    value="{{ old('tanggal_penerimaan') }}"
                    class="w-full rounded-xl border px-3 py-2 text-sm"
                    style="border-color: var(--border);"
                >
            </div>

            {{-- Tahun Akademik --}}
            <div class="mb-6">
                <label
                    for="tahun_akademik_id"
                    class="block text-sm font-medium mb-1"
                >
                    Tahun Akademik
                </label>

                <select
                    name="tahun_akademik_id"
                    id="tahun_akademik_id"
                    class="w-full rounded-xl border px-3 py-2 text-sm"
                    style="border-color: var(--border);"
                    required
                >
                    <option value="">-- Pilih --</option>

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
            <div class="flex gap-2">
                <button
                    type="submit"
                    class="px-4 py-2 rounded-xl text-sm font-medium text-white"
                    style="background: var(--primary);"
                >
                    Simpan
                </button>

                <a
                    href="{{ route('prestasi-dosen.index') }}"
                    class="px-4 py-2 rounded-xl text-sm font-medium"
                    style="background: var(--secondary); color: var(--foreground);"
                >
                    Batal
                </a>
            </div>

        </form>
    </div>

@endsection