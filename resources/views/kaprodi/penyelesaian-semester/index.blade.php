@extends('layouts.app')

@section('content')
<div class="w-full space-y-6">
    @if(session('status'))
        <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div>
        <h1 class="text-2xl font-bold text-slate-800">Penyelesaian Semester</h1>
        <p class="mt-1 text-sm text-slate-500">Pilih mahasiswa secara individual atau sekaligus dalam satu kelas. Progres hanya berubah setelah dikonfirmasi Kaprodi.</p>
        <div class="mt-3 inline-flex rounded-lg bg-blue-50 px-3 py-2 text-sm font-medium text-blue-700">
            Program Studi: {{ $prodi->nama_prodi ?? $prodi->nama }}
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <form method="GET" action="{{ route('kaprodi.penyelesaian-semester.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-12">
            <div class="md:col-span-6">
                <label class="mb-2 block text-sm font-medium">Semester yang diselesaikan</label>
                <select name="semester_id" required class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm">
                    <option value="">Pilih semester</option>
                    @foreach($semesterList as $item)
                        <option value="{{ $item->id }}" @selected((string) request('semester_id') === (string) $item->id)>
                            {{ $item->tahunAkademik->label ?? 'Tahun akademik' }} - {{ ucfirst($item->jenis) }} ({{ $item->tanggal_mulai }} s.d. {{ $item->tanggal_selesai }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-4">
                <label class="mb-2 block text-sm font-medium">Filter kelas (opsional)</label>
                <select name="kelas_id" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm">
                    <option value="">Semua kelas</option>
                    @foreach($kelasList as $item)
                        <option value="{{ $item->id }}" @selected((string) request('kelas_id') === (string) $item->id)>{{ $item->nama_kelas }} ({{ $item->angkatan }})</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end md:col-span-2">
                <button class="w-full rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white">Tampilkan</button>
            </div>
        </form>
    </div>

    @if($semester)
        <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900">
            Semester terpilih: <strong>{{ $semester->tahunAkademik->label ?? '-' }} - {{ ucfirst($semester->jenis) }}</strong>.
            @if(!$semesterSelesai)
                <p class="mt-1 text-amber-800">Tanggal akhir semester belum terlewati. Konfirmasi belum tersedia.</p>
            @else
                <p class="mt-1">Tanggal akhir sudah terlewati. Periksa rekomendasi sistem sebelum mengonfirmasi.</p>
            @endif
        </div>

        @if($semesterSelesai)
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="rounded-xl border border-slate-200 bg-white p-4"><p class="text-sm text-slate-500">Mahasiswa aktif</p><p class="mt-1 text-2xl font-bold">{{ $mahasiswaList->count() }}</p></div>
                <div class="rounded-xl border border-slate-200 bg-white p-4"><p class="text-sm text-slate-500">Layak diproses</p><p class="mt-1 text-2xl font-bold text-green-700">{{ $jumlahLayak }}</p></div>
                <div class="rounded-xl border border-slate-200 bg-white p-4"><p class="text-sm text-slate-500">Perlu ditinjau</p><p class="mt-1 text-2xl font-bold text-amber-700">{{ $jumlahPerluDitinjau }}</p></div>
            </div>

            <form method="POST" action="{{ route('kaprodi.penyelesaian-semester.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="semester_id" value="{{ $semester->id }}">
                @if($kelasTerpilih)<input type="hidden" name="kelas_id" value="{{ $kelasTerpilih->id }}">@endif
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                        <div>
                            <h2 class="font-semibold">Daftar mahasiswa</h2>
                            <p class="mt-1 text-sm text-slate-500">Centang satu per satu atau pilih semua yang layak diproses.</p>
                        </div>
                        <label class="flex items-center gap-2 text-sm font-medium">
                            <input id="pilih-semua-layak" type="checkbox" class="rounded border-slate-300">
                            Pilih semua yang layak
                        </label>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[900px] text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                                <tr><th class="px-5 py-3">Pilih</th><th class="px-5 py-3">NIM</th><th class="px-5 py-3">Nama</th><th class="px-5 py-3">Kelas</th><th class="px-5 py-3">Semester dihitung</th><th class="px-5 py-3">Status validasi</th></tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                            @forelse($mahasiswaList as $mahasiswa)
                                <tr>
                                    <td class="px-5 py-3"><input type="checkbox" name="mahasiswa_ids[]" value="{{ $mahasiswa->id }}" class="pilih-mahasiswa rounded border-slate-300" @disabled(!$mahasiswa->layak_diselesaikan)></td>
                                    <td class="px-5 py-3">{{ $mahasiswa->nim }}</td>
                                    <td class="px-5 py-3 font-medium">{{ $mahasiswa->nama }}</td>
                                    <td class="px-5 py-3">{{ $mahasiswa->kelasMahasiswa?->nama_kelas ?? '-' }}</td>
                                    <td class="px-5 py-3">{{ $mahasiswa->nomor_semester_acuan ? 'Semester '.$mahasiswa->nomor_semester_acuan : '-' }}</td>
                                    <td class="px-5 py-3">
                                        @if($mahasiswa->layak_diselesaikan)
                                            <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">Layak diproses</span>
                                        @else
                                            <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">{{ $mahasiswa->alasan_validasi }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-5 py-10 text-center text-slate-500">Tidak ada mahasiswa aktif pada filter ini.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-blue-200 bg-blue-50 p-5">
                    <div class="text-sm text-blue-900">
                        <p class="font-semibold">Konfirmasi penyelesaian semester</p>
                        <p class="mt-1">Mahasiswa yang tidak dipilih atau tidak layak tidak akan diubah progresnya.</p>
                    </div>
                    <button type="submit" onclick="return confirm('Konfirmasi penyelesaian semester untuk mahasiswa yang dipilih?')" class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white">Konfirmasi Mahasiswa Terpilih</button>
                </div>
            </form>
            <script>
                document.getElementById('pilih-semua-layak')?.addEventListener('change', function () {
                    document.querySelectorAll('.pilih-mahasiswa:not(:disabled)').forEach((checkbox) => {
                        checkbox.checked = this.checked;
                    });
                });
            </script>
        @endif
    @endif
</div>
@endsection
