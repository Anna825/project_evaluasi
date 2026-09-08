@extends('layouts.app')

@section('title', 'Detail Dosen - Evaluasi PBM')
@section('page-title', 'Detail Pendaftaran Dosen')
@section('page-desc', 'Informasi lengkap akun dan data dosen yang terdaftar.')

@section('content')

    {{-- Kembali --}}
    <div class="mb-4 text-left">
        <a
            href="{{ route('admin.users.index') }}"
            class="text-sm underline hover:opacity-70"
            style="color: var(--muted-foreground);"
        >
            ← Kembali ke Akun Dosen
        </a>
    </div>

    {{-- Card utama --}}
    <div
        class="w-full rounded-2xl border overflow-hidden"
        style="background: var(--card); border-color: var(--border);"
    >

        {{-- Header --}}
        <div
            class="px-6 py-5 md:px-8 border-b"
            style="border-color: var(--border);"
        >
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <div>
                    <h2 class="font-serif-display text-xl md:text-2xl font-bold">
                        {{ $user->name }}
                    </h2>

                    <p
                        class="text-sm mt-1"
                        style="color: var(--muted-foreground);"
                    >
                        Detail data pendaftaran dosen
                    </p>
                </div>

                {{-- Status --}}
                <div>
                    @if ($user->status === 'pending')
                        <span
                            class="inline-flex px-3 py-1.5 rounded-full text-xs font-semibold"
                            style="background: #fef3d9; color: #8a6816;"
                        >
                            Pending
                        </span>
                    @elseif ($user->status === 'aktif')
                        <span
                            class="inline-flex px-3 py-1.5 rounded-full text-xs font-semibold"
                            style="background: #e3f2e6; color: #2e7d32;"
                        >
                            Aktif
                        </span>
                    @else
                        <span
                            class="inline-flex px-3 py-1.5 rounded-full text-xs font-semibold"
                            style="background: #fdecea; color: #a13d3d;"
                        >
                            Nonaktif
                        </span>
                    @endif
                </div>

            </div>
        </div>


        {{-- Isi --}}
        <div class="p-6 md:p-8">

            {{-- Informasi Akun --}}
            <div class="mb-8">

                <h3 class="font-semibold text-base mb-4">
                    Informasi Akun
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <p
                            class="text-xs mb-1"
                            style="color: var(--muted-foreground);"
                        >
                            Nama Akun
                        </p>
                        <p class="font-medium">
                            {{ $user->name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p
                            class="text-xs mb-1"
                            style="color: var(--muted-foreground);"
                        >
                            Email
                        </p>
                        <p class="font-medium break-all">
                            {{ $user->email ?? '-' }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- Informasi Dosen --}}
            <div>

                <h3 class="font-semibold text-base mb-4">
                    Informasi Dosen
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

                    {{-- NIDN --}}
                    <div
                        class="rounded-xl border p-4"
                        style="border-color: var(--border);"
                    >
                        <p
                            class="text-xs mb-1"
                            style="color: var(--muted-foreground);"
                        >
                            NIDN
                        </p>

                        <p class="font-semibold">
                            {{ $user->dosen->nidn ?? '-' }}
                        </p>
                    </div>


                    {{-- Nama --}}
                    <div
                        class="rounded-xl border p-4"
                        style="border-color: var(--border);"
                    >
                        <p
                            class="text-xs mb-1"
                            style="color: var(--muted-foreground);"
                        >
                            Nama Lengkap
                        </p>

                        <p class="font-semibold">
                            {{ $user->dosen->nama ?? $user->name ?? '-' }}
                        </p>
                    </div>


                    {{-- Program Studi --}}
                    <div
                        class="rounded-xl border p-4"
                        style="border-color: var(--border);"
                    >
                        <p
                            class="text-xs mb-1"
                            style="color: var(--muted-foreground);"
                        >
                            Program Studi
                        </p>

                        <p class="font-semibold">
                            {{ $user->dosen->prodi->nama ?? '-' }}
                        </p>
                    </div>


                    {{-- Jenis Kelamin --}}
                    <div
                        class="rounded-xl border p-4"
                        style="border-color: var(--border);"
                    >
                        <p
                            class="text-xs mb-1"
                            style="color: var(--muted-foreground);"
                        >
                            Jenis Kelamin
                        </p>

                        <p class="font-semibold">
                            {{ $user->dosen->jenis_kelamin ?? '-' }}
                        </p>
                    </div>


                    {{-- Jabatan --}}
                    <div
                        class="rounded-xl border p-4"
                        style="border-color: var(--border);"
                    >
                        <p
                            class="text-xs mb-1"
                            style="color: var(--muted-foreground);"
                        >
                            Jabatan Fungsional
                        </p>

                        <p class="font-semibold">
                            {{ $user->dosen->jabatan_fungsional ?? '-' }}
                        </p>
                    </div>


                    {{-- Pendidikan --}}
                    <div
                        class="rounded-xl border p-4"
                        style="border-color: var(--border);"
                    >
                        <p
                            class="text-xs mb-1"
                            style="color: var(--muted-foreground);"
                        >
                            Pendidikan Terakhir
                        </p>

                        <p class="font-semibold">
                            {{ $user->dosen->pendidikan_terakhir ?? '-' }}
                        </p>
                    </div>


                    {{-- Institusi --}}
                    <div
                        class="rounded-xl border p-4"
                        style="border-color: var(--border);"
                    >
                        <p
                            class="text-xs mb-1"
                            style="color: var(--muted-foreground);"
                        >
                            Institusi Lulusan
                        </p>

                        <p class="font-semibold">
                            {{ $user->dosen->institusi_lulusan ?? '-' }}
                        </p>
                    </div>


                    {{-- No HP --}}
                    <div
                        class="rounded-xl border p-4"
                        style="border-color: var(--border);"
                    >
                        <p
                            class="text-xs mb-1"
                            style="color: var(--muted-foreground);"
                        >
                            Nomor HP
                        </p>

                        <p class="font-semibold">
                            {{ $user->dosen->no_hp ?? '-' }}
                        </p>
                    </div>

                </div>

            </div>

        </div>


        {{-- Footer Aksi --}}
        <div
            class="px-6 py-5 md:px-8 border-t flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
            style="border-color: var(--border);"
        >

            <a
                href="{{ route('admin.users.index') }}"
                class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-medium"
                style="background: var(--secondary); color: var(--foreground);"
            >
                Kembali
            </a>

            <div class="flex flex-col sm:flex-row gap-3">

                @if ($user->status !== 'aktif')
                    <form
                        method="POST"
                        action="{{ route('admin.users.activate', $user) }}"
                    >
                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white hover:opacity-95 transition"
                            style="background: #2e7d32;"
                        >
                            Aktifkan Akun
                        </button>
                    </form>
                @endif

                @if ($user->status !== 'nonaktif')
                    <form
                        method="POST"
                        action="{{ route('admin.users.deactivate', $user) }}"
                    >
                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white hover:opacity-95 transition"
                            style="background: #a13d3d;"
                        >
                            Nonaktifkan
                        </button>
                    </form>
                @endif

            </div>

        </div>

    </div>

@endsection