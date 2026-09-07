@extends('layouts.app')

@section('title', 'Edit Mahasiswa - Evaluasi PBM')
@section('page-title', 'Edit Data Mahasiswa')
@section('page-desc', 'Perbarui data akademik dan informasi mahasiswa ' . $mahasiswa->nama)

@section('content')
@php
    $prodiList = $prodiList ?? \App\Models\Prodi::orderBy('nama')->get();
@endphp

{{-- Header Bar: Breadcrumb & Tombol Back --}}
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-2 text-sm">
        <a href="{{ route('mahasiswa.index') }}" class="inline-flex items-center gap-1.5 font-medium hover:underline" style="color: var(--primary);">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Data Mahasiswa
        </a>
        <span style="color: var(--muted-foreground);">/</span>
        <span style="color: var(--muted-foreground);">Edit Data</span>
        <span style="color: var(--muted-foreground);">/</span>
        <span class="font-medium" style="color: var(--foreground);">{{ $mahasiswa->nama }}</span>
    </div>
</div>

{{-- Alert Error Validasi --}}
@if ($errors->any())
    <div class="flex items-start gap-3 rounded-2xl p-4 mb-6 border text-sm max-w-4xl" style="background: #fef2f2; border-color: #fecaca; color: #991b1b;">
        <svg class="w-5 h-5 shrink-0 mt-0.5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div>
            <p class="font-semibold mb-1">Periksa kembali isian formulir Anda:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- Layout 2 Kolom --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

    {{-- Kolom Kiri: Formulir Edit (8 Kolom) --}}
    <div class="lg:col-span-8">
        <div class="rounded-2xl border p-6 sm:p-8 card-hover" style="background: var(--card); border-color: var(--border);">
            
            {{-- Judul & Deskripsi Formulir --}}
            <div class="flex items-center gap-3 pb-5 border-b mb-6" style="border-color: var(--border);">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background: var(--secondary); color: var(--primary);">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-serif-display text-lg font-bold">Perbarui Informasi Mahasiswa</h2>
                    <p class="text-xs" style="color: var(--muted-foreground);">Pastikan perubahan data mahasiswa sudah sesuai dan terverifikasi.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('mahasiswa.update', $mahasiswa) }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Baris 1: Identitas Dasar (NIM & Nama) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium mb-1.5">
                            Nomor Induk Mahasiswa (NIM) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nim" value="{{ old('nim', $mahasiswa->nim) }}"
                            class="w-full rounded-xl px-4 py-2.5 text-sm font-mono border transition focus:ring-2 focus:ring-blue-500"
                            style="background: var(--card); border-color: var(--border);"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" value="{{ old('nama', $mahasiswa->nama) }}"
                            class="w-full rounded-xl px-4 py-2.5 text-sm border transition focus:ring-2 focus:ring-blue-500"
                            style="background: var(--card); border-color: var(--border);"
                            required>
                    </div>
                </div>

                {{-- Baris 2: Data Akademik (Program Studi & Angkatan) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium mb-1.5">
                            Program Studi <span class="text-red-500">*</span>
                        </label>
                        <select name="prodi_id"
                            class="w-full rounded-xl px-4 py-2.5 text-sm border transition focus:ring-2 focus:ring-blue-500"
                            style="background: var(--card); border-color: var(--border);"
                            required>
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach ($prodiList as $prodi)
                                <option value="{{ $prodi->id }}" {{ old('prodi_id', $mahasiswa->prodi_id) == $prodi->id ? 'selected' : '' }}>
                                    {{ $prodi->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1.5">
                            Tahun Angkatan <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="angkatan" value="{{ old('angkatan', $mahasiswa->angkatan) }}"
                            class="w-full rounded-xl px-4 py-2.5 text-sm border transition focus:ring-2 focus:ring-blue-500 font-mono"
                            style="background: var(--card); border-color: var(--border);"
                            required>
                    </div>
                </div>

                {{-- Baris 3: Capaian & Status (IPK Terakhir & Status) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium mb-1.5">
                            IPK Terakhir <span class="text-xs font-normal" style="color: var(--muted-foreground);">(Opsional)</span>
                        </label>
                        <input type="number" step="0.01" min="0" max="4" name="ipk_terakhir" value="{{ old('ipk_terakhir', $mahasiswa->ipk_terakhir) }}"
                            class="w-full rounded-xl px-4 py-2.5 text-sm border transition focus:ring-2 focus:ring-blue-500 font-mono"
                            style="background: var(--card); border-color: var(--border);">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1.5">
                            Status Mahasiswa <span class="text-red-500">*</span>
                        </label>
                        <select name="status"
                            class="w-full rounded-xl px-4 py-2.5 text-sm border transition focus:ring-2 focus:ring-blue-500"
                            style="background: var(--card); border-color: var(--border);"
                            required>
                            <option value="aktif" {{ old('status', $mahasiswa->status) == 'aktif' ? 'selected' : '' }}>Aktif (Sedang Berkuliah)</option>
                            <option value="cuti" {{ old('status', $mahasiswa->status) == 'cuti' ? 'selected' : '' }}>Cuti Akademik</option>
                            <option value="lulus" {{ old('status', $mahasiswa->status) == 'lulus' ? 'selected' : '' }}>Lulus (Alumni)</option>
                            <option value="DO" {{ old('status', $mahasiswa->status) == 'DO' ? 'selected' : '' }}>DO (Drop Out / Keluar)</option>
                        </select>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex items-center gap-3 pt-4 border-t" style="border-color: var(--border);">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-medium text-white transition hover:opacity-95 shadow-sm"
                        style="background: var(--primary);">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>

                    <a href="{{ route('mahasiswa.index') }}"
                        class="px-5 py-3 rounded-xl text-sm font-medium border transition hover:bg-gray-50"
                        style="border-color: var(--border); color: var(--muted-foreground);">
                        Batal
                    </a>
                </div>
            </form>

        </div>
    </div>

    {{-- Kolom Kanan: Ringkasan Mahasiswa (4 Kolom) --}}
    <div class="lg:col-span-4 space-y-6">
        <div class="rounded-2xl border p-6" style="background: var(--card); border-color: var(--border);">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg text-white" style="background: var(--primary);">
                    {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="font-bold text-sm truncate">{{ $mahasiswa->nama }}</p>
                    <p class="text-xs font-mono" style="color: var(--muted-foreground);">NIM: {{ $mahasiswa->nim }}</p>
                </div>
            </div>

            <div class="space-y-2.5 pt-3 border-t text-xs" style="border-color: var(--border);">
                <div class="flex justify-between">
                    <span style="color: var(--muted-foreground);">Terdaftar Sejak:</span>
                    <span class="font-medium">{{ $mahasiswa->created_at ? $mahasiswa->created_at->format('d M Y') : '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span style="color: var(--muted-foreground);">Terakhir Diperbarui:</span>
                    <span class="font-medium">{{ $mahasiswa->updated_at ? $mahasiswa->updated_at->format('d M Y') : '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span style="color: var(--muted-foreground);">Total Prestasi:</span>
                    <span class="font-bold font-mono">{{ $mahasiswa->prestasi()->count() }} Prestasi</span>
                </div>
            </div>

            <div class="mt-5 pt-4 border-t" style="border-color: var(--border);">
                <a href="{{ route('mahasiswa.show', $mahasiswa) }}" class="w-full flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl border text-xs font-medium hover:bg-gray-50 transition" style="border-color: var(--border); color: var(--primary);">
                    Lihat Detail Profil &rarr;
                </a>
            </div>
        </div>
    </div>

</div>
@endsection