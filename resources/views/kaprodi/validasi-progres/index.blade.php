@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Validasi & Perbaikan Data Akademik</h1>
        <p class="mt-1 text-sm text-slate-500">Temukan ketidaksesuaian progres dan sinkronkan berdasarkan riwayat penyelesaian semester yang valid.</p>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-xl border bg-white p-4"><p class="text-sm text-slate-500">Mahasiswa aktif prodi</p><p class="mt-1 text-2xl font-bold">{{ $totalAktif }}</p></div>
        <div class="rounded-xl border bg-white p-4"><p class="text-sm text-slate-500">Perlu perbaikan</p><p class="mt-1 text-2xl font-bold text-amber-600">{{ $items->count() }}</p></div>
        <div class="rounded-xl border bg-white p-4"><p class="text-sm text-slate-500">Sudah sinkron</p><p class="mt-1 text-2xl font-bold text-green-700">{{ max(0, $totalAktif - $items->count()) }}</p></div>
    </div>

    <form method="GET" class="rounded-xl border bg-white p-4">
        <label class="mb-2 block text-sm font-medium">Cari mahasiswa berdasarkan NIM atau nama</label>
        <div class="flex gap-2">
            <input type="text" name="q" value="{{ $q }}" placeholder="Masukkan NIM atau nama..." class="w-full rounded-lg border-slate-300 text-sm">
            <button class="rounded-lg bg-blue-700 px-4 py-2 text-sm font-semibold text-white">Cari</button>
            <a href="{{ route('kaprodi.validasi-progres.index') }}" class="rounded-lg border px-4 py-2 text-sm">Reset</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-xl border bg-white">
        <div class="border-b p-4"><h2 class="font-semibold">Daftar mahasiswa yang perlu ditinjau</h2></div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                    <tr><th class="px-4 py-3">NIM / Nama</th><th class="px-4 py-3">Kelas</th><th class="px-4 py-3">Progres saat ini</th><th class="px-4 py-3">Riwayat valid</th><th class="px-4 py-3">Temuan</th><th class="px-4 py-3">Aksi</th></tr>
                </thead>
                <tbody class="divide-y">
                @forelse($items as $item)
                    @php($m = $item['mahasiswa'])
                    @php($a = $item['analisis'])
                    <tr>
                        <td class="px-4 py-3"><div class="font-semibold">{{ $m->nama }}</div><div class="text-xs text-slate-500">{{ $m->nim }}</div></td>
                        <td class="px-4 py-3">{{ $m->kelasMahasiswa?->nama_kelas ?? '-' }}</td>
                        <td class="px-4 py-3">Semester {{ $a['progres_sekarang']?->semester_terakhir_selesai ?? 0 }}<div class="text-xs text-slate-500">Berikutnya {{ $a['progres_sekarang']?->semester_berikutnya ?? 1 }}</div></td>
                        <td class="px-4 py-3">Semester {{ $a['semester_valid'] }}<div class="text-xs text-slate-500">Berikutnya {{ $a['semester_berikutnya_valid'] }}</div></td>
                        <td class="px-4 py-3"><span class="rounded-full bg-amber-50 px-2 py-1 text-xs font-semibold text-amber-700">Perlu diperiksa</span><div class="mt-1 max-w-xs text-xs text-slate-500">{{ $a['alasan'] }}</div></td>
                        <td class="px-4 py-3"><a href="{{ route('kaprodi.validasi-progres.show', $m) }}" class="font-semibold text-blue-700 hover:underline">Periksa detail →</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500">Tidak ditemukan mahasiswa dengan ketidaksesuaian progres.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
