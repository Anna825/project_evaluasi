@extends('layouts.mahasiswa')

@section('title', 'Tracer Study - Evaluasi PBM')

@section('page-title', 'Tracer Study Alumni')

@section('page-desc', 'Pelacakan karir dan rekam jejak alumni Politeknik Manufaktur Bandung.')

@section('content')

<div class="w-full">

    {{-- Header halaman --}}
    <!-- <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">

        <div>
            <h1 class="font-serif-display text-2xl md:text-3xl font-bold">
                Tracer Study Alumni
            </h1>

            <p
                class="text-sm mt-1"
                style="color: var(--muted-foreground);"
            >
                Lengkapi informasi mengenai perjalanan karier dan kondisi Anda setelah lulus.
            </p>
        </div>
    </div> -->

    <div class="mb-4 text left">
        <a href="{{ route('public.mahasiswa.menu', $mahasiswa->nim) }}"
        class="text-sm text-gray-600 underline hover:text-gray-900">
                ← Kembali ke Dashboard
        </a>
    </div>

    {{-- =========================================================
        CEK STATUS MAHASISWA
    ========================================================== --}}

    @if ($mahasiswa->status !== 'lulus')

        <div
            class="rounded-2xl border p-8 md:p-12 text-center w-full"
            style="background: var(--card); border-color: var(--border);"
        >

            <div
                class="w-16 h-16 rounded-2xl mx-auto flex items-center justify-center mb-5"
                style="background: var(--secondary); color: var(--primary);"
            >
                <svg
                    width="32"
                    height="32"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                    />
                </svg>
            </div>

            <h2 class="font-serif-display text-xl md:text-2xl font-bold mb-2">
                Tracer Study Belum Tersedia
            </h2>

            <p
                class="text-sm mb-6 max-w-xl mx-auto leading-relaxed"
                style="color: var(--muted-foreground);"
            >
                Layanan pengisian Tracer Study hanya diperuntukkan bagi mahasiswa
                yang telah menyelesaikan masa studi dan berstatus
                <strong>Lulus</strong>.

                Status akademik Anda saat ini:
                <span
                    class="capitalize font-semibold"
                    style="color: var(--primary);"
                >
                    {{ $mahasiswa->status }}
                </span>.
            </p>

            <a
                href="{{ route('public.mahasiswa.menu', $mahasiswa->nim) }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium text-white"
                style="background: var(--primary);"
            >
                ← Kembali ke Dashboard
            </a>

        </div>

    @else


        {{-- =========================================================
            DATA TRACER STUDY YANG SUDAH TERSIMPAN
        ========================================================== --}}

        @if ($tracerStudy)

            <div
                class="rounded-2xl border overflow-hidden mb-6 w-full"
                style="background: var(--card); border-color: var(--border);"
            >

                {{-- Header kartu --}}
                <div
                    class="px-6 py-5 border-b"
                    style="border-color: var(--border);"
                >

                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                        <div class="flex items-start gap-3">

                            <div
                                class="w-11 h-11 rounded-xl flex-shrink-0 flex items-center justify-center text-white"
                                style="background: var(--mhs);"
                            >
                                <svg
                                    width="22"
                                    height="22"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </div>

                            <div>
                                <h2 class="font-semibold">
                                    Data Tracer Study Anda Telah Tercatat
                                </h2>

                                <p
                                    class="text-xs mt-1"
                                    style="color: var(--muted-foreground);"
                                >
                                    Terakhir diperbarui:
                                    {{ $tracerStudy->updated_at
                                        ? $tracerStudy->updated_at->format('d M Y, H:i')
                                        : '-' }}
                                </p>
                            </div>

                        </div>

                        <button
                            type="button"
                            onclick="const form = document.getElementById('formTracer'); form.classList.remove('hidden'); form.scrollIntoView({behavior: 'smooth'});"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-sm font-medium border"
                            style="border-color: var(--primary); color: var(--primary);"
                        >
                            Perbarui Data
                        </button>

                    </div>

                </div>


                {{-- Ringkasan data --}}
                <div class="p-6 md:p-8">

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

                        <div
                            class="rounded-xl border p-4"
                            style="border-color: var(--border);"
                        >
                            <p
                                class="text-xs mb-1"
                                style="color: var(--muted-foreground);"
                            >
                                Periode Pelacakan
                            </p>

                            <p class="font-semibold">
                                {{ $tracerStudy->periode_pelacakan ?? '-' }}
                            </p>
                        </div>


                        <div
                            class="rounded-xl border p-4"
                            style="border-color: var(--border);"
                        >
                            <p
                                class="text-xs mb-1"
                                style="color: var(--muted-foreground);"
                            >
                                Status Saat Ini
                            </p>

                            <p class="font-semibold">
                                {{ $tracerStudy->status_utama ?? '-' }}
                            </p>
                        </div>


                        <div
                            class="rounded-xl border p-4"
                            style="border-color: var(--border);"
                        >
                            <p
                                class="text-xs mb-1"
                                style="color: var(--muted-foreground);"
                            >
                                Nama Instansi / Perusahaan
                            </p>

                            <p class="font-semibold">
                                {{ $tracerStudy->nama_instansi ?? '-' }}
                            </p>
                        </div>


                        <div
                            class="rounded-xl border p-4"
                            style="border-color: var(--border);"
                        >
                            <p
                                class="text-xs mb-1"
                                style="color: var(--muted-foreground);"
                            >
                                Kesesuaian Bidang
                            </p>

                            <p class="font-semibold">
                                {{ $tracerStudy->kesesuaian_bidang ?? '-' }}
                            </p>
                        </div>


                        <div
                            class="rounded-xl border p-4"
                            style="border-color: var(--border);"
                        >
                            <p
                                class="text-xs mb-1"
                                style="color: var(--muted-foreground);"
                            >
                                Rentang Pendapatan
                            </p>

                            <p class="font-semibold">
                                {{ $tracerStudy->rentang_pendapatan ?? '-' }}
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        @endif


        {{-- =========================================================
            FORM PENGISIAN / PEMBARUAN TRACER STUDY
        ========================================================== --}}

        <div
            id="formTracer"
            class="rounded-2xl border overflow-hidden w-full {{ $tracerStudy ? 'hidden' : '' }}"
            style="background: var(--card); border-color: var(--border);"
        >

            {{-- Header form --}}
            <div
                class="px-6 py-5 md:px-8 border-b"
                style="border-color: var(--border);"
            >

                <h2 class="font-serif-display text-xl md:text-2xl font-bold">
                    {{ $tracerStudy
                        ? 'Perbarui Data Tracer Study'
                        : 'Form Pengisian Tracer Study' }}
                </h2>

                <p
                    class="text-sm mt-1"
                    style="color: var(--muted-foreground);"
                >
                    {{ $tracerStudy
                        ? 'Perbarui informasi Tracer Study Anda jika terdapat perubahan.'
                        : 'Lengkapi data berikut sesuai kondisi Anda saat ini.' }}
                </p>

            </div>


            <div class="p-6 md:p-8">

                {{-- Validation error --}}
                @if ($errors->any())

                    <div
                        class="mb-6 px-4 py-4 rounded-xl text-sm"
                        style="background: #fdecea; color: #a13d3d;"
                    >

                        <p class="font-semibold mb-2">
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
                    action="{{ route('public.tracer.store', $mahasiswa->nim) }}"
                >
                    @csrf


                    {{-- Data utama --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Periode --}}
                        <div>

                            <label
                                for="periode_pelacakan"
                                class="block text-sm font-medium mb-1.5"
                            >
                                Periode Pelacakan (Tahun)
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                id="periode_pelacakan"
                                name="periode_pelacakan"
                                value="{{ old('periode_pelacakan', $tracerStudy->periode_pelacakan ?? date('Y')) }}"
                                placeholder="Contoh: {{ date('Y') }}"
                                class="w-full rounded-xl border px-4 py-3 text-sm"
                                style="border-color: var(--border);"
                                required
                            >

                        </div>


                        {{-- Status --}}
                        <div>

                            <label
                                for="status_utama"
                                class="block text-sm font-medium mb-1.5"
                            >
                                Status Saat Ini
                            </label>

                            <input
                                type="text"
                                id="status_utama"
                                name="status_utama"
                                value="{{ old('status_utama', $tracerStudy->status_utama ?? '') }}"
                                placeholder="Contoh: Bekerja, Wirausaha, Melanjutkan Studi"
                                class="w-full rounded-xl border px-4 py-3 text-sm"
                                style="border-color: var(--border);"
                            >

                        </div>


                        {{-- Instansi --}}
                        <div>

                            <label
                                for="nama_instansi"
                                class="block text-sm font-medium mb-1.5"
                            >
                                Nama Instansi / Perusahaan
                            </label>

                            <input
                                type="text"
                                id="nama_instansi"
                                name="nama_instansi"
                                value="{{ old('nama_instansi', $tracerStudy->nama_instansi ?? '') }}"
                                placeholder="Nama tempat kerja atau kampus lanjut studi"
                                class="w-full rounded-xl border px-4 py-3 text-sm"
                                style="border-color: var(--border);"
                            >

                        </div>


                        {{-- Kesesuaian bidang --}}
                        <div>

                            <label
                                for="kesesuaian_bidang"
                                class="block text-sm font-medium mb-1.5"
                            >
                                Kesesuaian Bidang Studi
                            </label>

                            @php
                                $currentKes = old(
                                    'kesesuaian_bidang',
                                    $tracerStudy->kesesuaian_bidang ?? ''
                                );
                            @endphp

                            <select
                                id="kesesuaian_bidang"
                                name="kesesuaian_bidang"
                                class="w-full rounded-xl border px-4 py-3 text-sm"
                                style="border-color: var(--border);"
                            >

                                <option value="">
                                    -- Pilih Kesesuaian --
                                </option>

                                <option
                                    value="Sangat Sesuai"
                                    {{ $currentKes == 'Sangat Sesuai' ? 'selected' : '' }}
                                >
                                    Sangat Sesuai
                                </option>

                                <option
                                    value="Sesuai"
                                    {{ $currentKes == 'Sesuai' ? 'selected' : '' }}
                                >
                                    Sesuai
                                </option>

                                <option
                                    value="Cukup Sesuai"
                                    {{ $currentKes == 'Cukup Sesuai' ? 'selected' : '' }}
                                >
                                    Cukup Sesuai
                                </option>

                                <option
                                    value="Kurang Sesuai"
                                    {{ $currentKes == 'Kurang Sesuai' ? 'selected' : '' }}
                                >
                                    Kurang Sesuai
                                </option>

                                <option
                                    value="Tidak Sesuai"
                                    {{ $currentKes == 'Tidak Sesuai' ? 'selected' : '' }}
                                >
                                    Tidak Sesuai
                                </option>

                            </select>

                        </div>


                        {{-- Pendapatan --}}
                        <div>

                            <label
                                for="rentang_pendapatan"
                                class="block text-sm font-medium mb-1.5"
                            >
                                Rentang Pendapatan Bulanan
                            </label>

                            @php
                                $currentPend = old(
                                    'rentang_pendapatan',
                                    $tracerStudy->rentang_pendapatan ?? ''
                                );
                            @endphp

                            <select
                                id="rentang_pendapatan"
                                name="rentang_pendapatan"
                                class="w-full rounded-xl border px-4 py-3 text-sm"
                                style="border-color: var(--border);"
                            >

                                <option value="">
                                    -- Pilih Rentang Pendapatan --
                                </option>

                                <option
                                    value="< Rp 3.000.000"
                                    {{ $currentPend == '< Rp 3.000.000' ? 'selected' : '' }}
                                >
                                    &lt; Rp 3.000.000
                                </option>

                                <option
                                    value="Rp 3.000.000 - Rp 5.000.000"
                                    {{ $currentPend == 'Rp 3.000.000 - Rp 5.000.000' ? 'selected' : '' }}
                                >
                                    Rp 3.000.000 - Rp 5.000.000
                                </option>

                                <option
                                    value="Rp 5.000.000 - Rp 10.000.000"
                                    {{ $currentPend == 'Rp 5.000.000 - Rp 10.000.000' ? 'selected' : '' }}
                                >
                                    Rp 5.000.000 - Rp 10.000.000
                                </option>

                                <option
                                    value="> Rp 10.000.000"
                                    {{ $currentPend == '> Rp 10.000.000' ? 'selected' : '' }}
                                >
                                    &gt; Rp 10.000.000
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- Tombol --}}
                    <div
                        class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-6 mt-6 border-t"
                        style="border-color: var(--border);"
                    >

                        <a
                            href="{{ route('public.mahasiswa.menu', $mahasiswa->nim) }}"
                            class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-medium"
                            style="background: var(--secondary); color: var(--foreground);"
                        >
                            Batal
                        </a>


                        <div class="flex flex-col sm:flex-row gap-3">

                            @if ($tracerStudy)

                                <button
                                    type="button"
                                    onclick="document.getElementById('formTracer').classList.add('hidden')"
                                    class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-medium border"
                                    style="border-color: var(--border); color: var(--foreground);"
                                >
                                    Tutup Form
                                </button>

                            @endif


                            <button
                                type="submit"
                                class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl text-sm font-semibold text-white hover:opacity-95 transition"
                                style="background: var(--mhs);"
                            >
                                {{ $tracerStudy
                                    ? 'Simpan Perubahan'
                                    : 'Kirim Tracer Study' }}
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    @endif

</div>

@endsection