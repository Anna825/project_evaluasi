@extends('layouts.app')

@section('title', 'Edit CPL - Evaluasi PBM')
@section('page-title', 'Edit CPL')
@section('page-desc', '')

@section('content')
    <div class="rounded-2xl border p-6 max-w-xl" style="background: var(--card); border-color: var(--border);">
        @if ($errors->any())
            <div class="mb-4 px-4 py-3 rounded-xl text-sm" style="background: #fdecea; color: #a13d3d;">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('cpl.update', $cpl) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="kurikulum_id" value="{{ $cpl->kurikulum_id }}">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Kode CPL</label>
                <input type="text" name="kode" value="{{ old('kode', $cpl->kode) }}" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Domain (opsional)</label>
                <input type="text" name="domain" value="{{ old('domain', $cpl->domain) }}" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="4" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>{{ old('deskripsi', $cpl->deskripsi) }}</textarea>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 rounded-xl text-sm font-medium text-white hover:opacity-90" style="background: var(--primary);">Simpan</button>
                <a href="{{ route('kurikulum.show', $cpl->kurikulum_id) }}" class="px-4 py-2 rounded-xl text-sm font-medium" style="background: var(--secondary); color: var(--foreground);">Batal</a>
            </div>
        </form>
    </div>
@endsection