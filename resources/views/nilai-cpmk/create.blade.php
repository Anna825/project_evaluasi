@extends('layouts.app')

@section('title', 'Input Nilai CPMK - Evaluasi PBM')
@section('page-title', 'Input Nilai CPMK — ' . $kelas->nama)
@section('page-desc', '')

@section('content')
    @if ($cpmkList->isEmpty())
        <div class="rounded-xl p-4 text-sm" style="background: #fef3d9; color: #8a6816;">Belum ada CPMK untuk mata kuliah ini.</div>
    @elseif ($mahasiswaList->isEmpty())
        <div class="rounded-xl p-4 text-sm" style="background: #fef3d9; color: #8a6816;">Belum ada data mahasiswa untuk program studi ini.</div>
    @else
        <form method="POST" action="{{ route('nilai-cpmk.store') }}">
            @csrf
            <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">

            <div class="rounded-2xl border overflow-x-auto mb-4" style="background: var(--card); border-color: var(--border);">
                <table class="w-full text-left text-sm">
                    <thead style="background: var(--secondary);">
                        <tr>
                            <th class="p-3 font-medium sticky left-0" style="background: var(--secondary);">NIM</th>
                            <th class="p-3 font-medium">Nama</th>
                            @foreach ($cpmkList as $cpmk)
                                <th class="p-3 font-medium">{{ $cpmk->kode }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mahasiswaList as $mhs)
                            <tr class="border-t" style="border-color: var(--border);">
                                <td class="p-3 sticky left-0" style="background: var(--card);">{{ $mhs->nim }}</td>
                                <td class="p-3">{{ $mhs->nama }}</td>
                                @foreach ($cpmkList as $cpmk)
                                    @php $nilaiSekarang = $nilaiExisting[$mhs->id][$cpmk->id]->nilai ?? ''; @endphp
                                    <td class="p-2">
                                        <input type="number" step="0.01" min="0" max="100"
                                            name="nilai[{{ $mhs->id }}][{{ $cpmk->id }}]"
                                            value="{{ old("nilai.{$mhs->id}.{$cpmk->id}", $nilaiSekarang) }}"
                                            class="w-20 rounded-lg border px-2 py-1 text-sm [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                            style="border-color: var(--border);">
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 rounded-xl text-sm font-medium text-white" style="background: var(--primary);">Simpan Semua Nilai</button>
                <a href="{{ route('mata-kuliah.show', $kelas->mata_kuliah_id) }}" class="px-4 py-2 rounded-xl text-sm font-medium" style="background: var(--secondary); color: var(--foreground);">Batal</a>
            </div>
        </form>
    @endif
@endsection