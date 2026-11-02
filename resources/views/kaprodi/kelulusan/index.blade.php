@extends('layouts.app')

@section('content')
<div class="w-full space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Penetapan Kelulusan Mahasiswa</h1>
        <p class="mt-1 text-sm text-slate-500">Kaprodi memilih mahasiswa yang telah ditetapkan lulus. Penetapan tidak dilakukan otomatis berdasarkan semester.</p>
        <div class="mt-3 inline-flex rounded-lg bg-blue-50 px-3 py-2 text-sm font-medium text-blue-700">Program Studi: {{ $prodi->nama_prodi ?? $prodi->nama }}</div>
    </div>

    @if(session('status'))<div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('status') }}</div>@endif
    @if($errors->any())<div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <form method="GET" class="rounded-xl border border-slate-200 bg-white p-5">
        <label class="mb-2 block text-sm font-medium">Filter angkatan</label>
        <div class="flex flex-wrap gap-2">
            <select name="angkatan" class="rounded-lg border border-slate-300 px-4 py-2 text-sm">
                <option value="">Semua angkatan</option>
                @foreach($angkatanList as $angkatan)<option value="{{ $angkatan }}" @selected((string)request('angkatan') === (string)$angkatan)>{{ $angkatan }}</option>@endforeach
            </select>
            <button class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white">Terapkan</button>
            <a href="{{ route('kaprodi.kelulusan.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm">Reset</a>
        </div>
    </form>

    <form method="POST" action="{{ route('kaprodi.kelulusan.store') }}" class="space-y-4 rounded-xl border border-slate-200 bg-white p-5">
        @csrf
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div><h2 class="font-semibold">Pilih mahasiswa yang ditetapkan Lulus</h2><p class="mt-1 text-sm text-slate-500">Hanya mahasiswa Aktif dari Program Studi ini yang ditampilkan.</p></div>
            <label class="text-sm"><input type="checkbox" id="pilih-semua" class="mr-1"> Pilih semua di halaman</label>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500"><tr><th class="px-4 py-3">Pilih</th><th class="px-4 py-3">NIM</th><th class="px-4 py-3">Nama</th><th class="px-4 py-3">Angkatan</th><th class="px-4 py-3">Kelas</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                @forelse($mahasiswa as $item)
                    <tr><td class="px-4 py-3"><input type="checkbox" name="mahasiswa_ids[]" value="{{ $item->id }}" class="pilih-mahasiswa"></td><td class="px-4 py-3">{{ $item->nim }}</td><td class="px-4 py-3 font-medium">{{ $item->nama }}</td><td class="px-4 py-3">{{ $item->angkatan_masuk ?? '-' }}</td><td class="px-4 py-3">{{ $item->kelasMahasiswa->nama_kelas ?? '-' }}</td></tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">Tidak ada mahasiswa Aktif pada filter ini.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $mahasiswa->links() }}</div>
        <div class="grid gap-4 md:grid-cols-2">
            <div><label class="mb-2 block text-sm font-medium">Tanggal kelulusan</label><input type="date" name="tanggal_lulus" required value="{{ old('tanggal_lulus', today()->toDateString()) }}" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm"></div>
            <div><label class="mb-2 block text-sm font-medium">Alasan / dasar penetapan</label><textarea name="alasan" required rows="2" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm">{{ old('alasan') }}</textarea></div>
        </div>
        <div class="flex justify-end"><button type="submit" onclick="return confirm('Tetapkan mahasiswa terpilih sebagai Lulus? Perubahan ini akan tercatat dalam riwayat akademik.')" class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white">Konfirmasi Kelulusan</button></div>
    </form>
</div>
<script>
document.getElementById('pilih-semua')?.addEventListener('change', function () {
    document.querySelectorAll('.pilih-mahasiswa').forEach((item) => item.checked = this.checked);
});
</script>
@endsection
