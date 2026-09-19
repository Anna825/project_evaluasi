@extends('layouts.app')

@section('title', 'Tahun Akademik - Evaluasi PBM')

@section('page-title', 'Tahun Akademik')

@section('page-desc', 'Kelola tahun akademik dan periode semester yang digunakan dalam sistem.')

@section('content')

    {{-- =========================================================
        ALERT ERROR
    ========================================================== --}}
    @if ($errors->any())
        <div class="flex items-start gap-3 rounded-2xl p-4 mb-6 border text-sm"
             style="background: #fef2f2; border-color: #fecaca; color: #991b1b;">

            <svg class="w-5 h-5 shrink-0 mt-0.5 text-red-600"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>

            <div>
                <p class="font-semibold mb-1">Terjadi Kesalahan:</p>

                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif


    {{-- =========================================================
        ALERT BERHASIL
    ========================================================== --}}
    @if (session('status'))
        <div class="flex items-start gap-3 rounded-2xl p-4 mb-6 border text-sm"
             style="background: #f0fdf4; border-color: #bbf7d0; color: #166534;">

            <svg class="w-5 h-5 shrink-0 mt-0.5 text-emerald-600"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>

            <div>
                <p class="font-semibold">Berhasil!</p>
                <p>{{ session('status') }}</p>
            </div>
        </div>
    @endif


    {{-- =========================================================
        HEADER DAFTAR
    ========================================================== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">

        <div>
            <h2 class="text-xl font-semibold text-slate-900">
                Daftar Tahun Akademik
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Daftar tahun akademik yang telah dibuat.
            </p>
        </div>


        {{-- Tombol Tambah --}}
        <button
            type="button"
            onclick="document.getElementById('modalTambahTahunAkademik').showModal()"
            class="inline-flex items-center justify-center gap-2 rounded-xl
                   bg-[#193b63] px-5 py-3 text-sm font-semibold text-white
                   hover:bg-[#143250] transition"
        >

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-5 h-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 4v16m8-8H4" />
            </svg>

            Tambah Tahun Akademik
        </button>

    </div>


    {{-- =========================================================
        MODAL TAMBAH TAHUN AKADEMIK
    ========================================================== --}}
    <dialog
        id="modalTambahTahunAkademik"
        class="p-0 border-0 rounded-2xl
               w-[calc(100%-2rem)] max-w-3xl
               max-h-[90vh]
               m-auto
               overflow-hidden
               shadow-2xl"
        style="
            background: white;
        "
    >

        {{-- Isi Modal --}}
        <div class="flex flex-col max-h-[90vh]">


            {{-- =================================================
                HEADER MODAL
            ================================================== --}}
            <div class="flex items-center justify-between
                        px-6 py-5
                        border-b border-slate-200
                        shrink-0">

                <div>
                    <h3 class="text-xl font-semibold text-slate-900">
                        Tambah Tahun Akademik
                    </h3>

                    <p class="text-sm text-slate-500 mt-1">
                        Tambahkan tahun akademik beserta periode semester Ganjil dan Genap.
                    </p>
                </div>


                {{-- Tombol X --}}
                <button
                    type="button"
                    onclick="document.getElementById('modalTambahTahunAkademik').close()"
                    class="w-9 h-9 flex items-center justify-center
                           rounded-full
                           text-slate-500
                           hover:bg-slate-100
                           hover:text-slate-700
                           transition"
                    aria-label="Tutup"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-5 h-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

            </div>


            {{-- =================================================
                FORM
            ================================================== --}}
            <form
                method="POST"
                action="{{ route('tahun-akademik.store') }}"
                class="flex flex-col min-h-0"
            >

                @csrf


                {{-- Area Form Scroll --}}
                <div class="px-6 py-6 space-y-6 overflow-y-auto">


                    {{-- =============================================
                        TAHUN AKADEMIK
                    ============================================== --}}
                    <div>

                        <label
                            for="label"
                            class="block text-sm font-medium text-slate-900 mb-2"
                        >
                            Tahun Akademik
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="label"
                            id="label"
                            value="{{ old('label') }}"
                            placeholder="Contoh: 2027/2028"
                            required
                            class="w-full rounded-xl
                                   border border-slate-300
                                   bg-slate-50
                                   px-4 py-3
                                   text-slate-900
                                   placeholder:text-slate-400
                                   focus:border-blue-800
                                   focus:outline-none
                                   focus:ring-1
                                   focus:ring-blue-800
                                   @error('label') border-red-500 @enderror"
                        >

                        @error('label')
                            <p class="mt-1 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- =============================================
                        SEMESTER GANJIL
                    ============================================== --}}
                    <div class="rounded-xl border border-slate-200 p-5">

                        <h4 class="text-base font-semibold text-slate-900 mb-4">
                            Semester Ganjil
                        </h4>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">


                            {{-- Tanggal Mulai --}}
                            <div>

                                <label
                                    for="ganjil_mulai"
                                    class="block text-sm font-medium text-slate-900 mb-2"
                                >
                                    Tanggal Mulai
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="date"
                                    name="ganjil_mulai"
                                    id="ganjil_mulai"
                                    value="{{ old('ganjil_mulai') }}"
                                    required
                                    class="w-full rounded-xl
                                           border border-slate-300
                                           bg-slate-50
                                           px-4 py-3
                                           focus:border-blue-800
                                           focus:outline-none
                                           focus:ring-1
                                           focus:ring-blue-800
                                           @error('ganjil_mulai') border-red-500 @enderror"
                                >

                                @error('ganjil_mulai')
                                    <p class="mt-1 text-sm text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>


                            {{-- Tanggal Selesai --}}
                            <div>

                                <label
                                    for="ganjil_selesai"
                                    class="block text-sm font-medium text-slate-900 mb-2"
                                >
                                    Tanggal Selesai
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="date"
                                    name="ganjil_selesai"
                                    id="ganjil_selesai"
                                    value="{{ old('ganjil_selesai') }}"
                                    required
                                    class="w-full rounded-xl
                                           border border-slate-300
                                           bg-slate-50
                                           px-4 py-3
                                           focus:border-blue-800
                                           focus:outline-none
                                           focus:ring-1
                                           focus:ring-blue-800
                                           @error('ganjil_selesai') border-red-500 @enderror"
                                >

                                @error('ganjil_selesai')
                                    <p class="mt-1 text-sm text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- =============================================
                        SEMESTER GENAP
                    ============================================== --}}
                    <div class="rounded-xl border border-slate-200 p-5">

                        <h4 class="text-base font-semibold text-slate-900 mb-4">
                            Semester Genap
                        </h4>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">


                            {{-- Tanggal Mulai --}}
                            <div>

                                <label
                                    for="genap_mulai"
                                    class="block text-sm font-medium text-slate-900 mb-2"
                                >
                                    Tanggal Mulai
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="date"
                                    name="genap_mulai"
                                    id="genap_mulai"
                                    value="{{ old('genap_mulai') }}"
                                    required
                                    class="w-full rounded-xl
                                           border border-slate-300
                                           bg-slate-50
                                           px-4 py-3
                                           focus:border-blue-800
                                           focus:outline-none
                                           focus:ring-1
                                           focus:ring-blue-800
                                           @error('genap_mulai') border-red-500 @enderror"
                                >

                                @error('genap_mulai')
                                    <p class="mt-1 text-sm text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>


                            {{-- Tanggal Selesai --}}
                            <div>

                                <label
                                    for="genap_selesai"
                                    class="block text-sm font-medium text-slate-900 mb-2"
                                >
                                    Tanggal Selesai
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="date"
                                    name="genap_selesai"
                                    id="genap_selesai"
                                    value="{{ old('genap_selesai') }}"
                                    required
                                    class="w-full rounded-xl
                                           border border-slate-300
                                           bg-slate-50
                                           px-4 py-3
                                           focus:border-blue-800
                                           focus:outline-none
                                           focus:ring-1
                                           focus:ring-blue-800
                                           @error('genap_selesai') border-red-500 @enderror"
                                >

                                @error('genap_selesai')
                                    <p class="mt-1 text-sm text-red-500">
                                        {{ $message }}
                                    </p>
                                @enderror

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    FOOTER MODAL
                ================================================== --}}
                <div
                    class="flex justify-end items-center gap-3
                           px-6 py-4
                           border-t border-slate-200
                           bg-slate-50
                           shrink-0"
                >

                    {{-- Batal --}}
                    <button
                        type="button"
                        onclick="document.getElementById('modalTambahTahunAkademik').close()"
                        class="rounded-xl
                               border border-slate-300
                               bg-white
                               px-5 py-2.5
                               text-sm font-semibold
                               text-slate-700
                               hover:bg-slate-100
                               transition"
                    >
                        Batal
                    </button>


                    {{-- Simpan --}}
                    <button
                        type="submit"
                        class="rounded-xl
                               bg-[#193b63]
                               px-5 py-2.5
                               text-sm font-semibold
                               text-white
                               hover:bg-[#143250]
                               transition"
                    >
                        Simpan
                    </button>

                </div>

            </form>

        </div>


        {{-- =================================================
            BACKDROP / KLIK LUAR
        ================================================== --}}
        <script>
            document
                .getElementById('modalTambahTahunAkademik')
                .addEventListener('click', function (event) {

                    if (event.target === this) {
                        this.close();
                    }

                });
        </script>

    </dialog>


    {{-- =========================================================
        BUKA MODAL OTOMATIS JIKA VALIDASI GAGAL
    ========================================================== --}}
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const modal = document.getElementById('modalTambahTahunAkademik');

                if (modal) {
                    modal.showModal();
                }

            });
        </script>
    @endif


    {{-- =========================================================
        DAFTAR TAHUN AKADEMIK
    ========================================================== --}}
    <div class="w-full">

        <div
            class="rounded-2xl border overflow-hidden"
            style="background: var(--card); border-color: var(--border);"
        >

            {{-- Header Tabel --}}
            <div
                class="p-6 border-b"
                style="border-color: var(--border);"
            >

                <h2 class="font-serif-display text-lg font-bold">
                    Daftar Tahun Akademik
                </h2>

                <p
                    class="text-sm mt-1"
                    style="color: var(--muted-foreground);"
                >
                    Tahun akademik yang telah ditambahkan ke dalam sistem.
                </p>

            </div>


            {{-- Tabel --}}
            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <colgroup>
                        <col style="width: 80px;">
                        <col>
                        <col style="width: 160px;">
                    </colgroup>


                    <thead style="background: var(--secondary);">

                        <tr>

                            <th class="px-5 py-4 font-semibold">
                                No.
                            </th>

                            <th class="px-5 py-4 font-semibold">
                                Tahun Akademik
                            </th>

                            <th class="px-5 py-4 font-semibold text-center">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody
                        class="divide-y"
                        style="border-color: var(--border);"
                    >

                        @forelse ($tahunAkademik as $tahun)

                            <tr class="transition hover:bg-gray-50/50">

                                {{-- Nomor --}}
                                <td
                                    class="px-5 py-4"
                                    style="color: var(--muted-foreground);"
                                >
                                    {{ $loop->iteration }}
                                </td>


                                {{-- Tahun --}}
                                <td class="px-5 py-4">

                                    <span class="font-medium">
                                        {{ $tahun->label }}
                                    </span>

                                </td>


                                {{-- Aksi --}}
                                <td class="px-5 py-4">

                                    <div class="flex items-center justify-center">

                                        <form
                                            method="POST"
                                            action="{{ route('tahun-akademik.destroy', $tahun) }}"
                                            onsubmit="return confirm('Yakin ingin menghapus tahun akademik {{ $tahun->label }}?');"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center gap-1.5
                                                       px-3.5 py-2
                                                       rounded-lg
                                                       text-xs font-medium
                                                       transition
                                                       hover:opacity-80"
                                                style="background: #fee2e2; color: #b91c1c;"
                                            >

                                                <svg
                                                    width="15"
                                                    height="15"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-3a1 1 0 00-1 1v3M4 7h16"
                                                    />
                                                </svg>

                                                Hapus

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="3"
                                    class="px-5 py-10 text-center"
                                    style="color: var(--muted-foreground);"
                                >
                                    Belum ada tahun akademik yang ditambahkan.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection