@extends('layouts.app')

@section('title', 'Edit RPS - Evaluasi PBM')
@section('page-title', 'Edit RPS')
@section('page-desc', '')

@section('content')
    <div class="rounded-2xl border p-6 max-w-xl" style="background: var(--card); border-color: var(--border);">
        @if ($errors->any())
            <div class="mb-4 px-4 py-3 rounded-xl text-sm" style="background: #fdecea; color: #a13d3d;">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('rps.update', $rps) }}">
            @csrf @method('PUT')
            <input type="hidden" name="mata_kuliah_id" value="{{ $rps->mata_kuliah_id }}">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Versi</label>
                <input type="text" name="versi" value="{{ old('versi', $rps->versi) }}" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Tanggal Disusun</label>
                <input type="date" name="tanggal_disusun" value="{{ old('tanggal_disusun', $rps->tanggal_disusun) }}" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Deskripsi Singkat (opsional)</label>
                <textarea name="deskripsi_singkat" rows="3" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);">{{ old('deskripsi_singkat', $rps->deskripsi_singkat) }}</textarea>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 rounded-xl text-sm font-medium text-white" style="background: var(--primary);">Simpan</button>
                <a href="{{ route('mata-kuliah.show', $rps->mata_kuliah_id) }}" class="px-4 py-2 rounded-xl text-sm font-medium" style="background: var(--secondary); color: var(--foreground);">Batal</a>
            </div>
        </form>
    </div>
@endsection