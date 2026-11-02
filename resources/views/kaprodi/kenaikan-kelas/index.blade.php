@extends('layouts.app')

@section('content')
<div class="w-full space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Kenaikan Kelas Mahasiswa</h1>
        <p class="mt-1 text-sm text-slate-500">Pratinjau kenaikan semester dan tingkat kelas berdasarkan kelas asal.</p>
        <div class="mt-3 inline-flex rounded-lg bg-blue-50 px-3 py-2 text-sm font-medium text-blue-700">
            Program Studi: {{ $prodi->nama_prodi ?? $prodi->nama }}
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <form method="GET" action="{{ route('kaprodi.kenaikan-kelas.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-12">
            <div class="md:col-span-4">
                <label class="mb-2 block text-sm font-medium">Kelas asal</label>
                <select name="kelas_asal_id" required class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm">
                    <option value="">Pilih kelas</option>
                    @foreach($kelasList as $item)
                        <option value="{{ $item->id }}" @selected((string) request('kelas_asal_id') === (string) $item->id)>{{ $item->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-5">
                <label class="mb-2 block text-sm font-medium">Periode akademik tujuan</label>
                <select name="semester_id" required class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm">
                    <option value="">Pilih semester tujuan</option>
                    @foreach($semesterList as $item)
                        <option value="{{ $item->id }}" @selected((string) request('semester_id') === (string) $item->id)>
                            {{ $item->tahunAkademik->label ?? 'Tahun akademik' }} - {{ ucfirst($item->jenis) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2 md:col-span-3">
                <button class="flex-1 rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white">Tampilkan Pratinjau</button>
                <a href="{{ route('kaprodi.kenaikan-kelas.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm">Reset</a>
            </div>
        </form>
    </div>

    @if($semesterTujuan && $kelasAsalTerpilih)
        <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800">
            Kelas asal: {{ $kelasAsalTerpilih->nama_kelas }}. Pratinjau ini belum mengubah data mahasiswa. Mahasiswa Cuti dan Lulus tidak ditampilkan.
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-4"><p class="text-sm text-slate-500">Mahasiswa Aktif</p><p class="mt-1 text-2xl font-bold">{{ $preview->count() }}</p></div>
            <div class="rounded-xl border border-slate-200 bg-white p-4"><p class="text-sm text-slate-500">Siap dipetakan</p><p class="mt-1 text-2xl font-bold text-green-700">{{ $preview->whereNotNull('kelas_tujuan')->count() }}</p></div>
            <div class="rounded-xl border border-slate-200 bg-white p-4"><p class="text-sm text-slate-500">Perlu diperiksa</p><p class="mt-1 text-2xl font-bold text-amber-700">{{ $warnings->count() }}</p></div>
        </div>

        @if($warnings->isNotEmpty())
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-5">
                <h2 class="font-semibold text-amber-800">Peringatan yang perlu ditindaklanjuti</h2>
                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-amber-800">
                    @foreach($warnings as $warning)<li>{{ $warning['nim'] }} - {{ $warning['nama'] }}: {{ $warning['alasan'] }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="font-semibold">Rincian Pratinjau Kenaikan</h2>
                <p class="mt-1 text-sm text-slate-500">Hanya mahasiswa berstatus Aktif yang ditampilkan. Mahasiswa Cuti dan Lulus tidak diproses.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[950px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                        <tr><th class="px-5 py-4">NIM</th><th class="px-5 py-4">Nama</th><th class="px-5 py-4">Semester</th><th class="px-5 py-4">Kelas Asal</th><th class="px-5 py-4">Kelas Tujuan</th><th class="px-5 py-4">Validasi</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse($preview as $item)
                        <tr>
                            <td class="px-5 py-4">{{ $item['mahasiswa']->nim }}</td>
                            <td class="px-5 py-4 font-medium">{{ $item['mahasiswa']->nama }}</td>
                            <td class="px-5 py-4">Semester {{ $item['semester_saat_ini'] }} → {{ $item['semester_tujuan'] }}</td>
                            <td class="px-5 py-4">{{ $item['kelas_asal']->nama_kelas }}</td>
                            <td class="px-5 py-4 font-semibold">{{ $item['nama_tujuan'] }}</td>
                            <td class="px-5 py-4">
                                @if($item['kelas_tujuan'])
                                    <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">Kelas tersedia</span>
                                @else
                                    <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">Perlu diperiksa</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-slate-500">Belum ada mahasiswa Aktif dengan progres semester yang dapat dipratinjau.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
