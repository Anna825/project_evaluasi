@extends('layouts.app')

@section('title', 'Detail Akun Alumni - Evaluasi PBM')
@section('page-title', 'Detail Akun Alumni')
@section('page-desc', 'Informasi akun dan data alumni yang terdaftar.')

@section('content')
    @php
        $alumniRole = $user->roles->firstWhere('nama_role', 'alumni');

        $mahasiswa = $alumniRole?->pivot?->mahasiswa_id
            ? $user->userRoles
                ->firstWhere('mahasiswa_id', $alumniRole->pivot->mahasiswa_id)
                ?->mahasiswa
            : null;
    @endphp

    <div class="space-y-6">

        {{-- Informasi Akun --}}
        <div
            class="rounded-2xl border p-6"
            style="background: var(--card); border-color: var(--border);"
        >
            <h2 class="text-lg font-semibold mb-5">
                Informasi Akun
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <p
                        class="text-sm mb-1"
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
                        class="text-sm mb-1"
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
                        class="text-sm mb-1"
                        style="color: var(--muted-foreground);"
                    >
                        Status Akun
                    </p>

                    @if ($user->status === 'pending')
                        <span
                            class="px-2 py-1 rounded-full text-xs font-medium"
                            style="background: #fef3d9; color: #8a6816;"
                        >
                            Pending
                        </span>
                    @elseif ($user->status === 'aktif')
                        <span
                            class="px-2 py-1 rounded-full text-xs font-medium"
                            style="background: #e3f2e6; color: #2e7d32;"
                        >
                            Aktif
                        </span>
                    @else
                        <span
                            class="px-2 py-1 rounded-full text-xs font-medium"
                            style="background: #fdecea; color: #a13d3d;"
                        >
                            Nonaktif
                        </span>
                    @endif
                </div>

            </div>
        </div>


        {{-- Data Alumni --}}
        <div
            class="rounded-2xl border p-6"
            style="background: var(--card); border-color: var(--border);"
        >
            <h2 class="text-lg font-semibold mb-5">
                Data Alumni
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <p
                        class="text-sm mb-1"
                        style="color: var(--muted-foreground);"
                    >
                        NIM
                    </p>
                    <p class="font-medium">
                        {{ $mahasiswa->nim ?? '-' }}
                    </p>
                </div>

                <div>
                    <p
                        class="text-sm mb-1"
                        style="color: var(--muted-foreground);"
                    >
                        Program Studi
                    </p>
                    <p class="font-medium">
                        {{ $mahasiswa->prodi->nama ?? '-' }}
                    </p>
                </div>

                <div>
                    <p
                        class="text-sm mb-1"
                        style="color: var(--muted-foreground);"
                    >
                        Status Mahasiswa
                    </p>
                    <p class="font-medium">
                        {{ ucfirst($mahasiswa->status ?? '-') }}
                    </p>
                </div>

                <div>
                    <p
                        class="text-sm mb-1"
                        style="color: var(--muted-foreground);"
                    >
                        Tahun Lulus
                    </p>
                    <p class="font-medium">
                        {{ $mahasiswa->tahun_lulus ?? '-' }}
                    </p>
                </div>

            </div>
        </div>


        {{-- Aksi --}}
        <div class="flex flex-wrap gap-2">

            <a
                href="{{ route('admin.alumni.index') }}"
                class="px-4 py-2 rounded-lg text-sm font-medium border"
                style="border-color: var(--border); color: var(--foreground);"
            >
                Kembali
            </a>

            @if ($user->status !== 'aktif')
                <form
                    method="POST"
                    action="{{ route('admin.alumni.activate', $user) }}"
                >
                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg text-sm font-medium text-white"
                        style="background: #2e7d32;"
                    >
                        Aktifkan Akun
                    </button>
                </form>
            @endif

            @if ($user->status !== 'nonaktif')
                <form
                    method="POST"
                    action="{{ route('admin.alumni.deactivate', $user) }}"
                >
                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg text-sm font-medium text-white"
                        style="background: #a13d3d;"
                    >
                        Nonaktifkan Akun
                    </button>
                </form>
            @endif

        </div>

    </div>
@endsection