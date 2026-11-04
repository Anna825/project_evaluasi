@extends('layouts.app')

@section('content')
<div class="w-full space-y-6">
    @if(session('status'))<div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('status') }}</div>@endif
    @if($errors->any())<div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Kenaikan Kelas Mahasiswa</h1>
        <p class="mt-1 text-sm text-slate-500">Kenaikan dilakukan untuk seluruh mahasiswa Aktif dalam satu kelas. Identitas kelas diperbarui tanpa memindahkan mahasiswa.</p>
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
                        <option value="{{ $item->id }}" @selected((string) request('kelas_asal_id') === (string) $item->id)>{{ $item->nama_kelas }} ({{ $item->angkatan }})</option>
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
            Kelas asal: <strong>{{ $kelasAsalTerpilih->nama_kelas }} ({{ $kelasAsalTerpilih->angkatan }})</strong>.
            Kelas tujuan direncanakan menjadi <strong>{{ $preview->first()['nama_tujuan'] ?? (preg_replace('/^\d+/', (string) (\App\Models\KelasMahasiswa::tingkatDariNama($kelasAsalTerpilih->nama_kelas) + 1), $kelasAsalTerpilih->nama_kelas, 1)) }} ({{ $kelasAsalTerpilih->angkatan }})</strong>.
            Pratinjau tidak mengubah data. Mahasiswa Cuti dan Lulus tidak diproses.
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-4"><p class="text-sm text-slate-500">Mahasiswa Aktif</p><p class="mt-1 text-2xl font-bold">{{ $preview->count() }}</p></div>
            <div class="rounded-xl border border-slate-200 bg-white p-4"><p class="text-sm text-slate-500">Memenuhi validasi</p><p class="mt-1 text-2xl font-bold text-green-700">{{ $preview->where('layak', true)->count() }}</p></div>
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
                <p class="mt-1 text-sm text-slate-500">Semua mahasiswa Aktif harus lolos validasi. Jika satu mahasiswa bermasalah, seluruh batch tidak dapat diproses.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[950px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                        <tr><th class="px-5 py-4">NIM</th><th class="px-5 py-4">Nama</th><th class="px-5 py-4">Semester selesai</th><th class="px-5 py-4">Semester berikutnya</th><th class="px-5 py-4">Kelas asal</th><th class="px-5 py-4">Kelas setelah naik</th><th class="px-5 py-4">Validasi</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse($preview as $item)
                        <tr>
                            <td class="px-5 py-4">{{ $item['mahasiswa']->nim }}</td>
                            <td class="px-5 py-4 font-medium">{{ $item['mahasiswa']->nama }}</td>
                            <td class="px-5 py-4">{{ $item['semester_selesai'] ?: '-' }}</td>
                            <td class="px-5 py-4">{{ $item['semester_tujuan'] ?: '-' }}</td>
                            <td class="px-5 py-4">{{ $kelasAsalTerpilih->nama_kelas }}</td>
                            <td class="px-5 py-4 font-semibold">{{ $item['nama_tujuan'] }}</td>
                            <td class="px-5 py-4">
                                @if($item['layak'])
                                    <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">Memenuhi syarat</span>
                                @else
                                    <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">Perlu diperiksa</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-10 text-center text-slate-500">Belum ada mahasiswa Aktif pada kelas ini.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($preview->isNotEmpty() && $warnings->isEmpty() && $preview->every(fn ($item) => $item['layak']))
            <form method="POST" action="{{ route('kaprodi.kenaikan-kelas.store') }}" class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-blue-200 bg-blue-50 p-5">
                @csrf
                <input type="hidden" name="kelas_asal_id" value="{{ $kelasAsalTerpilih->id }}">
                <input type="hidden" name="semester_id" value="{{ $semesterTujuan->id }}">
                <div class="text-sm text-blue-900">
                    <p class="font-semibold">Seluruh {{ $preview->count() }} mahasiswa Aktif memenuhi validasi.</p>
                    <p class="mt-1">Konfirmasi akan mengubah nama dan tingkat record kelas ini. ID kelas, angkatan, dan keanggotaan mahasiswa tetap.</p>
                </div>
                <button type="submit" onclick="return confirm('Konfirmasi kenaikan seluruh mahasiswa Aktif pada kelas ini? Nama kelas akan diperbarui, tanpa memindahkan mahasiswa.')" class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white">Konfirmasi Kenaikan Kelas</button>
            </form>
        @endif
    @endif
</div>
@endsection
