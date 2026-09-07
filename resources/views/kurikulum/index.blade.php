@extends('layouts.app')

@section('title', 'Kurikulum - Evaluasi PBM')
@section('page-title', 'Kurikulum')
@section('page-desc', 'Kelola kurikulum dan capaian pembelajaran lulusan (CPL).')

@section('content')
    <div class="flex justify-end mb-4">
        <a href="{{ route('kurikulum.create') }}" class="inline-block px-4 py-2 rounded-xl text-sm font-medium text-white hover:opacity-90" style="background: var(--primary);">
            + Tambah Kurikulum
        </a>
    </div>

    <div class="rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
        <table class="w-full text-left text-sm">
            <thead style="background: var(--secondary);">
                <tr>
                    <th class="p-3 font-medium">Nama</th>
                    <th class="p-3 font-medium">Prodi</th>
                    <th class="p-3 font-medium">Tahun Berlaku</th>
                    <th class="p-3 font-medium">Status</th>
                    <th class="p-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kurikulum as $k)
                    <tr class="border-t" style="border-color: var(--border);">
                        <td class="p-3">{{ $k->nama }}</td>
                        <td class="p-3" style="color: var(--muted-foreground);">{{ $k->prodi->nama ?? '-' }}</td>
                        <td class="p-3">{{ $k->tahun_berlaku_mulai }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium" style="background: var(--secondary);">{{ ucfirst($k->status) }}</span>
                        </td>
                        <td class="p-3">
                            <div class="flex gap-3">
                                <a href="{{ route('kurikulum.show', $k) }}" class="hover:underline" style="color: var(--primary);">Kelola CPL</a>
                                <a href="{{ route('kurikulum.edit', $k) }}" class="hover:underline" style="color: var(--accent);">Edit</a>
                                <form method="POST" action="{{ route('kurikulum.destroy', $k) }}" onsubmit="return confirm('Yakin hapus? Semua CPL dan Mata Kuliah terkait akan ikut terhapus!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="hover:underline" style="color: #a13d3d;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center" style="color: var(--muted-foreground);">Belum ada data kurikulum.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection