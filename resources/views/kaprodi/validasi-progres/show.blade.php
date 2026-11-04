@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <a href="{{ route('kaprodi.validasi-progres.index') }}" class="text-sm font-medium text-blue-700 hover:underline">← Kembali ke daftar validasi</a>

    @if(session('status'))<div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('status') }}</div>@endif
    @if($errors->any())<div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <div>
        <h1 class="text-2xl font-bold text-slate-800">Detail Perbaikan Progres Akademik</h1>
        <p class="mt-1 text-sm text-slate-500">Periksa sumber riwayat sebelum menyetujui sinkronisasi.</p>
    </div>

    <div class="rounded-xl border bg-white p-5">
        <h2 class="text-lg font-semibold">{{ $mahasiswa->nama }}</h2>
        <p class="text-sm text-slate-500">NIM {{ $mahasiswa->nim }} · {{ $mahasiswa->kelasMahasiswa?->nama_kelas ?? 'Belum memiliki kelas' }} · Angkatan {{ $mahasiswa->angkatan_masuk }}</p>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div class="rounded-xl border border-red-200 bg-white p-5">
            <span class="text-xs font-semibold uppercase text-red-600">Progres saat ini</span>
            <p class="mt-2 text-3xl font-bold">{{ $analisis['progres_sekarang']?->semester_terakhir_selesai ?? 0 }}</p>
            <p class="text-sm text-slate-500">Semester terakhir selesai</p>
            <p class="mt-2 text-sm">Semester berikutnya: {{ $analisis['progres_sekarang']?->semester_berikutnya ?? 1 }}</p>
            <p class="text-sm">Semester terakhir ID: {{ $analisis['progres_sekarang']?->semester_terakhir_id ?? '-' }}</p>
        </div>
        <div class="rounded-xl border border-green-200 bg-white p-5">
            <span class="text-xs font-semibold uppercase text-green-700">Rekomendasi berdasarkan riwayat valid</span>
            <p class="mt-2 text-3xl font-bold text-green-700">{{ $analisis['semester_valid'] }}</p>
            <p class="text-sm text-slate-500">Semester terakhir yang dapat diverifikasi</p>
            <p class="mt-2 text-sm">Semester berikutnya: {{ $analisis['semester_berikutnya_valid'] }}</p>
            <p class="text-sm">Semester terakhir ID: {{ $analisis['detail_valid_terakhir']->semester_id ?? '-' }}</p>
        </div>
    </div>

    <div class="rounded-xl border bg-white p-5">
        <h2 class="font-semibold">Riwayat penyelesaian semester</h2>
        <p class="mt-1 text-xs text-slate-500">Catatan tidak dihapus saat sinkronisasi. Catatan yang tidak masuk urutan valid tetap dipertahankan untuk audit dan pemeriksaan.</p>
        <div class="mt-4 overflow-x-auto">
            <table class="w-full min-w-[650px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500"><tr><th class="px-3 py-3">Semester</th><th class="px-3 py-3">Semester ID</th><th class="px-3 py-3">Jenis</th><th class="px-3 py-3">Batch</th><th class="px-3 py-3">Status pemeriksaan</th></tr></thead>
                <tbody class="divide-y">
                @foreach($riwayat as $row)
                    @php($isValid = (int) $row->semester_diselesaikan <= $analisis['semester_valid'])
                    <tr>
                        <td class="px-3 py-3">{{ $row->semester_diselesaikan }}</td>
                        <td class="px-3 py-3">{{ $row->semester_id }}</td>
                        <td class="px-3 py-3">{{ ucfirst($row->jenis) }}</td>
                        <td class="px-3 py-3">{{ $row->batch_nomor }}</td>
                        <td class="px-3 py-3">
                            @if($isValid)<span class="rounded-full bg-green-50 px-2 py-1 text-xs text-green-700">Masuk urutan valid</span>
                            @else<span class="rounded-full bg-amber-50 px-2 py-1 text-xs text-amber-700">Perlu ditinjau</span>@endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($analisis['butuh_perbaikan'])
        <form method="POST" action="{{ route('kaprodi.validasi-progres.sync', $mahasiswa) }}" class="rounded-xl border border-blue-200 bg-blue-50 p-5 space-y-4">
            @csrf
            <div>
                <h2 class="font-semibold text-blue-900">Konfirmasi sinkronisasi progres</h2>
                <p class="mt-1 text-sm text-blue-800">Progres akan disesuaikan ke Semester {{ $analisis['semester_valid'] }}. Riwayat semester tidak dihapus atau diubah.</p>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Alasan perbaikan <span class="text-red-600">*</span></label>
                <textarea name="alasan" required minlength="10" maxlength="1000" rows="3" class="w-full rounded-lg border-slate-300 text-sm" placeholder="Contoh: Sinkronisasi karena progres tidak sesuai dengan riwayat penyelesaian semester yang valid.">{{ old('alasan') }}</textarea>
            </div>
            <button type="submit" onclick="return confirm('Yakin menyinkronkan progres mahasiswa berdasarkan riwayat semester valid? Riwayat semester tidak akan dihapus.')" class="rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white">Konfirmasi Sinkronisasi</button>
        </form>
    @else
        <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">Progres mahasiswa sudah sinkron. Tidak ada tindakan yang diperlukan.</div>
    @endif
</div>
@endsection
