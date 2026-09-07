@extends('layouts.app')

@section('title', 'Tambah RPS - Evaluasi PBM')
@section('page-title', 'Tambah RPS')
@section('page-desc', 'Untuk mata kuliah: ' . $mataKuliah->nama)

@section('content')
    <div class="rounded-2xl border p-6 max-w-xl" style="background: var(--card); border-color: var(--border);">
        @if ($errors->any())
            <div class="mb-4 px-4 py-3 rounded-xl text-sm" style="background: #fdecea; color: #a13d3d;">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('rps.store') }}">
            @csrf
            <input type="hidden" name="mata_kuliah_id" value="{{ $mataKuliah->id }}">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Versi</label>
                <input type="text" name="versi" value="{{ old('versi') }}" placeholder="Contoh: 1.0" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Tanggal Disusun</label>
                <input type="date" name="tanggal_disusun" value="{{ old('tanggal_disusun') }}" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Deskripsi Singkat (opsional)</label>
                <textarea name="deskripsi_singkat" rows="3" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);">{{ old('deskripsi_singkat') }}</textarea>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 rounded-xl text-sm font-medium text-white" style="background: var(--primary);">Simpan</button>
                <a href="{{ route('mata-kuliah.show', $mataKuliah) }}" class="px-4 py-2 rounded-xl text-sm font-medium" style="background: var(--secondary); color: var(--foreground);">Batal</a>
            </div>
        </form>
    </div>
@endsection