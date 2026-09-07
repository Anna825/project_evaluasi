@extends('layouts.app')

@section('title', 'Edit Prodi - Evaluasi PBM')
@section('page-title', 'Edit Program Studi')
@section('page-desc', 'Perbarui data program studi.')

@section('content')
    <div class="rounded-2xl border p-6 max-w-xl" style="background: var(--card); border-color: var(--border);">
        @if ($errors->any())
            <div class="mb-4 px-4 py-3 rounded-xl text-sm" style="background: #fdecea; color: #a13d3d;">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('prodi.update', $prodi) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Jurusan</label>
                <select name="jurusan_id" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
                    <option value="">-- Pilih Jurusan --</option>
                    @foreach ($jurusanList as $jurusan)
                        <option value="{{ $jurusan->id }}" {{ old('jurusan_id', $prodi->jurusan_id) == $jurusan->id ? 'selected' : '' }}>{{ $jurusan->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Nama Prodi</label>
                <input type="text" name="nama" value="{{ old('nama', $prodi->nama) }}" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 rounded-xl text-sm font-medium text-white hover:opacity-90" style="background: var(--primary);">Simpan</button>
                <a href="{{ route('prodi.index') }}" class="px-4 py-2 rounded-xl text-sm font-medium" style="background: var(--secondary); color: var(--foreground);">Batal</a>
            </div>
        </form>
    </div>
@endsection