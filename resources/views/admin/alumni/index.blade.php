@extends('layouts.app')

@section('title', 'Kelola Akun Alumni - Evaluasi PBM')
@section('page-title', 'Akun Alumni')
@section('page-desc', 'Aktifkan atau nonaktifkan akun alumni yang mendaftar.')

@section('content')
    <div class="rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
        <table class="w-full text-left text-sm">
            <thead style="background: var(--secondary);">
                <tr>
                    <th class="p-3 font-medium">Nama</th>
                    <th class="p-3 font-medium">Email</th>
                    <th class="p-3 font-medium">NIM</th>
                    <th class="p-3 font-medium">Prodi</th>
                    <th class="p-3 font-medium">Status</th>
                    <th class="p-3 font-medium">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($users as $user)
                    @php
                        $userRole = $user->roles->firstWhere('nama_role', 'alumni');
                        $mahasiswa = $userRole?->pivot?->mahasiswa_id
                            ? $user->userRoles->firstWhere('mahasiswa_id', $userRole->pivot->mahasiswa_id)?->mahasiswa
                            : null;
                    @endphp

                    <tr class="border-t" style="border-color: var(--border);">
                        <td class="p-3">
                            {{ $user->name }}
                        </td>

                        <td class="p-3" style="color: var(--muted-foreground);">
                            {{ $user->email }}
                        </td>

                        <td class="p-3">
                            {{ $mahasiswa->nim ?? '-' }}
                        </td>

                        <td class="p-3">
                            {{ $mahasiswa->prodi->nama ?? '-' }}
                        </td>

                        <td class="p-3">
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
                        </td>

                        <td class="p-3">
                            <div class="flex gap-2">
                                <a
                                    href="{{ route('admin.alumni.show', $user) }}"
                                    class="px-3 py-1 rounded-lg text-xs font-medium border"
                                    style="border-color: var(--border); color: var(--foreground);"
                                >
                                    Detail
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
                                            class="px-3 py-1 rounded-lg text-xs font-medium text-white"
                                            style="background: #2e7d32;"
                                        >
                                            Aktifkan
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
                                            class="px-3 py-1 rounded-lg text-xs font-medium text-white"
                                            style="background: #a13d3d;"
                                        >
                                            Nonaktifkan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td
                            colspan="6"
                            class="p-6 text-center"
                            style="color: var(--muted-foreground);"
                        >
                            Belum ada alumni yang mendaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection