@extends('layouts.app')

@section('title', 'Ajukan Penelitian/PKM - Evaluasi PBM')
@section('page-title', 'Ajukan Penelitian/PKM')
@section('page-desc', '')

@section('content')
    <div class="rounded-2xl border p-6 max-w-xl" style="background: var(--card); border-color: var(--border);">
        @if ($errors->any())
            <div class="mb-4 px-4 py-3 rounded-xl text-sm" style="background: #fdecea; color: #a13d3d;">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('penelitian-pkm.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Judul</label>
                <input type="text" name="judul" value="{{ old('judul') }}" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Jenis</label>
                <select name="jenis" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
                    <option value="Penelitian" {{ old('jenis') == 'Penelitian' ? 'selected' : '' }}>Penelitian</option>
                    <option value="PKM" {{ old('jenis') == 'PKM' ? 'selected' : '' }}>PKM</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Kategori Pendanaan (opsional)</label>
                <input type="text" name="kategori_pendanaan" value="{{ old('kategori_pendanaan') }}" placeholder="Internal, DIKTI, dsb" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Tahun Akademik</label>
                <select name="tahun_akademik_id" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
                    <option value="">-- Pilih --</option>
                    @foreach ($tahunAkademikList as $ta)
                        <option value="{{ $ta->id }}" {{ old('tahun_akademik_id') == $ta->id ? 'selected' : '' }}>{{ $ta->label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Dosen Anggota (opsional)</label>
                <div class="space-y-1">
                    @foreach ($dosenList as $d)
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="anggota[]" value="{{ $d->id }}">
                            {{ $d->nama }}
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 rounded-xl text-sm font-medium text-white" style="background: var(--primary);">Ajukan</button>
                <a href="{{ route('penelitian-pkm.index') }}" class="px-4 py-2 rounded-xl text-sm font-medium" style="background: var(--secondary); color: var(--foreground);">Batal</a>
            </div>
        </form>
    </div>
@endsection