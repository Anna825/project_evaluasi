@extends('layouts.app')

@section('title', 'Program Studi - Evaluasi PBM')
@section('page-title', 'Program Studi')
@section('page-desc', 'Kelola data program studi di bawah jurusan.')

@section('content')
    <div class="flex justify-end mb-4">
        <a href="{{ route('prodi.create') }}" class="inline-block px-4 py-2 rounded-xl text-sm font-medium text-white hover:opacity-90" style="background: var(--primary);">
            + Tambah Prodi
        </a>
    </div>

    <div class="rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
        <table class="w-full text-left text-sm">
            <thead style="background: var(--secondary);">
                <tr>
                    <th class="p-3 font-medium">Nama Prodi</th>
                    <th class="p-3 font-medium">Jurusan</th>
                    <th class="p-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($prodi as $p)
                    <tr class="border-t" style="border-color: var(--border);">
                        <td class="p-3">{{ $p->nama }}</td>
                        <td class="p-3" style="color: var(--muted-foreground);">{{ $p->jurusan->nama ?? '-' }}</td>
                        <td class="p-3">
                            <div class="flex gap-3">
                                <a href="{{ route('prodi.edit', $p) }}" class="hover:underline" style="color: var(--accent);">Edit</a>
                                <form method="POST" action="{{ route('prodi.destroy', $p) }}" onsubmit="return confirm('Yakin hapus prodi ini? Semua data mahasiswa/dosen terkait akan ikut terhapus!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="hover:underline" style="color: #a13d3d;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-6 text-center" style="color: var(--muted-foreground);">Belum ada data prodi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection