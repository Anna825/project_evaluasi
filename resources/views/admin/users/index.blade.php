@extends('layouts.app')

@section('title', 'Kelola Akun Dosen - Evaluasi PBM')
@section('page-title', 'Akun Dosen')
@section('page-desc', 'Aktifkan atau nonaktifkan akun dosen yang mendaftar.')

@section('content')
    <div class="rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
        <table class="w-full text-left text-sm">
            <thead style="background: var(--secondary);">
                <tr>
                    <th class="p-3 font-medium">Nama</th>
                    <th class="p-3 font-medium">Email</th>
                    <th class="p-3 font-medium">NIDN</th>
                    <th class="p-3 font-medium">Prodi</th>
                    <th class="p-3 font-medium">Status</th>
                    <th class="p-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-t" style="border-color: var(--border);">
                        <td class="p-3">{{ $user->name }}</td>
                        <td class="p-3" style="color: var(--muted-foreground);">{{ $user->email }}</td>
                        <td class="p-3">{{ $user->dosen->nidn ?? '-' }}</td>
                        <td class="p-3">{{ $user->dosen->prodi->nama ?? '-' }}</td>
                        <td class="p-3">
                            @if ($user->status === 'pending')
                                <span class="px-2 py-1 rounded-full text-xs font-medium" style="background: #fef3d9; color: #8a6816;">Pending</span>
                            @elseif ($user->status === 'aktif')
                                <span class="px-2 py-1 rounded-full text-xs font-medium" style="background: #e3f2e6; color: #2e7d32;">Aktif</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-medium" style="background: #fdecea; color: #a13d3d;">Nonaktif</span>
                            @endif
                        </td>
                        <td class="p-3">
                            <div class="flex gap-2">
                                @if ($user->status !== 'aktif')
                                    <form method="POST" action="{{ route('admin.users.activate', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-1 rounded-lg text-xs font-medium text-white" style="background: #2e7d32;">Aktifkan</button>
                                    </form>
                                @endif
                                @if ($user->status !== 'nonaktif')
                                    <form method="POST" action="{{ route('admin.users.deactivate', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-1 rounded-lg text-xs font-medium text-white" style="background: #a13d3d;">Nonaktifkan</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-6 text-center" style="color: var(--muted-foreground);">Belum ada dosen yang mendaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection