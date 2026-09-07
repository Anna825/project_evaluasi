@extends('layouts.mahasiswa')

@section('title', 'Dashboard Mahasiswa - Evaluasi PBM')

@section('page-title', 'Selamat Datang, ' . ($mahasiswa->nama ?? 'Mahasiswa'))

@section('page-desc')
    {{ $mahasiswa->prodi->nama ?? 'Program Studi belum tersedia' }}
    · Angkatan {{ $mahasiswa->angkatan ?? '-' }}
@endsection

@section('content')

    {{-- =========================
        RINGKASAN
    ========================== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

        {{-- Total Prestasi --}}
        <a
            href="{{ route('public.prestasi.index', $mahasiswa->nim) }}"
            class="group block rounded-2xl border p-5 card-hover"
            style="background: var(--card); border-color: var(--border);"
        >
            <div class="flex items-start justify-between gap-4">

                <div>
                    <p
                        class="text-xs font-semibold uppercase tracking-wider mb-2"
                        style="color: var(--muted-foreground);"
                    >
                        Total Prestasi
                    </p>

                    <p
                        class="text-3xl font-bold mono"
                        style="color: var(--mhs);"
                    >
                        {{ $prestasiCount }}
                    </p>

                    <p
                        class="text-xs mt-2"
                        style="color: var(--muted-foreground);"
                    >
                        Prestasi yang telah dicatat
                    </p>
                </div>

                <div
                    class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0"
                    style="background: rgba(46,125,50,.10); color: var(--mhs);"
                >
                    <svg
                        width="22"
                        height="22"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"
                        />
                    </svg>
                </div>

            </div>

            <div
                class="mt-4 text-xs font-medium group-hover:underline"
                style="color: var(--primary);"
            >
                Lihat prestasi →
            </div>
        </a>


        {{-- IPK --}}
        <a
            href="{{ route('public.profil', $mahasiswa->nim) }}"
            class="group block rounded-2xl border p-5 card-hover"
            style="background: var(--card); border-color: var(--border);"
        >
            <div class="flex items-start justify-between gap-4">

                <div>
                    <p
                        class="text-xs font-semibold uppercase tracking-wider mb-2"
                        style="color: var(--muted-foreground);"
                    >
                        IPK Terakhir
                    </p>

                    <p
                        class="text-3xl font-bold mono"
                        style="color: var(--accent);"
                    >
                        {{ $mahasiswa->ipk_terakhir ?? '-' }}
                    </p>

                    <p
                        class="text-xs mt-2"
                        style="color: var(--muted-foreground);"
                    >
                        Berdasarkan data akademik terakhir
                    </p>
                </div>

                <div
                    class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0"
                    style="background: rgba(184,149,42,.10); color: var(--accent);"
                >
                    <svg
                        width="22"
                        height="22"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 3l8 4.5v5c0 4.5-3.5 7.5-8 8.5-4.5-1-8-4-8-8.5v-5L12 3z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M8 10.5l4 2 4-2"
                        />
                    </svg>
                </div>

            </div>

            <div
                class="mt-4 text-xs font-medium group-hover:underline"
                style="color: var(--primary);"
            >
                Lihat profil →
            </div>
        </a>


        {{-- Tracer Study --}}
        @if ($mahasiswa->status === 'lulus')

            <a
                href="{{ route('public.tracer.create', $mahasiswa->nim) }}"
                class="group block rounded-2xl border p-5 card-hover"
                style="background: var(--card); border-color: var(--border);"
            >

        @else

            <div
                class="rounded-2xl border p-5"
                style="background: var(--card); border-color: var(--border);"
            >

        @endif

                <div class="flex items-start justify-between gap-4">

                    <div>
                        <p
                            class="text-xs font-semibold uppercase tracking-wider mb-2"
                            style="color: var(--muted-foreground);"
                        >
                            Tracer Study
                        </p>

                        @if ($mahasiswa->status !== 'lulus')

                            <p
                                class="text-lg font-semibold"
                                style="color: var(--muted-foreground);"
                            >
                                Belum tersedia
                            </p>

                            <p
                                class="text-xs mt-2"
                                style="color: var(--muted-foreground);"
                            >
                                Tersedia setelah mahasiswa berstatus lulus
                            </p>

                        @elseif ($tracerSudahIsi)

                            <p
                                class="text-lg font-semibold"
                                style="color: var(--mhs);"
                            >
                                Sudah diisi
                            </p>

                            <p
                                class="text-xs mt-2"
                                style="color: var(--muted-foreground);"
                            >
                                Data tracer study sudah tersimpan
                            </p>

                        @else

                            <p
                                class="text-lg font-semibold"
                                style="color: var(--accent);"
                            >
                                Belum diisi
                            </p>

                            <p
                                class="text-xs mt-2"
                                style="color: var(--muted-foreground);"
                            >
                                Silakan lengkapi data tracer study
                            </p>

                        @endif
                    </div>

                    <div
                        class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0"
                        style="background: rgba(26,58,92,.08); color: var(--primary);"
                    >
                        <svg
                            width="22"
                            height="22"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 12a9 9 0 1018 0 9 9 0 00-18 0z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 7v5l3 2"
                            />
                        </svg>
                    </div>

                </div>

                @if ($mahasiswa->status === 'lulus')
                    <div
                        class="mt-4 text-xs font-medium group-hover:underline"
                        style="color: var(--primary);"
                    >
                        {{ $tracerSudahIsi ? 'Lihat / perbarui tracer →' : 'Isi tracer study →' }}
                    </div>
                @endif

        @if ($mahasiswa->status === 'lulus')
            </a>
        @else
            </div>
        @endif

    </div>


    {{-- =========================
        INFORMASI AKADEMIK
    ========================== --}}
    <div
        class="rounded-2xl border overflow-hidden mb-6"
        style="background: var(--card); border-color: var(--border);"
    >

        <div
            class="px-6 py-5 border-b"
            style="border-color: var(--border);"
        >
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="font-serif-display text-xl">
                        Informasi Akademik
                    </h2>

                    <p
                        class="text-sm mt-1"
                        style="color: var(--muted-foreground);"
                    >
                        Informasi dasar akademik mahasiswa.
                    </p>
                </div>

                <a
                    href="{{ route('public.profil', $mahasiswa->nim) }}"
                    class="text-sm font-medium hover:underline"
                    style="color: var(--primary);"
                >
                    Lihat profil →
                </a>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            <div>
                <p
                    class="text-xs uppercase tracking-wider mb-1"
                    style="color: var(--muted-foreground);"
                >
                    Nama
                </p>

                <p class="font-semibold">
                    {{ $mahasiswa->nama ?? '-' }}
                </p>
            </div>

            <div>
                <p
                    class="text-xs uppercase tracking-wider mb-1"
                    style="color: var(--muted-foreground);"
                >
                    NIM
                </p>

                <p class="font-semibold mono">
                    {{ $mahasiswa->nim ?? '-' }}
                </p>
            </div>

            <div>
                <p
                    class="text-xs uppercase tracking-wider mb-1"
                    style="color: var(--muted-foreground);"
                >
                    Program Studi
                </p>

                <p class="font-semibold">
                    {{ $mahasiswa->prodi->nama ?? '-' }}
                </p>
            </div>

            <div>
                <p
                    class="text-xs uppercase tracking-wider mb-1"
                    style="color: var(--muted-foreground);"
                >
                    Angkatan
                </p>

                <p class="font-semibold">
                    {{ $mahasiswa->angkatan ?? '-' }}
                </p>
            </div>

            <div>
                <p
                    class="text-xs uppercase tracking-wider mb-1"
                    style="color: var(--muted-foreground);"
                >
                    Status Mahasiswa
                </p>

                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                    style="
                        background: var(--secondary);
                        color: var(--foreground);
                    "
                >
                    {{ ucfirst($mahasiswa->status ?? '-') }}
                </span>
            </div>

            <div>
                <p
                    class="text-xs uppercase tracking-wider mb-1"
                    style="color: var(--muted-foreground);"
                >
                    IPK Terakhir
                </p>

                <p class="font-semibold mono">
                    {{ $mahasiswa->ipk_terakhir ?? '-' }}
                </p>
            </div>

        </div>
    </div>


    {{-- =========================
        AKSI CEPAT
    ========================== --}}
    <div
        class="rounded-2xl border p-6"
        style="background: var(--card); border-color: var(--border);"
    >

        <div class="mb-4">
            <h2 class="font-serif-display text-xl">
                Aksi Cepat
            </h2>

            <p
                class="text-sm mt-1"
                style="color: var(--muted-foreground);"
            >
                Akses fitur yang paling sering digunakan.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">

            <a
                href="{{ route('public.prestasi.create', $mahasiswa->nim) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white hover:opacity-90 transition"
                style="background: var(--primary);"
            >
                <span>+</span>
                Tambah Prestasi
            </a>

            <a
                href="{{ route('public.prestasi.index', $mahasiswa->nim) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold hover:opacity-90 transition"
                style="
                    background: var(--secondary);
                    color: var(--foreground);
                "
            >
                Lihat Prestasi
            </a>

            @if ($mahasiswa->status === 'lulus' && !$tracerSudahIsi)

                <a
                    href="{{ route('public.tracer.create', $mahasiswa->nim) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white hover:opacity-90 transition"
                    style="background: var(--mhs);"
                >
                    Isi Tracer Study
                </a>

            @elseif ($mahasiswa->status === 'lulus' && $tracerSudahIsi)

                <a
                    href="{{ route('public.tracer.create', $mahasiswa->nim) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold hover:opacity-90 transition"
                    style="
                        background: rgba(46,125,50,.10);
                        color: var(--mhs);
                    "
                >
                    Perbarui Tracer Study
                </a>

            @endif

        </div>
    </div>

@endsection