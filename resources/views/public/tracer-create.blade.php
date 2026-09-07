@extends('layouts.mahasiswa')

@section('title', 'Tracer Study - Evaluasi PBM')
@section('page-title', 'Tracer Study Alumni')
@section('page-desc', 'Pelacakan karir dan rekam jejak alumni Politeknik Manufaktur Bandung.')

@section('content')

    <h1 class="font-serif-display text-xl mb-6">
        Isi Tracer Study
    </h1>

    {{-- =========================================================
        CEK STATUS MAHASISWA
        Tracer Study hanya untuk mahasiswa yang sudah lulus
    ========================================================== --}}
    @if ($mahasiswa->status !== 'lulus')

        <div
            class="rounded-2xl border p-8 max-w-2xl text-center"
            style="background: var(--card); border-color: var(--border);"
        >
            <div
                class="w-16 h-16 rounded-2xl mx-auto flex items-center justify-center mb-4"
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

            <h2 class="font-serif-display text-xl font-bold mb-2">
                Tracer Study Belum Tersedia
            </h2>

            <p
                class="text-sm mb-6 max-w-md mx-auto"
                style="color: var(--muted-foreground);"
            >
                Layanan pengisian Tracer Study hanya diperuntukkan bagi
                mahasiswa yang telah menyelesaikan masa studi
                (berstatus <strong>Lulus</strong>).

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
                Kembali ke Dashboard
            </a>
        </div>

    @else

        {{-- =========================================================
            DATA TRACER STUDY YANG SUDAH TERSIMPAN
        ========================================================== --}}
        @if ($tracerStudy)

            <div
                class="rounded-2xl border p-6 max-w-2xl mb-6"
                style="background: var(--card); border-color: var(--border);"
            >
                <div
                    class="flex items-center justify-between pb-4 border-b mb-4"
                    style="border-color: var(--border);"
                >
                    <div class="flex items-center gap-3">

                        <div
                            class="w-10 h-10 rounded-xl flex items-center justify-center text-white"
                            style="background: var(--mhs);"
                        >
                            <svg
                                width="20"
                                height="20"
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
                            <p class="font-semibold text-sm">
                                Data Tracer Study Anda Telah Tercatat
                            </p>

                            <p
                                class="text-xs"
                                style="color: var(--muted-foreground);"
                            >
                                Terakhir disimpan:
                                {{ $tracerStudy->updated_at
                                    ? $tracerStudy->updated_at->format('d M Y, H:i')
                                    : '-' }}
                            </p>
                        </div>

                    </div>

                    <button
                        type="button"
                        onclick="const form = document.getElementById('formTracer'); form.classList.toggle('hidden'); form.scrollIntoView({behavior: 'smooth'});"
                        class="text-xs px-3 py-1.5 rounded-lg border font-medium hover:bg-gray-50 transition"
                        style="border-color: var(--primary); color: var(--primary);"
                    >
                        Perbarui Data
                    </button>
                </div>

                {{-- Ringkasan data tracer study --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                    <div>
                        <p
                            class="text-xs"
                            style="color: var(--muted-foreground);"
                        >
                            Periode Pelacakan
                        </p>

                        <p class="font-medium mono">
                            {{ $tracerStudy->periode_pelacakan ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p
                            class="text-xs"
                            style="color: var(--muted-foreground);"
                        >
                            Status Saat Ini
                        </p>

                        <p class="font-medium">
                            {{ $tracerStudy->status_utama ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p
                            class="text-xs"
                            style="color: var(--muted-foreground);"
                        >
                            Nama Instansi / Perusahaan
                        </p>

                        <p class="font-medium">
                            {{ $tracerStudy->nama_instansi ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p
                            class="text-xs"
                            style="color: var(--muted-foreground);"
                        >
                            Kesesuaian Bidang
                        </p>

                        <p class="font-medium">
                            {{ $tracerStudy->kesesuaian_bidang ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p
                            class="text-xs"
                            style="color: var(--muted-foreground);"
                        >
                            Rentang Pendapatan
                        </p>

                        <p class="font-medium">
                            {{ $tracerStudy->rentang_pendapatan ?? '-' }}
                        </p>
                    </div>

                </div>
            </div>

        @endif


        {{-- =========================================================
            FORM PENGISIAN / PEMBARUAN TRACER STUDY
        ========================================================== --}}

        <div
            id="formTracer"
            class="rounded-2xl border p-6 max-w-2xl {{ $tracerStudy ? 'hidden' : '' }}"
            style="background: var(--card); border-color: var(--border);"
        >

            <h2 class="font-serif-display text-lg font-bold mb-4">
                {{ $tracerStudy
                    ? 'Perbarui Data Tracer Study'
                    : 'Form Pengisian Tracer Study' }}
            </h2>


            {{-- =====================================================
                VALIDATION ERROR
            ====================================================== --}}
            @if ($errors->any())

                <div
                    class="mb-5 px-4 py-3 rounded-xl text-sm"
                    style="background: #fdecea; color: #a13d3d;"
                >
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>

            @endif


            {{-- =====================================================
                FORM TRACER STUDY
            ====================================================== --}}
            <form
                method="POST"
                action="{{ route('public.tracer.store', $mahasiswa->nim) }}"
            >
                @csrf


                {{-- Periode Pelacakan --}}
                <div class="mb-4">

                    <label class="block text-sm font-medium mb-1">
                        Periode Pelacakan (Tahun)
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="periode_pelacakan"
                        value="{{ old('periode_pelacakan', $tracerStudy->periode_pelacakan ?? date('Y')) }}"
                        placeholder="Contoh: {{ date('Y') }}"
                        class="w-full rounded-xl border px-3 py-2.5 text-sm"
                        style="border-color: var(--border);"
                        required
                    >

                </div>


                {{-- Status Saat Ini --}}
                <div class="mb-4">

                    <label class="block text-sm font-medium mb-1">
                        Status Saat Ini
                    </label>

                    <input
                        type="text"
                        name="status_utama"
                        value="{{ old('status_utama', $tracerStudy->status_utama ?? '') }}"
                        placeholder="Contoh: Bekerja, Wirausaha, Melanjutkan Studi"
                        class="w-full rounded-xl border px-3 py-2.5 text-sm"
                        style="border-color: var(--border);"
                    >

                </div>


                {{-- Nama Instansi --}}
                <div class="mb-4">

                    <label class="block text-sm font-medium mb-1">
                        Nama Instansi / Perusahaan
                    </label>

                    <input
                        type="text"
                        name="nama_instansi"
                        value="{{ old('nama_instansi', $tracerStudy->nama_instansi ?? '') }}"
                        placeholder="Nama tempat kerja atau kampus lanjut studi"
                        class="w-full rounded-xl border px-3 py-2.5 text-sm"
                        style="border-color: var(--border);"
                    >

                </div>


                {{-- Kesesuaian Bidang --}}
                <div class="mb-4">

                    <label class="block text-sm font-medium mb-1">
                        Kesesuaian Bidang Studi
                    </label>

                    @php
                        $currentKes = old(
                            'kesesuaian_bidang',
                            $tracerStudy->kesesuaian_bidang ?? ''
                        );
                    @endphp

                    <select
                        name="kesesuaian_bidang"
                        class="w-full rounded-xl border px-3 py-2.5 text-sm"
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


                {{-- Rentang Pendapatan --}}
                <div class="mb-6">

                    <label class="block text-sm font-medium mb-1">
                        Rentang Pendapatan Bulanan
                    </label>

                    @php
                        $currentPend = old(
                            'rentang_pendapatan',
                            $tracerStudy->rentang_pendapatan ?? ''
                        );
                    @endphp

                    <select
                        name="rentang_pendapatan"
                        class="w-full rounded-xl border px-3 py-2.5 text-sm"
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


                {{-- Tombol --}}
                <div class="flex gap-3">

                    <button
                        type="submit"
                        class="px-5 py-2.5 rounded-xl text-sm font-medium text-white hover:opacity-95 transition"
                        style="background: var(--mhs);"
                    >
                        {{ $tracerStudy
                            ? 'Simpan Perubahan'
                            : 'Kirim Tracer Study' }}
                    </button>


                    @if ($tracerStudy)

                        <button
                            type="button"
                            onclick="document.getElementById('formTracer').classList.add('hidden')"
                            class="px-4 py-2.5 rounded-xl text-sm font-medium border"
                            style="border-color: var(--border); color: var(--foreground);"
                        >
                            Tutup Form
                        </button>

                    @else

                        <a
                            href="{{ route('public.mahasiswa.menu', $mahasiswa->nim) }}"
                            class="px-4 py-2.5 rounded-xl text-sm font-medium"
                            style="background: var(--secondary); color: var(--foreground);"
                        >
                            Batal
                        </a>

                    @endif

                </div>

            </form>

        </div>

    @endif

@endsection