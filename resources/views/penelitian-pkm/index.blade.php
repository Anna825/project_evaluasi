@extends('layouts.app')

@section('title', 'Penelitian & PKM Saya - Evaluasi PBM')
@section('page-title', 'Penelitian & PKM Saya')
@section('page-desc', '')

@section('content')
    <div class="flex justify-end mb-4">
        <a href="{{ route('penelitian-pkm.create') }}" class="inline-block px-4 py-2 rounded-xl text-sm font-medium text-white" style="background: var(--primary);">+ Ajukan Baru</a>
    </div>
    <div class="rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
        <table class="w-full text-left text-sm">
            <thead style="background: var(--secondary);">
                <tr>
                    <th class="p-3 font-medium">Judul</th>
                    <th class="p-3 font-medium">Jenis</th>
                    <th class="p-3 font-medium">Peran</th>
                    <th class="p-3 font-medium">Status</th>
                    <th class="p-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>

                @forelse ($penelitianList as $p)

                    <tr
                        class="border-t"
                        style="border-color: var(--border);"
                    >

                        <td class="p-3">
                            {{ $p->judul }}
                        </td>

                        <td class="p-3">
                            {{ $p->jenis }}
                        </td>

                        <td class="p-3">
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                                style="background: var(--secondary);"
                            >
                                {{ $p->pivot->peran ?? '-' }}
                            </span>
                        </td>

                        <td class="p-3">
                            <span
                                class="px-2 py-1 rounded-full text-xs font-medium"
                                style="background: var(--secondary);"
                            >
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>

                        <td class="p-3">

                            <div class="flex gap-3 items-center">

                                {{-- Semua anggota boleh melihat detail --}}
                                <a
                                    href="{{ route('penelitian-pkm.show', $p) }}"
                                    class="hover:underline"
                                    style="color: var(--primary);"
                                >
                                    Detail
                                </a>

                                {{-- Hanya Ketua yang boleh Edit dan Hapus --}}
                                @if (($p->pivot->peran ?? null) === 'Ketua')

                                    <a
                                        href="{{ route('penelitian-pkm.edit', $p) }}"
                                        class="hover:underline"
                                        style="color: var(--accent);"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('penelitian-pkm.destroy', $p) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus penelitian/PKM ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="hover:underline"
                                            style="color: #a13d3d;"
                                        >
                                            Hapus
                                        </button>
                                    </form>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="5"
                            class="p-6 text-center"
                            style="color: var(--muted-foreground);"
                        >
                            Belum ada penelitian/PKM.
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>
    </div>
@endsection