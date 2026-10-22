
@extends('layouts.app')

@section('title', 'Kelola Jurusan - Evaluasi PBM')
@section('page-title', 'Kelola Jurusan')
@section('page-desc', 'Kelola data jurusan dan ketua jurusan.')

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded-xl border px-4 py-3 text-sm"
             style="background: var(--card); border-color: var(--border); color: var(--foreground);">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-xl border px-4 py-3 text-sm"
             style="background: var(--card); border-color: #e5a3a3; color: #a13d3d;">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-xl border px-4 py-3 text-sm"
             style="background: var(--card); border-color: #e5a3a3; color: #a13d3d;">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex justify-end mb-4">
        <a href="{{ route('jurusan.create') }}"
           class="inline-block px-4 py-2 rounded-xl text-sm font-medium text-white hover:opacity-90"
           style="background: var(--primary);">
            + Tambah Jurusan
        </a>
    </div>

    <div class="rounded-2xl border overflow-hidden"
         style="background: var(--card); border-color: var(--border);">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead style="background: var(--secondary);">
                    <tr>
                        <th class="p-3 font-medium">No.</th>
                        <th class="p-3 font-medium">Nama Jurusan</th>
                        <th class="p-3 font-medium">Ketua Jurusan</th>
                        <th class="p-3 font-medium">Jumlah Prodi</th>
                        <th class="p-3 font-medium">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($jurusan as $j)
                        <tr class="border-t" style="border-color: var(--border);">
                            <td class="p-3">
                                {{ $jurusan->firstItem() + $loop->index }}
                            </td>
                            <td class="p-3 font-medium">
                                {{ $j->nama }}
                            </td>
                            <td class="p-3" style="color: var(--muted-foreground);">
                                {{ $j->kajur ?: 'Belum ditentukan' }}
                            </td>
                            <td class="p-3">
                                {{ $j->prodi_count }}
                            </td>
                            <td class="p-3">
                                <div class="flex flex-wrap items-center gap-3">
                                    <a href="{{ route('jurusan.edit', $j) }}"
                                       class="hover:underline"
                                       style="color: var(--accent);">
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="{{ route('jurusan.destroy', $j) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus jurusan ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="hover:underline"
                                                style="color: #a13d3d;">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center"
                                style="color: var(--muted-foreground);">
                                Belum ada data jurusan. Silakan tambahkan jurusan terlebih dahulu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($jurusan->hasPages())
            <div class="border-t p-4" style="border-color: var(--border);">
                {{ $jurusan->links() }}
            </div>
        @endif
    </div>
@endsection
