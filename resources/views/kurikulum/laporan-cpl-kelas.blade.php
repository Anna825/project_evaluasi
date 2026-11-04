@extends('layouts.app')

@section('title', 'Capaian CPL - ' . $kelasMahasiswa->nama_kelas)
@section('page-title', 'Capaian CPL ' . $kelasMahasiswa->nama_kelas)
@section('page-desc', 'Daftar capaian CPL mahasiswa pada kelas ' . $kelasMahasiswa->nama_kelas)

@section('content')
    <div class="mb-5">
        <a href="{{ route('kurikulum.laporan-cpl', $kurikulum) }}"
           class="text-sm hover:underline"
           style="color: var(--muted-foreground);">
            ← Kembali ke Daftar Kelas
        </a>
    </div>

    <div class="rounded-2xl border p-5 mb-5"
         style="background: var(--card); border-color: var(--border);">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center text-2xl"
                     style="background: var(--secondary);">📁</div>
                <div>
                    <p class="text-sm" style="color: var(--muted-foreground);">Kelas</p>
                    <h2 class="text-xl font-semibold">{{ $kelasMahasiswa->nama_kelas }}</h2>
                    <p class="text-sm mt-1" style="color: var(--muted-foreground);">
                        {{ $kelasMahasiswa->prodi->nama ?? '-' }} · {{ $kurikulum->nama }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="min-w-[150px] rounded-xl border px-5 py-3"
                     style="background: var(--secondary); border-color: var(--border);">
                    <p class="text-xs" style="color: var(--muted-foreground);">Jumlah CPL</p>
                    <p class="text-lg font-semibold mt-1">{{ $cplList->count() }}</p>
                </div>
                <div class="min-w-[170px] rounded-xl border px-5 py-3"
                     style="background: var(--secondary); border-color: var(--border);">
                    <p class="text-xs" style="color: var(--muted-foreground);">Jumlah Mahasiswa</p>
                    <p class="text-lg font-semibold mt-1">{{ $rekap->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border overflow-hidden"
         style="background: var(--card); border-color: var(--border);">
        <div class="px-5 py-4 border-b" style="border-color: var(--border);">
            <h2 class="text-lg font-semibold">Rekap Capaian CPL</h2>
            <p class="text-sm mt-1" style="color: var(--muted-foreground);">
                Nilai capaian CPL masing-masing mahasiswa kelas {{ $kelasMahasiswa->nama_kelas }}.
            </p>
        </div>

        @if ($rekap->isNotEmpty())
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
                        @foreach ($rekap as $r)
                            <tr class="border-t" style="border-color: var(--border);">
                                <td class="px-4 py-4 whitespace-nowrap"><span class="mono">{{ $r['mahasiswa']->nim }}</span></td>
                                <td class="px-4 py-4 whitespace-nowrap font-medium">{{ $r['mahasiswa']->nama }}</td>
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
        @else
            <div class="px-6 py-12 text-center">
                <div class="text-4xl mb-3">📁</div>
                <h3 class="font-medium">Belum ada mahasiswa pada kelas ini</h3>
                <p class="text-sm mt-1" style="color: var(--muted-foreground);">
                    Data mahasiswa akan tampil setelah ditempatkan pada kelas ini.
                </p>
            </div>
        @endif
    </div>
@endsection
