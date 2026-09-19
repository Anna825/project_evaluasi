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
    <div class="grid grid-cols-1 md:grid-cols-1 gap-5 mb-6">

        {{-- Total Prestasi --}}
        <a href="{{ route('public.prestasi.index', $mahasiswa->nim) }}" class="group block rounded-2xl border p-5 card-hover" style="background: var(--card); border-color: var(--border);">
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
        </div>
    </div>
@endsection