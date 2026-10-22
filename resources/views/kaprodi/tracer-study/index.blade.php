@extends('layouts.app')

@section('title', 'Rekap Tracer Study - Kaprodi')
@section('page-title', 'Rekap Tracer Study')
@section('page-desc', 'Rekap data tracer alumni untuk Program Studi ' . ($prodi->nama ?? '-'))

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <p class="text-sm" style="color: var(--muted-foreground);">
                Data ditampilkan khusus untuk alumni yang berasal dari Program Studi {{ $prodi->nama ?? '-' }}.
            </p>
        </div>
        <a href="{{ route('kaprodi.manajemen-prodi') }}" class="ui-btn ui-btn-outline">← Manajemen Program Studi</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="ui-card p-5">
            <p class="text-xs uppercase tracking-wider font-semibold" style="color: var(--muted-foreground);">Total Alumni</p>
            <p class="text-3xl font-bold mono mt-2" style="color: var(--primary);">{{ $totalAlumni }}</p>
            <p class="text-xs mt-2" style="color: var(--muted-foreground);">Alumni berstatus lulus</p>
        </div>
        <div class="ui-card p-5">
            <p class="text-xs uppercase tracking-wider font-semibold" style="color: var(--muted-foreground);">Sudah Mengisi</p>
            <p class="text-3xl font-bold mono mt-2" style="color: #2e7d32;">{{ $totalAlumniTerisi }}</p>
            <p class="text-xs mt-2" style="color: var(--muted-foreground);">Alumni dengan data tracer</p>
        </div>
        <div class="ui-card p-5">
            <p class="text-xs uppercase tracking-wider font-semibold" style="color: var(--muted-foreground);">Belum Mengisi</p>
            <p class="text-3xl font-bold mono mt-2" style="color: #c62828;">{{ max(0, $totalAlumni - $totalAlumniTerisi) }}</p>
            <p class="text-xs mt-2" style="color: var(--muted-foreground);">Berdasarkan data alumni lulus</p>
        </div>
        <div class="ui-card p-5">
            <p class="text-xs uppercase tracking-wider font-semibold" style="color: var(--muted-foreground);">Respons Tracer</p>
            <p class="text-3xl font-bold mono mt-2" style="color: var(--accent);">{{ $persentaseRespons }}%</p>
            <p class="text-xs mt-2" style="color: var(--muted-foreground);">Alumni yang sudah mengisi</p>
        </div>
    </div>

    <div class="ui-card p-5 mb-6">
        <div class="flex items-center justify-between gap-3 mb-4">
            <div>
                <h2 class="font-serif-display text-xl">Filter Rekap</h2>
                <p class="text-sm mt-1" style="color: var(--muted-foreground);">Gunakan filter untuk melihat periode atau jenis pengisian tertentu.</p>
            </div>
            <a href="{{ route('kaprodi.tracer-study.index') }}" class="ui-btn ui-btn-outline text-sm">Reset</a>
        </div>

        <form method="GET" action="{{ route('kaprodi.tracer-study.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="ui-label">Tahun Lulus</label>
                <select name="tahun_lulus" class="ui-input">
                    <option value="">Semua Tahun</option>
                    @foreach ($tahunLulusList as $tahun)
                        <option value="{{ $tahun }}" @selected(request('tahun_lulus') == $tahun)>{{ $tahun }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="ui-label">Periode Pelacakan</label>
                <select name="periode_pelacakan" class="ui-input">
                    <option value="">Semua Periode</option>
                    @foreach ($periodeList as $periode)
                        <option value="{{ $periode }}" @selected(request('periode_pelacakan') == $periode)>{{ $periode }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="ui-label">Jenis Pengisian</label>
                <select name="jenis_pengisian" class="ui-input">
                    <option value="">Semua Jenis</option>
                    <option value="awal" @selected(request('jenis_pengisian') === 'awal')>Pengisian Awal</option>
                    <option value="perubahan_karier" @selected(request('jenis_pengisian') === 'perubahan_karier')>Perubahan Karier</option>
                </select>
            </div>
            <button type="submit" class="ui-btn ui-btn-primary">Terapkan Filter</button>
        </form>
    </div>

    <div class="ui-card overflow-hidden">
        <div class="px-5 py-4 border-b" style="border-color: var(--border);">
            <h2 class="font-serif-display text-xl">Data Tracer Study</h2>
            <p class="text-sm mt-1" style="color: var(--muted-foreground);">Setiap baris merupakan satu riwayat pengisian tracer study.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b" style="border-color: var(--border);">
                        <th class="text-left px-5 py-3 font-semibold">Alumni</th>
                        <th class="text-left px-5 py-3 font-semibold">Tahun Lulus</th>
                        <th class="text-left px-5 py-3 font-semibold">Periode</th>
                        <th class="text-left px-5 py-3 font-semibold">Jenis</th>
                        <th class="text-left px-5 py-3 font-semibold">Pengisian</th>
                        <th class="text-right px-5 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tracerList as $tracer)
                        <tr class="border-b last:border-b-0" style="border-color: var(--border);">
                            <td class="px-5 py-4">
                                <p class="font-medium">{{ $tracer->nama_alumni ?? $tracer->mahasiswa->nama ?? '-' }}</p>
                                <p class="text-xs mt-1" style="color: var(--muted-foreground);">{{ $tracer->mahasiswa->nim ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-4">{{ $tracer->tahun_lulus ?? '-' }}</td>
                            <td class="px-5 py-4">{{ $tracer->periode_pelacakan ?? '-' }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium" style="background: rgba(21,101,192,.08); color: #1565c0;">
                                    {{ $tracer->jenis_pengisian === 'awal' ? 'Pengisian Awal' : 'Perubahan Karier' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">{{ $tracer->tanggal_pengisian?->format('d-m-Y') ?? '-' }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('kaprodi.tracer-study.show', $tracer) }}" class="ui-btn ui-btn-outline text-sm">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center" style="color: var(--muted-foreground);">
                                Belum ada data Tracer Study yang sesuai dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($tracerList->hasPages())
            <div class="px-5 py-4 border-t" style="border-color: var(--border);">
                {{ $tracerList->links() }}
            </div>
        @endif
    </div>
@endsection
