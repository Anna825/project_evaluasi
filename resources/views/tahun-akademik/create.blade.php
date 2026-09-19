@extends('layouts.app')

@section('title', 'Tambah Tahun Akademik - Evaluasi PBM')
@section('page-title', 'Tambah Tahun Akademik')
@section('page-desc', 'Tambahkan tahun akademik beserta periode Semester Ganjil dan Genap.')

@section('content')

<div class="mb-4 text-left">
    <a href="{{ route('tahun-akademik.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium"
       style="background: var(--secondary); color: var(--foreground);">
        ← Kembali ke Tahun Akademik
    </a>
</div>

<div class="w-full">
    <div class="rounded-2xl border p-6"
         style="background: var(--card); border-color: var(--border);">

        @if ($errors->any())
            <div class="mb-5 px-4 py-3 rounded-xl text-sm"
                 style="background: #fdecea; color: #a13d3d;">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('tahun-akademik.store') }}">
            @csrf

            {{-- Tahun Akademik --}}
            <div class="mb-6">
                <label for="label"
                       class="block text-sm font-medium mb-1">
                    Tahun Akademik
                </label>

                <input
                    type="text"
                    name="label"
                    id="label"
                    value="{{ old('label') }}"
                    placeholder="Contoh: 2026/2027"
                    class="w-full rounded-xl border px-3 py-2 text-sm"
                    style="border-color: var(--border);"
                    required
                >
            </div>


            {{-- SEMESTER GANJIL --}}
            <div class="mb-6 rounded-2xl border p-5"
                 style="border-color: var(--border);">

                <h3 class="text-base font-semibold mb-4">
                    Semester Ganjil
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label for="ganjil_mulai"
                               class="block text-sm font-medium mb-1">
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            name="ganjil_mulai"
                            id="ganjil_mulai"
                            value="{{ old('ganjil_mulai') }}"
                            class="w-full rounded-xl border px-3 py-2 text-sm"
                            style="border-color: var(--border);"
                            required
                        >
                    </div>

                    <div>
                        <label for="ganjil_selesai"
                               class="block text-sm font-medium mb-1">
                            Tanggal Selesai
                        </label>

                        <input
                            type="date"
                            name="ganjil_selesai"
                            id="ganjil_selesai"
                            value="{{ old('ganjil_selesai') }}"
                            class="w-full rounded-xl border px-3 py-2 text-sm"
                            style="border-color: var(--border);"
                            required
                        >
                    </div>

                </div>
            </div>


            {{-- SEMESTER GENAP --}}
            <div class="mb-6 rounded-2xl border p-5"
                 style="border-color: var(--border);">

                <h3 class="text-base font-semibold mb-4">
                    Semester Genap
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label for="genap_mulai"
                               class="block text-sm font-medium mb-1">
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            name="genap_mulai"
                            id="genap_mulai"
                            value="{{ old('genap_mulai') }}"
                            class="w-full rounded-xl border px-3 py-2 text-sm"
                            style="border-color: var(--border);"
                            required
                        >
                    </div>

                    <div>
                        <label for="genap_selesai"
                               class="block text-sm font-medium mb-1">
                            Tanggal Selesai
                        </label>

                        <input
                            type="date"
                            name="genap_selesai"
                            id="genap_selesai"
                            value="{{ old('genap_selesai') }}"
                            class="w-full rounded-xl border px-3 py-2 text-sm"
                            style="border-color: var(--border);"
                            required
                        >
                    </div>

                </div>
            </div>


            {{-- BUTTON --}}
            <div class="flex gap-2">
                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl text-sm font-medium text-white hover:opacity-90"
                    style="background: var(--primary);">
                    Simpan
                </button>

                <a
                    href="{{ route('tahun-akademik.index') }}"
                    class="px-5 py-2.5 rounded-xl text-sm font-medium"
                    style="background: var(--secondary); color: var(--foreground);">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

@endsection