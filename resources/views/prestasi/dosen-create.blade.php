@extends('layouts.app')

@section('title', 'Tambah Prestasi - Evaluasi PBM')
@section('page-title', 'Tambah Prestasi')
@section('page-desc', '')

@section('content')
    {{-- Kembali --}}
    <div class="mb-4 text-left">
        <a
            href="{{ route('prestasi-dosen.index') }}"
            class="text-sm underline hover:opacity-70"
            style="color: var(--muted-foreground);"
        >
            ← Kembali ke Prestasi
        </a>
    </div>

    <div
        class="w-full rounded-2xl border p-6 sm:p-8"
        style="background: var(--card); border-color: var(--border);"
    >
    <div class="w-full rounded-2xl border p-6 sm:p-8" style="background: var(--card); border-color: var(--border);">
        @if ($errors->any())
            <div class="mb-4 px-4 py-3 rounded-xl text-sm" style="background: #fdecea; color: #a13d3d;">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('prestasi-dosen.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Kegiatan</label>
                <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Tingkat</label>
                <input type="text" name="tingkat" value="{{ old('tingkat') }}" placeholder="Lokal/Nasional/Internasional" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Jenis</label>
                <input type="text" name="jenis" value="{{ old('jenis') }}" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Peringkat</label>
                <input type="text" name="peringkat" value="{{ old('peringkat') }}" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Tahun Akademik</label>
                <select name="tahun_akademik_id" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
                    <option value="">-- Pilih --</option>
                    @foreach ($tahunAkademikList as $ta)
                        <option value="{{ $ta->id }}" {{ old('tahun_akademik_id') == $ta->id ? 'selected' : '' }}>{{ $ta->label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 rounded-xl text-sm font-medium text-white" style="background: var(--primary);">Simpan</button>
                <a href="{{ route('prestasi-dosen.index') }}" class="px-4 py-2 rounded-xl text-sm font-medium" style="background: var(--secondary); color: var(--foreground);">Batal</a>
            </div>
        </form>
    </div>
@endsection