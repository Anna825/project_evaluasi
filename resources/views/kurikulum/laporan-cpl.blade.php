@extends('layouts.app')

@section('title', 'Laporan Capaian CPL - Evaluasi PBM')
@section('page-title', 'Laporan Capaian CPL')
@section('page-desc', 'Rekap capaian pembelajaran lulusan untuk ' . $kurikulum->nama)

@section('content')

    <div class="mb-5">
        <a
            href="{{ route('kurikulum.show', $kurikulum) }}"
            class="text-sm hover:underline"
            style="color: var(--muted-foreground);"
        >
            ← Kembali ke Detail Kurikulum
        </a>
    </div>

    <div
        class="w-full rounded-2xl border p-6 mb-6"
        style="background: var(--card); border-color: var(--border);"
    >
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-xs uppercase tracking-wide" style="color: var(--muted-foreground);">Kurikulum</p>
                <p class="font-medium mt-1">{{ $kurikulum->nama }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide" style="color: var(--muted-foreground);">Program Studi</p>
                <p class="font-medium mt-1">{{ $kurikulum->prodi->nama ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide" style="color: var(--muted-foreground);">Jumlah CPL</p>
                <p class="font-medium mt-1">{{ $cplList->count() }}</p>
            </div>
        </div>
    </div>

    @forelse ($rekapPerKelas as $dataKelas)
        <div
            class="w-full rounded-2xl border overflow-hidden mb-6"
            style="background: var(--card); border-color: var(--border);"
        >
            <div class="px-6 py-5 border-b" style="border-color: var(--border);">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <h2 class="font-serif-display text-xl">
                            {{ $dataKelas['kelas']->nama_kelas ?? 'Belum Memiliki Kelas' }}
                        </h2>
                        <p class="text-sm mt-1" style="color: var(--muted-foreground);">
                            Rekap capaian CPL mahasiswa dalam kelas ini.
                        </p>
                    </div>
                    <span class="text-sm rounded-full px-3 py-1" style="background: var(--secondary);">
                        {{ $dataKelas['rekap']->count() }} Mahasiswa
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead style="background: var(--secondary);">
                        <tr>
                            <th class="px-4 py-3 font-medium whitespace-nowrap">NIM</th>
                            <th class="px-4 py-3 font-medium whitespace-nowrap">Nama</th>
                            @foreach ($cplList as $cpl)
                                <th class="px-4 py-3 font-medium whitespace-nowrap text-center">{{ $cpl->kode }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataKelas['rekap'] as $r)
                            <tr class="border-t" style="border-color: var(--border);">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="mono">{{ $r['mahasiswa']->nim }}</span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap font-medium">
                                    {{ $r['mahasiswa']->nama }}
                                </td>
                                @foreach ($cplList as $cpl)
                                    <td class="px-4 py-4 text-center whitespace-nowrap">
                                        {{ $r['capaian'][$cpl->id]['nilai'] ?? '-' }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="w-full rounded-2xl border p-8 text-center"
             style="background: var(--card); border-color: var(--border); color: var(--muted-foreground);">
            Belum ada data capaian CPL.
        </div>
    @endforelse

@endsection
