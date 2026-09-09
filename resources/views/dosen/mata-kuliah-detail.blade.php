@extends('layouts.app')

@section('title', 'Detail Mata Kuliah - Evaluasi PBM')
@section('page-title', $mataKuliah->kode . ' — ' . $mataKuliah->nama)
@section('page-desc', $mataKuliah->sks . ' SKS, Semester ke-' . $mataKuliah->semester_ke)

@section('content')
     {{-- Kembali --}}
    <div class="mb-4 text-left">
        <a
            href="{{ route('mata-kuliah.index') }}"
            class="text-sm underline hover:opacity-70"
            style="color: var(--muted-foreground);"
        >
            ← Kembali ke Mata Kuliah
        </a>
    </div>
    <div class="rounded-2xl border overflow-hidden mb-6" style="background: var(--card); border-color: var(--border);">
        <div class="px-6 py-4 border-b flex items-center justify-between" style="border-color: var(--border);">
            <h2 class="font-serif-display text-lg">CPMK</h2>
            <a href="{{ route('cpmk.create', $mataKuliah) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium text-white" style="background: var(--primary);">+ Tambah CPMK</a>
        </div>
        <div class="p-2">
            @forelse ($mataKuliah->cpmk as $cpmk)
                <div class="p-4 border-b last:border-b-0" style="border-color: var(--border);">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium text-sm">{{ $cpmk->kode }}</p>
                            <p class="text-sm mt-1" style="color: var(--muted-foreground);">{{ $cpmk->deskripsi }}</p>
                        </div>
                        <div class="flex gap-3 shrink-0 ml-4 text-sm">
                            <a href="{{ route('cpmk.edit', $cpmk) }}" class="hover:underline" style="color: var(--accent);">Edit</a>
                            <form method="POST" action="{{ route('cpmk.destroy', $cpmk) }}" onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="hover:underline" style="color: #a13d3d;">Hapus</button>
                            </form>
                        </div>
                    </div>
                    @if ($cpmk->cpl->isNotEmpty())
                        <p class="text-xs mt-1" style="color: var(--muted-foreground);">
                            Dipetakan ke: @foreach ($cpmk->cpl as $cpl){{ $cpl->kode }} ({{ $cpl->pivot->bobot }}%){{ !$loop->last ? ', ' : '' }}@endforeach
                        </p>
                    @endif
                </div>
            @empty
                <p class="p-6 text-center text-sm" style="color: var(--muted-foreground);">Belum ada CPMK.</p>
            @endforelse
        </div>
    </div>

    <div class="rounded-2xl border overflow-hidden mb-6" style="background: var(--card); border-color: var(--border);">
        <div class="px-6 py-4 border-b flex items-center justify-between" style="border-color: var(--border);">
            <h2 class="font-serif-display text-lg">RPS</h2>
            <a href="{{ route('rps.create', $mataKuliah) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium text-white" style="background: var(--primary);">+ Tambah RPS</a>
        </div>
        <div class="p-2">
            @forelse ($mataKuliah->rps as $rps)
                <div class="p-4 border-b last:border-b-0 flex justify-between items-start" style="border-color: var(--border);">
                    <div>
                        <p class="font-medium text-sm">Versi {{ $rps->versi }} — {{ $rps->tanggal_disusun }}</p>
                        <p class="text-sm mt-1" style="color: var(--muted-foreground);">{{ $rps->deskripsi_singkat ?? '-' }}</p>
                    </div>
                    <div class="flex gap-3 shrink-0 ml-4 text-sm">
                        <a href="{{ route('rps.edit', $rps) }}" class="hover:underline" style="color: var(--accent);">Edit</a>
                        <form method="POST" action="{{ route('rps.destroy', $rps) }}" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="hover:underline" style="color: #a13d3d;">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="p-6 text-center text-sm" style="color: var(--muted-foreground);">Belum ada RPS.</p>
            @endforelse
        </div>
    </div>

    <div class="rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
        <div class="px-6 py-4 border-b" style="border-color: var(--border);">
            <h2 class="font-serif-display text-lg">Kelas &amp; Input Nilai</h2>
        </div>
        <div class="p-2">
            @forelse ($mataKuliah->kelas as $kls)
                <div class="p-4 border-b last:border-b-0 flex justify-between items-center" style="border-color: var(--border);">
                    <span class="text-sm">{{ $kls->nama }}</span>
                    <a href="{{ route('nilai-cpmk.create', $kls) }}" class="text-sm hover:underline" style="color: var(--primary);">Input Nilai CPMK</a>
                </div>
            @empty
                <p class="p-6 text-center text-sm" style="color: var(--muted-foreground);">Belum ada kelas.</p>
            @endforelse
        </div>
    </div>
@endsection