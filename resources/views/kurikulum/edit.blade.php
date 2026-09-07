@extends('layouts.app')

@section('title', 'Edit Kurikulum - Evaluasi PBM')
@section('page-title', 'Edit Kurikulum')
@section('page-desc', 'Perbarui data kurikulum.')

@section('content')
    <div class="rounded-2xl border p-6 max-w-xl" style="background: var(--card); border-color: var(--border);">
        @if ($errors->any())
            <div class="mb-4 px-4 py-3 rounded-xl text-sm" style="background: #fdecea; color: #a13d3d;">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('kurikulum.update', $kurikulum) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Program Studi</label>
                <select name="prodi_id" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
                    <option value="">-- Pilih Prodi --</option>
                    @foreach ($prodiList as $prodi)
                        <option value="{{ $prodi->id }}" {{ old('prodi_id', $kurikulum->prodi_id) == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama Kurikulum</label>
                <input type="text" name="nama" value="{{ old('nama', $kurikulum->nama) }}" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Tahun Berlaku Mulai</label>
                <input type="number" name="tahun_berlaku_mulai" value="{{ old('tahun_berlaku_mulai', $kurikulum->tahun_berlaku_mulai) }}" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
                    <option value="draft" {{ old('status', $kurikulum->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="aktif" {{ old('status', $kurikulum->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status', $kurikulum->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 rounded-xl text-sm font-medium text-white hover:opacity-90" style="background: var(--primary);">Simpan</button>
                <a href="{{ route('kurikulum.index') }}" class="px-4 py-2 rounded-xl text-sm font-medium" style="background: var(--secondary); color: var(--foreground);">Batal</a>
            </div>
        </form>
    </div>
@endsection