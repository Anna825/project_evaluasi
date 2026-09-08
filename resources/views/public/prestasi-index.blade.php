@extends('layouts.mahasiswa')

@section('title', 'Prestasi Saya - Evaluasi PBM')
@section('page-title', 'Prestasi Saya')
@section('page-desc', 'Catat dan lihat prestasi akademik maupun non-akademik.')

@section('content')
    <div class="flex justify-end mb-4">
        <a href="{{ route('public.prestasi.create', $mahasiswa->nim) }}" class="inline-block px-4 py-2 rounded-xl text-sm font-medium text-white" style="background: var(--primary);">+ Tambah Prestasi</a>
    </div>

    <div class="rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
        <table class="w-full text-left text-sm">
            <thead style="background: var(--secondary);">
                <tr>
                    <th class="p-3 font-medium">Nama Kegiatan</th>
                    <th class="p-3 font-medium">Tingkat</th>
                    <th class="p-3 font-medium">Peringkat</th>
                    <th class="p-3 font-medium">Tempat Pelaksanaan</th>
                    <th class="p-3 font-medium">Tahun</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($prestasiList as $p)
                    <tr class="border-t" style="border-color: var(--border);">
                        <td class="p-3">{{ $p->nama_kegiatan }}</td>
                        <td class="p-3">{{ $p->tingkat ?? '-' }}</td>
                        <td class="p-3">{{ $p->peringkat ?? '-' }}</td>
                        <td class="p-3">{{ $p->tempat_pelaksanaan ?? '-' }}</td>
                        <td class="p-3">{{ $p->tahunAkademik->label ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center" style="color: var(--muted-foreground);">Belum ada prestasi tercatat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection