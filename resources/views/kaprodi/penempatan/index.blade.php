@extends('layouts.app')
@section('content')
<div class="w-full space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Penempatan Mahasiswa</h1>
        <p class="mt-1 text-sm text-slate-500">Kelola penempatan kembali mahasiswa setelah menjalani cuti.</p>
        <div class="mt-3 inline-flex rounded-lg bg-blue-50 px-3 py-2 text-sm font-medium text-blue-700">Program Studi: {{ $prodi->nama_prodi ?? $prodi->nama }}</div>
    </div>
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <form method="GET" action="{{ route('kaprodi.penempatan.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-12">
            <div class="md:col-span-5"><label class="mb-2 block text-sm font-medium">Nama atau NIM</label><input name="keyword" value="{{ request('keyword') }}" placeholder="Cari mahasiswa..." class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm"></div>
            <div class="md:col-span-3"><label class="mb-2 block text-sm font-medium">Status akademik</label><select name="status" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm"><option value="">Semua Status</option>@foreach(['cuti','aktif','lulus','DO'] as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ strtoupper($status) }}</option>@endforeach</select></div>
            <div class="md:col-span-2"><label class="mb-2 block text-sm font-medium">Status penempatan</label><select name="penempatan" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm"><option value="">Semua</option><option value="belum" @selected(request('penempatan') === 'belum')>Belum ditempatkan</option><option value="selesai" @selected(request('penempatan') === 'selesai')>Sudah ditempatkan</option></select></div>
            <div class="flex items-end gap-2 md:col-span-2"><button class="flex-1 rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white">Cari</button><a href="{{ route('kaprodi.penempatan.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm">Reset</a></div>
        </form>
    </div>
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4"><div><h2 class="font-semibold">Daftar Mahasiswa</h2><p class="mt-1 text-sm text-slate-500">Mahasiswa pada program studi yang Anda kelola.</p></div><span class="text-sm text-slate-500">Total: {{ $mahasiswa->total() }} mahasiswa</span></div>
        <div class="overflow-x-auto"><table class="w-full min-w-[1050px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500"><tr><th class="px-5 py-4">No.</th><th class="px-5 py-4">NIM</th><th class="px-5 py-4">Nama</th><th class="px-5 py-4">Angkatan</th><th class="px-5 py-4">Kelas</th><th class="px-5 py-4">Status</th><th class="px-5 py-4">Semester berikutnya</th><th class="px-5 py-4">Penempatan</th><th class="px-5 py-4">Aksi</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
            @forelse($mahasiswa as $index => $item)
                @php $sudahDitempatkan = $item->status === 'aktif' && $item->riwayatPenempatanKelas()->where('alasan_penempatan', 'cuti_kembali')->exists(); @endphp
                <tr class="hover:bg-slate-50"><td class="px-5 py-4">{{ $mahasiswa->firstItem() + $index }}</td><td class="px-5 py-4 font-medium">{{ $item->nim }}</td><td class="px-5 py-4 font-medium">{{ $item->nama }}</td><td class="px-5 py-4">{{ $item->angkatan_masuk ?? '-' }}</td><td class="px-5 py-4">{{ $item->kelasMahasiswa->nama_kelas ?? '-' }}</td>
                <td class="px-5 py-4"><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $item->status === 'cuti' ? 'bg-amber-50 text-amber-700' : ($item->status === 'aktif' ? 'bg-green-50 text-green-700' : 'bg-slate-100 text-slate-600') }}">{{ ucfirst($item->status) }}</span></td>
                <td class="px-5 py-4">{{ $item->progresStudi ? 'Semester '.$item->progresStudi->semester_berikutnya : 'Belum diatur' }}</td>
                <td class="px-5 py-4">@if($sudahDitempatkan)<span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">Sudah ditempatkan</span>@elseif($item->status === 'cuti')<span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">Belum ditempatkan</span>@else<span class="text-slate-400">-</span>@endif</td>
                <td class="px-5 py-4">@if($item->status === 'cuti')<a href="{{ route('kaprodi.penempatan.show', $item) }}" class="inline-flex rounded-lg bg-blue-700 px-3 py-2 text-xs font-semibold text-white">Atur Penempatan</a>@elseif($sudahDitempatkan)<span class="text-xs text-green-700">Tercatat</span>@else<span class="text-slate-400">-</span>@endif</td></tr>
            @empty<tr><td colspan="9" class="px-5 py-12 text-center text-slate-500">Data mahasiswa tidak ditemukan.</td></tr>@endforelse
            </tbody>
        </table></div>
        <div class="border-t border-slate-200 px-5 py-4">{{ $mahasiswa->links() }}</div>
    </div>
</div>
@endsection
