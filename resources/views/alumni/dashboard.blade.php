@extends('layouts.app')

@section('title', 'Dashboard Alumni - Evaluasi PBM')
@section('page-title', 'Selamat Datang, ' . ($mahasiswa->nama ?? 'Alumni'))

@section('page-desc')
    {{ $mahasiswa->prodi->nama ?? 'Program Studi belum tersedia' }}
    · Alumni
@endsection

@section('content')

    {{-- =========================
        TRACER STUDY
    ========================== --}}
    <div class="mb-6">
        <a
            href="{{ route('alumni.tracer.index') }}"
            class="group block rounded-2xl border p-6 card-hover"
            style="background: var(--card); border-color: var(--border);"
        >
            <div class="flex items-start justify-between gap-5">

                <div>
                    <p
                        class="text-xs font-semibold uppercase tracking-wider mb-2"
                        style="color: var(--muted-foreground);"
                    >
                        Tracer Study
                    </p>

                    @if ($tracerCount > 0)
                        <p
                            class="text-xl font-semibold"
                            style="color: var(--mhs);"
                        >
                            Data tracer sudah tersedia
                        </p>

                        <p
                            class="text-sm mt-2"
                            style="color: var(--muted-foreground);"
                        >
                            {{ $tracerCount }} pengisian telah tersimpan dalam riwayat tracer study.
                        </p>
                    @else
                        <p
                            class="text-xl font-semibold"
                            style="color: var(--accent);"
                        >
                            Belum mengisi Tracer Study
                        </p>

                        <p
                            class="text-sm mt-2"
                            style="color: var(--muted-foreground);"
                        >
                            Silakan lengkapi data tracer study Anda.
                        </p>
                    @endif
                </div>

                <div
                    class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0"
                    style="background: rgba(26,58,92,.08); color: var(--primary);"
                >
                    <svg
                        width="24"
                        height="24"
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

            <div
                class="mt-5 text-sm font-medium group-hover:underline"
                style="color: var(--primary);"
            >
                {{ $tracerCount > 0 ? 'Lihat / perbarui tracer study →' : 'Isi tracer study →' }}
            </div>
        </a>
    </div>


    {{-- =========================
        RINGKASAN
    ========================== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">

        {{-- Status Alumni --}}
        <div
            class="rounded-2xl border p-5"
            style="background: var(--card); border-color: var(--border);"
        >
            <p
                class="text-xs font-semibold uppercase tracking-wider mb-2"
                style="color: var(--muted-foreground);"
            >
                Status
            </p>

            <p
                class="text-3xl font-bold"
                style="color: var(--primary);"
            >
                Alumni
            </p>

            <p
                class="text-xs mt-2"
                style="color: var(--muted-foreground);"
            >
                Akun Alumni aktif
            </p>
        </div>


        {{-- Pengisian Terakhir --}}
        <div
            class="rounded-2xl border p-5"
            style="background: var(--card); border-color: var(--border);"
        >
            <p
                class="text-xs font-semibold uppercase tracking-wider mb-2"
                style="color: var(--muted-foreground);"
            >
                Pengisian Terakhir
            </p>

            @if ($tracerTerakhir)
                <p
                    class="text-xl font-bold"
                    style="color: var(--primary);"
                >
                    {{ $tracerTerakhir->tanggal_pengisian?->format('d M Y') ?? '-' }}
                </p>

                <p
                    class="text-xs mt-2"
                    style="color: var(--muted-foreground);"
                >
                    {{ ucfirst(str_replace('_', ' ', $tracerTerakhir->jenis_pengisian)) }}
                </p>
            @else
                <p
                    class="text-xl font-semibold"
                    style="color: var(--muted-foreground);"
                >
                    Belum ada data
                </p>

                <p
                    class="text-xs mt-2"
                    style="color: var(--muted-foreground);"
                >
                    Belum pernah melakukan pengisian tracer.
                </p>
            @endif
        </div>

    </div>


    {{-- =========================
        INFORMASI ALUMNI
    ========================== --}}
    <div
        class="rounded-2xl border overflow-hidden"
        style="background: var(--card); border-color: var(--border);"
    >

        <div
            class="px-6 py-5 border-b"
            style="border-color: var(--border);"
        >
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="font-serif-display text-xl">
                        Informasi Alumni
                    </h2>
                </div>

                <a
                    href="{{ route('profile.show') }}"
                    class="text-sm font-medium hover:underline"
                    style="color: var(--primary);"
                >
                    Lihat profil →
                </a>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

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

        </div>
    </div>

@endsection