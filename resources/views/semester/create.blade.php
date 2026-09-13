@extends('layouts.app')

@section('title', 'Tambah Semester - Evaluasi PBM')

@section('page-title', 'Tambah Semester')

@section('page-desc', 'Tambahkan semester akademik berdasarkan tahun akademik yang tersedia.')

@section('content')

<div class="w-full">

    {{-- Kembali --}}
    <div class="mb-4 text-left">
        <a
            href="{{ route('semester.index') }}"
            class="text-sm underline hover:opacity-70"
            style="color: var(--muted-foreground);"
        >
            ← Kembali ke Semester
        </a>
    </div>


    <div
        class="rounded-2xl border p-6"
        style="background: var(--card); border-color: var(--border);"
    >

        {{-- Error Validasi --}}
        @if ($errors->any())
            <div
                class="mb-6 rounded-xl border p-4"
                style="border-color: #ef4444; background: rgba(239, 68, 68, 0.08);"
            >
                <p
                    class="font-medium mb-2"
                    style="color: #ef4444;"
                >
                    Terdapat kesalahan pada data yang diinput:
                </p>

                <ul
                    class="list-disc list-inside text-sm"
                    style="color: #ef4444;"
                >
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form
            action="{{ route('semester.store') }}"
            method="POST"
        >

            @csrf


            {{-- Tahun Akademik --}}
            <div class="mb-5">

                <label
                    for="tahun_akademik_id"
                    class="block text-sm font-medium mb-2"
                >
                    Tahun Akademik
                </label>

                <select
                    name="tahun_akademik_id"
                    id="tahun_akademik_id"
                    class="w-full rounded-xl border px-4 py-3"
                    style="background: var(--background); border-color: var(--border);"
                    required
                >

                    <option value="">
                        -- Pilih Tahun Akademik --
                    </option>

                    @foreach ($tahunAkademikList as $tahunAkademik)

                        <option
                            value="{{ $tahunAkademik->id }}"
                            {{ old('tahun_akademik_id') == $tahunAkademik->id ? 'selected' : '' }}
                        >
                            {{ $tahunAkademik->label }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Jenis Semester --}}
            <div class="mb-5">

                <label
                    for="jenis"
                    class="block text-sm font-medium mb-2"
                >
                    Jenis Semester
                </label>

                <select
                    name="jenis"
                    id="jenis"
                    class="w-full rounded-xl border px-4 py-3"
                    style="background: var(--background); border-color: var(--border);"
                    required
                >

                    <option value="">
                        -- Pilih Jenis Semester --
                    </option>

                    <option
                        value="ganjil"
                        {{ old('jenis') === 'ganjil' ? 'selected' : '' }}
                    >
                        Ganjil
                    </option>

                    <option
                        value="genap"
                        {{ old('jenis') === 'genap' ? 'selected' : '' }}
                    >
                        Genap
                    </option>

                </select>

            </div>


            {{-- Tanggal Mulai --}}
            <div class="mb-5">

                <label
                    for="tanggal_mulai"
                    class="block text-sm font-medium mb-2"
                >
                    Tanggal Mulai
                </label>

                <input
                    type="date"
                    name="tanggal_mulai"
                    id="tanggal_mulai"
                    value="{{ old('tanggal_mulai') }}"
                    class="w-full rounded-xl border px-4 py-3"
                    style="background: var(--background); border-color: var(--border);"
                    required
                >

            </div>


            {{-- Tanggal Selesai --}}
            <div class="mb-6">

                <label
                    for="tanggal_selesai"
                    class="block text-sm font-medium mb-2"
                >
                    Tanggal Selesai
                </label>

                <input
                    type="date"
                    name="tanggal_selesai"
                    id="tanggal_selesai"
                    value="{{ old('tanggal_selesai') }}"
                    class="w-full rounded-xl border px-4 py-3"
                    style="background: var(--background); border-color: var(--border);"
                    required
                >

            </div>


            {{-- Tombol --}}
            <div class="flex items-center gap-3">

                <a
                    href="{{ route('semester.index') }}"
                    class="rounded-xl border px-5 py-3 text-sm font-medium"
                    style="border-color: var(--border);"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-xl px-5 py-3 text-sm font-medium text-white"
                    style="background: var(--primary);"
                >
                    Simpan Semester
                </button>

            </div>

        </form>

    </div>

</div>

@endsection