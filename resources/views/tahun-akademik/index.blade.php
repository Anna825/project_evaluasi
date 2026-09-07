@extends('layouts.app')

@section('title', 'Tambah Tahun Akademik - Evaluasi PBM')
@section('page-title', 'Tambah Tahun Akademik')
@section('page-desc', 'Isi label tahun akademik baru.')

@section('content')
    <div class="rounded-2xl border p-6 max-w-xl" style="background: var(--card); border-color: var(--border);">
        @if ($errors->any())
            <div class="mb-4 px-4 py-3 rounded-xl text-sm" style="background: #fdecea; color: #a13d3d;">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('tahun-akademik.store') }}">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Label (contoh: 2026/2027)</label>
                <input type="text" name="label" value="{{ old('label') }}" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 rounded-xl text-sm font-medium text-white hover:opacity-90" style="background: var(--primary);">Simpan</button>
                <a href="{{ route('tahun-akademik.index') }}" class="px-4 py-2 rounded-xl text-sm font-medium" style="background: var(--secondary); color: var(--foreground);">Batal</a>
            </div>
        </form>
    </div>
@endsection