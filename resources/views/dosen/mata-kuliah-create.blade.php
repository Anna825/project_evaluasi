@extends('layouts.app')

@section('title', 'Tambah Mata Kuliah - Evaluasi PBM')

@section('page-title', 'Tambah Mata Kuliah')

@section('page-desc', 'Tambahkan mata kuliah yang Anda ampu.')

@section('content')

<div class="max-w-3xl">

    <div
        class="rounded-2xl border p-6"
        style="background: var(--card); border-color: var(--border);"
    >

        <form action="{{ route('mata-kuliah.store') }}" method="POST">

            @csrf

            {{-- Menampilkan error validasi --}}
            @if ($errors->any())
                <div
                    class="mb-6 rounded-xl border p-4"
                    style="border-color: #ef4444; background: rgba(239, 68, 68, 0.08);"
                >
                    <p class="font-medium mb-2" style="color: #ef4444;">
                        Terdapat kesalahan pada data yang diinput:
                    </p>

                    <ul class="list-disc list-inside text-sm" style="color: #ef4444;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            {{-- Kurikulum --}}
            <div class="mb-5">

                <label
                    for="kurikulum_id"
                    class="block text-sm font-medium mb-2"
                >
                    Kurikulum
                </label>

                <select
                    name="kurikulum_id"
                    id="kurikulum_id"
                    class="w-full rounded-xl border px-4 py-3"
                    style="background: var(--background); border-color: var(--border);"
                    required
                >

                    <option value="">-- Pilih Kurikulum --</option>

                    @foreach ($kurikulumList as $kurikulum)
                        <option
                            value="{{ $kurikulum->id }}"
                            {{ old('kurikulum_id') == $kurikulum->id ? 'selected' : '' }}
                        >
                            {{ $kurikulum->nama ?? $kurikulum->kode ?? 'Kurikulum #' . $kurikulum->id }}
                        </option>
                    @endforeach

                </select>

            </div>


            {{-- Kode Mata Kuliah --}}
            <div class="mb-5">

                <label
                    for="kode"
                    class="block text-sm font-medium mb-2"
                >
                    Kode Mata Kuliah
                </label>

                <input
                    type="text"
                    name="kode"
                    id="kode"
                    value="{{ old('kode') }}"
                    placeholder="Contoh: TI301"
                    class="w-full rounded-xl border px-4 py-3"
                    style="background: var(--background); border-color: var(--border);"
                    required
                >

            </div>


            {{-- Nama Mata Kuliah --}}
            <div class="mb-5">

                <label
                    for="nama"
                    class="block text-sm font-medium mb-2"
                >
                    Nama Mata Kuliah
                </label>

                <input
                    type="text"
                    name="nama"
                    id="nama"
                    value="{{ old('nama') }}"
                    placeholder="Contoh: Pemrograman Web"
                    class="w-full rounded-xl border px-4 py-3"
                    style="background: var(--background); border-color: var(--border);"
                    required
                >

            </div>


            {{-- SKS --}}
            <div class="mb-5">

                <label
                    for="sks"
                    class="block text-sm font-medium mb-2"
                >
                    SKS
                </label>

                <input
                    type="number"
                    name="sks"
                    id="sks"
                    value="{{ old('sks') }}"
                    min="1"
                    max="10"
                    placeholder="Contoh: 3"
                    class="w-full rounded-xl border px-4 py-3"
                    style="background: var(--background); border-color: var(--border);"
                    required
                >

            </div>


            {{-- Semester Ke --}}
            <div class="mb-5">

                <label
                    for="semester_ke"
                    class="block text-sm font-medium mb-2"
                >
                    Semester Ke-
                </label>

                <input
                    type="number"
                    name="semester_ke"
                    id="semester_ke"
                    value="{{ old('semester_ke') }}"
                    min="1"
                    max="8"
                    placeholder="Contoh: 5"
                    class="w-full rounded-xl border px-4 py-3"
                    style="background: var(--background); border-color: var(--border);"
                    required
                >

            </div>


            {{-- Jenis --}}
            <div class="mb-5">

                <label
                    for="jenis"
                    class="block text-sm font-medium mb-2"
                >
                    Jenis Mata Kuliah
                </label>

                <select
                    name="jenis"
                    id="jenis"
                    class="w-full rounded-xl border px-4 py-3"
                    style="background: var(--background); border-color: var(--border);"
                >

                    <option value="">-- Pilih Jenis --</option>

                    <option
                        value="wajib"
                        {{ old('jenis') == 'wajib' ? 'selected' : '' }}
                    >
                        Wajib
                    </option>

                    <option
                        value="pilihan"
                        {{ old('jenis') == 'pilihan' ? 'selected' : '' }}
                    >
                        Pilihan
                    </option>

                </select>

            </div>


            {{-- Semester Akademik --}}
            <div class="mb-5">

                <label
                    for="semester_id"
                    class="block text-sm font-medium mb-2"
                >
                    Semester Akademik
                </label>

                <select
                    name="semester_id"
                    id="semester_id"
                    class="w-full rounded-xl border px-4 py-3"
                    style="background: var(--background); border-color: var(--border);"
                    required
                >

                    <option value="">-- Pilih Semester Akademik --</option>

                    @foreach ($semesterList as $semester)
                        <option
                            value="{{ $semester->id }}"
                            {{ old('semester_id') == $semester->id ? 'selected' : '' }}
                        >
                            {{ $semester->nama ?? 'Semester #' . $semester->id }}
                        </option>
                    @endforeach

                </select>

            </div>


            {{-- Nama Kelas --}}
            <div class="mb-6">

                <label
                    for="nama_kelas"
                    class="block text-sm font-medium mb-2"
                >
                    Nama Kelas
                </label>

                <input
                    type="text"
                    name="nama_kelas"
                    id="nama_kelas"
                    value="{{ old('nama_kelas') }}"
                    placeholder="Contoh: Kelas A"
                    class="w-full rounded-xl border px-4 py-3"
                    style="background: var(--background); border-color: var(--border);"
                    required
                >

            </div>


            {{-- Tombol --}}
            <div class="flex items-center gap-3">

                <a
                    href="{{ route('mata-kuliah.index') }}"
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
                    Simpan Mata Kuliah
                </button>

            </div>

        </form>

    </div>

</div>

@endsection