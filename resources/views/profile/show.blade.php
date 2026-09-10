@extends('layouts.app')

@section('title', 'Profil Saya - Evaluasi PBM')
@section('page-title', 'Profil Saya')
@section('page-desc', 'Informasi akun dan data pengguna.')

@section('content')

    <!-- {{-- Kembali --}}
    <div class="mb-4 text-left">
        <a
            href="{{ url()->previous() }}"
            class="text-sm underline hover:opacity-70"
            style="color: var(--muted-foreground);"
        >
            ← Kembali
        </a>
    </div> -->

    {{-- Header Profil --}}
    <div
        class="w-full rounded-2xl border overflow-hidden mb-6"
        style="background: var(--card); border-color: var(--border);"
    >
        <div class="p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center gap-5">

                {{-- Avatar --}}
                <div
                    class="w-16 h-16 rounded-full flex items-center justify-center shrink-0"
                    style="background: var(--secondary); color: var(--primary);"
                >
                    <span class="text-2xl font-semibold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                </div>

                {{-- Identitas --}}
                <div>
                    <h2 class="font-serif-display text-2xl">
                        {{ $user->name }}
                    </h2>

                    <p
                        class="text-sm mt-1"
                        style="color: var(--muted-foreground);"
                    >
                        {{ $user->email }}
                    </p>

                    {{-- Role --}}
                    <div class="flex flex-wrap gap-2 mt-3">
                        @forelse ($user->roles as $role)
                            <span
                                class="px-2.5 py-1 rounded-full text-xs font-medium"
                                style="background: var(--secondary); color: var(--primary);"
                            >
                                {{ ucfirst($role->nama_role) }}
                            </span>
                        @empty
                            <span
                                class="text-xs"
                                style="color: var(--muted-foreground);"
                            >
                                Role belum tersedia
                            </span>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Informasi Akun --}}
    <div
        class="w-full rounded-2xl border overflow-hidden mb-6"
        style="background: var(--card); border-color: var(--border);"
    >
        <div
            class="px-6 py-4 border-b"
            style="border-color: var(--border);"
        >
            <h2 class="font-serif-display text-lg">
                Informasi Akun
            </h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <p
                        class="text-xs mb-1"
                        style="color: var(--muted-foreground);"
                    >
                        Nama
                    </p>
                    <p class="font-medium">
                        {{ $user->name }}
                    </p>
                </div>

                <div>
                    <p
                        class="text-xs mb-1"
                        style="color: var(--muted-foreground);"
                    >
                        Email
                    </p>
                    <p class="font-medium">
                        {{ $user->email }}
                    </p>
                </div>

                <div>
                    <p
                        class="text-xs mb-1"
                        style="color: var(--muted-foreground);"
                    >
                        Status Akun
                    </p>
                    <p class="font-medium capitalize">
                        {{ $user->status }}
                    </p>
                </div>

                <div>
                    <p
                        class="text-xs mb-1"
                        style="color: var(--muted-foreground);"
                    >
                        Role
                    </p>
                    <p class="font-medium">
                        {{ $user->roles->pluck('nama_role')->map(fn ($role) => ucfirst($role))->join(', ') ?: '-' }}
                    </p>
                </div>

            </div>
        </div>
    </div>

    {{-- Data Dosen --}}
    @if ($user->dosen)
        <div
            class="w-full rounded-2xl border overflow-hidden mb-6"
            style="background: var(--card); border-color: var(--border);"
        >
            <div
                class="px-6 py-4 border-b"
                style="border-color: var(--border);"
            >
                <h2 class="font-serif-display text-lg">
                    Data Dosen
                </h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <p
                            class="text-xs mb-1"
                            style="color: var(--muted-foreground);"
                        >
                            NIDN
                        </p>
                        <p class="font-medium">
                            {{ $user->dosen->nidn ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p
                            class="text-xs mb-1"
                            style="color: var(--muted-foreground);"
                        >
                            Program Studi
                        </p>
                        <p class="font-medium">
                            {{ $user->dosen->prodi->nama ?? '-' }}
                        </p>
                    </div>

                </div>
            </div>
        </div>
    @endif

    {{-- Data Mahasiswa --}}
    @if ($user->mahasiswa)
        <div
            class="w-full rounded-2xl border overflow-hidden"
            style="background: var(--card); border-color: var(--border);"
        >
            <div
                class="px-6 py-4 border-b"
                style="border-color: var(--border);"
            >
                <h2 class="font-serif-display text-lg">
                    Data Mahasiswa
                </h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <p
                            class="text-xs mb-1"
                            style="color: var(--muted-foreground);"
                        >
                            NIM
                        </p>
                        <p class="font-medium">
                            {{ $user->mahasiswa->nim ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p
                            class="text-xs mb-1"
                            style="color: var(--muted-foreground);"
                        >
                            Program Studi
                        </p>
                        <p class="font-medium">
                            {{ $user->mahasiswa->prodi->nama ?? '-' }}
                        </p>
                    </div>

                </div>
            </div>
        </div>
    @endif

@endsection