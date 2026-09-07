@extends('layouts.app')

@section('title', 'Edit Penelitian/PKM - Evaluasi PBM')
@section('page-title', 'Edit Penelitian/PKM')
@section('page-desc', '')

@section('content')
    <div class="rounded-2xl border p-6 max-w-xl" style="background: var(--card); border-color: var(--border);">
        @if ($errors->any())
            <div class="mb-4 px-4 py-3 rounded-xl text-sm" style="background: #fdecea; color: #a13d3d;">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('penelitian-pkm.update', $penelitianPkm) }}">
            @csrf @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Judul</label>
                <input type="text" name="judul" value="{{ old('judul', $penelitianPkm->judul) }}" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Jenis</label>
                <select name="jenis" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
                    <option value="Penelitian" {{ old('jenis', $penelitianPkm->jenis) == 'Penelitian' ? 'selected' : '' }}>Penelitian</option>
                    <option value="PKM" {{ old('jenis', $penelitianPkm->jenis) == 'PKM' ? 'selected' : '' }}>PKM</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Kategori Pendanaan (opsional)</label>
                <input type="text" name="kategori_pendanaan" value="{{ old('kategori_pendanaan', $penelitianPkm->kategori_pendanaan) }}" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Tahun Akademik</label>
                <select name="tahun_akademik_id" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
                    @foreach ($tahunAkademikList as $ta)
                        <option value="{{ $ta->id }}" {{ old('tahun_akademik_id', $penelitianPkm->tahun_akademik_id) == $ta->id ? 'selected' : '' }}>{{ $ta->label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 rounded-xl text-sm font-medium text-white" style="background: var(--primary);">Simpan</button>
                <a href="{{ route('penelitian-pkm.index') }}" class="px-4 py-2 rounded-xl text-sm font-medium" style="background: var(--secondary); color: var(--foreground);">Batal</a>
            </div>
        </form>
    </div>
@endsection