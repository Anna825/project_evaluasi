@extends('layouts.app')

@section('title', 'Penempatan Mahasiswa - Evaluasi PBM')
@section('page-title', 'Penempatan Mahasiswa')
@section('page-desc', 'Kelola mahasiswa Cuti yang akan kembali aktif dan ditempatkan pada semester berikutnya.')

@section('content')
<div class="space-y-6">
    @if(session('status'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="rounded-2xl border p-5 sm:p-6" style="background:var(--card);border-color:var(--border)">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider" style="color:var(--muted-foreground)">Program Studi</p>
                <h2 class="mt-1 text-lg font-semibold">{{ $prodi->nama_prodi ?? $prodi->nama }}</h2>
                <p class="mt-1 text-sm" style="color:var(--muted-foreground)">Hanya mahasiswa berstatus Cuti yang ditampilkan di halaman ini.</p>
            </div>
            <div class="rounded-xl px-4 py-3" style="background:var(--secondary)">
                <p class="text-xs" style="color:var(--muted-foreground)">Mahasiswa perlu penempatan</p>
                <p class="mt-1 text-2xl font-bold tabular-nums">{{ $mahasiswa->total() }}</p>
            </div>
        </div>
        <form method="GET" action="{{ route('kaprodi.penempatan.index') }}" class="mt-5 flex flex-col gap-3 sm:flex-row">
            <div class="flex-1">
                <label for="keyword" class="mb-1.5 block text-sm font-medium">Cari mahasiswa</label>
                <input id="keyword" name="keyword" value="{{ request('keyword') }}" placeholder="Masukkan nama atau NIM..." class="w-full rounded-xl border px-4 py-2.5 text-sm" style="border-color:var(--border)">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white" style="background:var(--primary)">Cari</button>
                <a href="{{ route('kaprodi.penempatan.index') }}" class="rounded-xl border px-5 py-2.5 text-sm font-medium" style="border-color:var(--border)">Reset</a>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border" style="background:var(--card);border-color:var(--border)">
        <div class="flex flex-col gap-1 border-b px-5 py-4 sm:flex-row sm:items-center sm:justify-between" style="border-color:var(--border)">
            <div>
                <h2 class="text-lg font-semibold">Daftar Mahasiswa Cuti</h2>
                <p class="mt-1 text-sm" style="color:var(--muted-foreground)">Mahasiswa Aktif yang sudah kembali dari cuti tidak ditampilkan di sini.</p>
            </div>
            <span class="text-sm font-medium" style="color:var(--muted-foreground)">Total {{ $mahasiswa->total() }} mahasiswa</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[850px] text-left text-sm">
                <thead style="background:var(--secondary);color:var(--muted-foreground)">
                    <tr>
                        <th class="px-5 py-3 font-semibold">No.</th>
                        <th class="px-5 py-3 font-semibold">NIM</th>
                        <th class="px-5 py-3 font-semibold">Nama Mahasiswa</th>
                        <th class="px-5 py-3 font-semibold">Angkatan</th>
                        <th class="px-5 py-3 font-semibold">Kelas Terakhir</th>
                        <th class="px-5 py-3 font-semibold">Semester Berikutnya</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswa as $index => $item)
                        <tr class="border-t transition-colors hover:bg-slate-50/70" style="border-color:var(--border)">
                            <td class="px-5 py-4">{{ $mahasiswa->firstItem() + $index }}</td>
                            <td class="px-5 py-4 font-medium tabular-nums">{{ $item->nim }}</td>
                            <td class="px-5 py-4 font-medium">{{ $item->nama }}</td>
                            <td class="px-5 py-4">{{ $item->angkatan_masuk ?? '-' }}</td>
                            <td class="px-5 py-4">{{ $item->kelasMahasiswa->nama_kelas ?? '-' }}</td>
                            <td class="px-5 py-4">{{ $item->progresStudi ? 'Semester '.$item->progresStudi->semester_berikutnya : 'Perlu pemeriksaan data' }}</td>
                            <td class="px-5 py-4"><span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">Cuti</span></td>
                            <td class="px-5 py-4 text-right"><a href="{{ route('kaprodi.penempatan.show', $item) }}" class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-xs font-semibold text-white" style="background:var(--primary)">Proses Penempatan</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-5 py-14 text-center">
                            <div class="font-semibold">Tidak ada mahasiswa Cuti</div>
                            <p class="mt-1 text-sm" style="color:var(--muted-foreground)">Saat ini tidak ada mahasiswa yang membutuhkan penempatan kembali.</p>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($mahasiswa->hasPages())
            <div class="border-t px-5 py-4" style="border-color:var(--border)">{{ $mahasiswa->links() }}</div>
        @endif
    </div>
</div>
@endsection
