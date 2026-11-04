@extends('layouts.app')

@section('title', 'Penetapan Kelulusan - Evaluasi PBM')
@section('page-title', 'Penetapan Kelulusan Mahasiswa')
@section('page-desc', 'Pilih angkatan dan kelas terlebih dahulu untuk menampilkan mahasiswa yang akan ditetapkan lulus.')

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
        <div class="mb-4">
            <p class="text-xs font-semibold uppercase tracking-wider" style="color:var(--muted-foreground)">Program Studi</p>
            <h2 class="mt-1 text-lg font-semibold">{{ $prodi->nama_prodi ?? $prodi->nama }}</h2>
            <p class="mt-1 text-sm" style="color:var(--muted-foreground)">Data mahasiswa tidak ditampilkan sebelum angkatan dipilih agar proses penetapan lebih terarah.</p>
        </div>
        <form method="GET" action="{{ route('kaprodi.kelulusan.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto_auto] md:items-end">
            <div>
                <label for="filter-angkatan" class="mb-1.5 block text-sm font-medium">Angkatan masuk <span class="text-red-600">*</span></label>
                <select id="filter-angkatan" name="angkatan" required class="w-full rounded-xl border px-3 py-2.5 text-sm" style="border-color:var(--border)">
                    <option value="">Pilih angkatan</option>
                    @foreach($angkatanList as $angkatan)
                        <option value="{{ $angkatan }}" @selected((string)request('angkatan') === (string)$angkatan)>{{ $angkatan }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="filter-kelas" class="mb-1.5 block text-sm font-medium">Kelas <span class="font-normal" style="color:var(--muted-foreground)">(opsional)</span></label>
                <select id="filter-kelas" name="kelas" class="w-full rounded-xl border px-3 py-2.5 text-sm" style="border-color:var(--border)">
                    <option value="">Semua kelas pada angkatan</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" @selected((string)request('kelas') === (string)$kelas->id)>{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white" style="background:var(--primary)">Tampilkan</button>
            <a href="{{ route('kaprodi.kelulusan.index') }}" class="rounded-xl border px-5 py-2.5 text-center text-sm font-medium" style="border-color:var(--border)">Reset</a>
        </form>
    </div>

    @if(!$mahasiswa)
        <div class="rounded-2xl border bg-white px-6 py-12 text-center" style="border-color:var(--border)">
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl" style="background:var(--secondary);color:var(--primary)">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21.5v-16Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M4 5.5v16M8 7h8m-8 4h8"/></svg>
            </div>
            <h2 class="font-semibold">Pilih angkatan terlebih dahulu</h2>
            <p class="mt-1 text-sm" style="color:var(--muted-foreground)">Daftar mahasiswa akan muncul setelah kamu memilih angkatan masuk dan menekan tombol Tampilkan.</p>
        </div>
    @else
        <form method="POST" action="{{ route('kaprodi.kelulusan.store') }}" class="space-y-5 rounded-2xl border p-5 sm:p-6" style="background:var(--card);border-color:var(--border)">
            @csrf
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold">Mahasiswa Angkatan {{ request('angkatan') }}</h2>
                    <p class="mt-1 text-sm" style="color:var(--muted-foreground)">{{ $mahasiswa->total() }} mahasiswa Aktif ditemukan{{ request('kelas') ? ' pada kelas yang dipilih' : ' pada seluruh kelas angkatan ini' }}. Pilih mahasiswa yang akan ditetapkan lulus.</p>
                </div>
                <label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" id="pilih-semua" class="rounded" style="accent-color:var(--primary)"> Pilih semua di halaman</label>
            </div>
            <div class="overflow-hidden rounded-xl border" style="border-color:var(--border)">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[680px] text-left text-sm">
                        <thead style="background:var(--secondary);color:var(--muted-foreground)">
                            <tr><th class="px-4 py-3 font-semibold">Pilih</th><th class="px-4 py-3 font-semibold">NIM</th><th class="px-4 py-3 font-semibold">Nama Mahasiswa</th><th class="px-4 py-3 font-semibold">Angkatan</th><th class="px-4 py-3 font-semibold">Kelas</th></tr>
                        </thead>
                        <tbody>
                        @forelse($mahasiswa as $item)
                            <tr class="border-t" style="border-color:var(--border)">
                                <td class="px-4 py-3"><input type="checkbox" name="mahasiswa_ids[]" value="{{ $item->id }}" class="pilih-mahasiswa rounded" style="accent-color:var(--primary)"></td>
                                <td class="px-4 py-3 tabular-nums">{{ $item->nim }}</td>
                                <td class="px-4 py-3 font-medium">{{ $item->nama }}</td>
                                <td class="px-4 py-3">{{ $item->angkatan_masuk }}</td>
                                <td class="px-4 py-3">{{ $item->kelasMahasiswa->nama_kelas ?? 'Belum ditempatkan' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-10 text-center" style="color:var(--muted-foreground)">Tidak ada mahasiswa Aktif pada filter ini.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div>{{ $mahasiswa->links() }}</div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div><label for="tanggal-lulus" class="mb-1.5 block text-sm font-medium">Tanggal kelulusan</label><input id="tanggal-lulus" type="date" name="tanggal_lulus" required value="{{ old('tanggal_lulus', today()->toDateString()) }}" class="w-full rounded-xl border px-3 py-2.5 text-sm" style="border-color:var(--border)"></div>
                <div><label for="alasan-lulus" class="mb-1.5 block text-sm font-medium">Alasan / dasar penetapan</label><textarea id="alasan-lulus" name="alasan" required rows="2" class="w-full rounded-xl border px-3 py-2.5 text-sm" style="border-color:var(--border)" placeholder="Tuliskan dasar penetapan kelulusan...">{{ old('alasan') }}</textarea></div>
            </div>
            <div class="flex flex-col-reverse gap-3 border-t pt-4 sm:flex-row sm:items-center sm:justify-between" style="border-color:var(--border)">
                <p class="text-xs" style="color:var(--muted-foreground)">Penetapan hanya berlaku untuk mahasiswa yang dicentang pada halaman ini.</p>
                <button type="submit" onclick="return confirm('Tetapkan mahasiswa terpilih sebagai Lulus? Perubahan ini akan tercatat dalam riwayat akademik.')" class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white" style="background:var(--primary)">Konfirmasi Kelulusan</button>
            </div>
        </form>
    @endif
</div>
<script>
const selectAll = document.getElementById('pilih-semua');
selectAll?.addEventListener('change', function () {
    document.querySelectorAll('.pilih-mahasiswa').forEach(item => item.checked = this.checked);
});
document.querySelectorAll('.pilih-mahasiswa').forEach(item => {
    item.addEventListener('change', function () {
        if (selectAll) selectAll.checked = document.querySelectorAll('.pilih-mahasiswa:checked').length === document.querySelectorAll('.pilih-mahasiswa').length;
    });
});
</script>
@endsection
