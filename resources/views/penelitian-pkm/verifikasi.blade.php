@extends('layouts.app')

@section('title', 'Verifikasi Penelitian/PKM - Evaluasi PBM')
@section('page-title', 'Verifikasi Penelitian & PKM')
@section('page-desc', 'Tinjau pengajuan penelitian dan PKM dari dosen.')

@section('content')
    <div class="rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
        <table class="w-full text-left text-sm">
            <thead style="background: var(--secondary);">
                <tr>
                    <th class="p-3 font-medium">Judul</th>
                    <th class="p-3 font-medium">Ketua</th>
                    <th class="p-3 font-medium">Jenis</th>
                    <th class="p-3 font-medium">Status</th>
                    <th class="p-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($penelitianList as $p)
                    <tr class="border-t" style="border-color: var(--border);">
                        <td class="p-3">{{ $p->judul }}</td>
                        <td class="p-3" style="color: var(--muted-foreground);">{{ $p->dosen->firstWhere('pivot.peran', 'Ketua')?->nama ?? '-' }}</td>
                        <td class="p-3">{{ $p->jenis }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium" style="background: var(--secondary);">{{ ucfirst($p->status) }}</span>
                        </td>
                        <td class="p-3">
                            <div class="flex flex-wrap gap-2">

                                {{-- Detail --}}
                                <a
                                    href="{{ route('penelitian-pkm.verifikasi.show', $p) }}"
                                    class="px-3 py-1 rounded-lg text-xs font-medium border"
                                    style="border-color: var(--border); color: var(--foreground);"
                                >
                                    Detail
                                </a>

                                @if ($p->status === 'diajukan')

                                    {{-- Setujui --}}
                                    <form
                                        method="POST"
                                        action="{{ route('penelitian-pkm.approve', $p) }}"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="px-3 py-1 rounded-lg text-xs font-medium text-white"
                                            style="background: #2e7d32;"
                                        >
                                            Setujui
                                        </button>
                                    </form>

                                    {{-- Tolak --}}
                                    <form
                                        method="POST"
                                        action="{{ route('penelitian-pkm.reject', $p) }}"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="px-3 py-1 rounded-lg text-xs font-medium text-white"
                                            style="background: #a13d3d;"
                                        >
                                            Tolak
                                        </button>
                                    </form>

                                @endif

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center" style="color: var(--muted-foreground);">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection