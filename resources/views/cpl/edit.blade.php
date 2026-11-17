@extends('layouts.app')

@section('title', 'Edit CPL - Evaluasi PBM')
@section('page-title', 'Edit CPL')
@section('page-desc', '')

@section('content')
 {{-- Kembali --}}
    <div class="mb-6">
        <x-back-button href="{{ route('kurikulum.show', $cpl->kurikulum_id) }}" label="kurikulum_id) }}" class="text-sm underline hover:opacity-70" style="color: var(--muted-foreground);" > Kembali ke Kurikulum" />
    </div>

    <div
        class="w-full rounded-2xl border p-6 sm:p-8"
        style="background: var(--card); border-color: var(--border);"
    >
        @if ($errors->any())
            <div role="alert" aria-live="assertive"
                 class="mb-6 px-4 py-4 rounded-xl border"
                 style="background: #fdecea; border-color: #e6a5a0; color: #9b2c2c;">
                <div class="flex items-start gap-3">
                    <svg class="shrink-0 mt-0.5" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path stroke-linecap="round" d="M12 8v4m0 4h.01"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="font-semibold mb-1">CPL belum bisa disimpan.</p>
                        <p class="text-sm mb-2">Periksa kembali data yang kamu masukkan:</p>
                        <ul class="list-disc pl-5 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button"
                            onclick="this.closest('[role=alert]').remove()"
                            class="text-lg leading-none font-semibold hover:opacity-70"
                            aria-label="Tutup pemberitahuan">&times;</button>
                </div>
            </div>
        @endif


        <form method="POST" action="{{ route('cpl.update', $cpl) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="kurikulum_id" value="{{ $cpl->kurikulum_id }}">
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Kode CPL</label>
                <input type="text" name="kode" value="{{ old('kode', $cpl->kode) }}" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>

                @error('kode')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
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