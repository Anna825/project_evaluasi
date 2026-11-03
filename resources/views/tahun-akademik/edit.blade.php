@extends('layouts.app')

@section('title', 'Edit Tahun Akademik - Evaluasi PBM')
@section('page-title', 'Edit Tahun Akademik')
@section('page-desc', 'Perbarui label tahun akademik dan tanggal periode Semester Ganjil maupun Genap.')

@section('content')
<div class="mb-4">
    <a href="{{ route('tahun-akademik.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium"
       style="background: var(--secondary); color: var(--foreground);">
        ← Kembali ke Tahun Akademik
    </a>
</div>

<div class="rounded-2xl border p-6"
     style="background: var(--card); border-color: var(--border);">
    @if ($errors->any())
        <div class="mb-5 px-4 py-3 rounded-xl text-sm"
             style="background: #fdecea; color: #a13d3d;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('tahun-akademik.update', $tahunAkademik) }}">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <label for="label" class="block text-sm font-medium mb-1">Tahun Akademik</label>
            <input type="text" id="label" name="label"
                   value="{{ old('label', $tahunAkademik->label) }}"
                   placeholder="Contoh: 2026/2027"
                   class="w-full rounded-xl border px-3 py-2.5 text-sm"
                   style="border-color: var(--border);" required>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <section class="rounded-2xl border p-5" style="border-color: var(--border);">
                <h3 class="font-semibold mb-4">Semester Ganjil</h3>
                <div class="space-y-4">
                    <div>
                        <label for="ganjil_mulai" class="block text-sm font-medium mb-1">Tanggal Mulai</label>
                        <input type="date" id="ganjil_mulai" name="ganjil_mulai"
                               value="{{ old('ganjil_mulai', optional($ganjil)->tanggal_mulai ? \Illuminate\Support\Carbon::parse($ganjil->tanggal_mulai)->format('Y-m-d') : '') }}"
                               class="w-full rounded-xl border px-3 py-2.5 text-sm"
                               style="border-color: var(--border);" required>
                    </div>
                    <div>
                        <label for="ganjil_selesai" class="block text-sm font-medium mb-1">Tanggal Selesai</label>
                        <input type="date" id="ganjil_selesai" name="ganjil_selesai"
                               value="{{ old('ganjil_selesai', optional($ganjil)->tanggal_selesai ? \Illuminate\Support\Carbon::parse($ganjil->tanggal_selesai)->format('Y-m-d') : '') }}"
                               class="w-full rounded-xl border px-3 py-2.5 text-sm"
                               style="border-color: var(--border);" required>
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border p-5" style="border-color: var(--border);">
                <h3 class="font-semibold mb-4">Semester Genap</h3>
                <div class="space-y-4">
                    <div>
                        <label for="genap_mulai" class="block text-sm font-medium mb-1">Tanggal Mulai</label>
                        <input type="date" id="genap_mulai" name="genap_mulai"
                               value="{{ old('genap_mulai', optional($genap)->tanggal_mulai ? \Illuminate\Support\Carbon::parse($genap->tanggal_mulai)->format('Y-m-d') : '') }}"
                               class="w-full rounded-xl border px-3 py-2.5 text-sm"
                               style="border-color: var(--border);" required>
                    </div>
                    <div>
                        <label for="genap_selesai" class="block text-sm font-medium mb-1">Tanggal Selesai</label>
                        <input type="date" id="genap_selesai" name="genap_selesai"
                               value="{{ old('genap_selesai', optional($genap)->tanggal_selesai ? \Illuminate\Support\Carbon::parse($genap->tanggal_selesai)->format('Y-m-d') : '') }}"
                               class="w-full rounded-xl border px-3 py-2.5 text-sm"
                               style="border-color: var(--border);" required>
                    </div>
                </div>
            </section>
        </div>

        <div class="flex gap-2 mt-6">
            <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white"
                    style="background: var(--primary);">Simpan Perubahan</button>
            <a href="{{ route('tahun-akademik.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-medium"
               style="background: var(--secondary); color: var(--foreground);">Batal</a>
        </div>
    </form>
</div>
@endsection
