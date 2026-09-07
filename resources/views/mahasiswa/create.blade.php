@extends('layouts.app')

@section('title', 'Tambah Mahasiswa - Evaluasi PBM')
@section('page-title', 'Tambah Mahasiswa Baru')
@section('page-desc', 'Daftarkan data mahasiswa baru secara manual ke sistem evaluasi dan akreditasi.')

@section('content')
@php
    $prodiList = $prodiList ?? \App\Models\Prodi::orderBy('nama')->get();
@endphp

{{-- Header Bar: Breadcrumb & Tombol Back --}}
<div class="flex items-center gap-2 text-sm mb-6">
    <a href="{{ route('mahasiswa.index') }}" class="inline-flex items-center gap-1.5 font-medium hover:underline" style="color: var(--primary);">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Data Mahasiswa
    </a>
    <span style="color: var(--muted-foreground);">/</span>
    <span style="color: var(--muted-foreground);">Tambah Mahasiswa</span>
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

    {{-- Kolom Kiri: Formulir Utama (8 Kolom) --}}
    <div class="lg:col-span-8">
        <div class="rounded-2xl border p-6 sm:p-8 card-hover" style="background: var(--card); border-color: var(--border);">
            
            {{-- Judul & Deskripsi Formulir --}}
            <div class="flex items-center gap-3 pb-5 border-b mb-6" style="border-color: var(--border);">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background: var(--secondary); color: var(--primary);">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-serif-display text-lg font-bold">Informasi Mahasiswa</h2>
                    <p class="text-xs" style="color: var(--muted-foreground);">Isi formulir berikut dengan data mahasiswa yang valid dan benar.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('mahasiswa.store') }}" class="space-y-6">
                @csrf

                {{-- Baris 1: Identitas Dasar (NIM & Nama) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium mb-1.5">
                            Nomor Induk Mahasiswa (NIM) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nim" value="{{ old('nim') }}"
                            placeholder="Contoh: 211511001"
                            class="w-full rounded-xl px-4 py-2.5 text-sm font-mono border transition focus:ring-2 focus:ring-blue-500"
                            style="background: var(--card); border-color: var(--border);"
                            required autofocus>
                        <p class="text-[11px] mt-1" style="color: var(--muted-foreground);">NIM harus unik dan belum pernah terdaftar.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" value="{{ old('nama') }}"
                            placeholder="Contoh: Ahmad Fauzan"
                            class="w-full rounded-xl px-4 py-2.5 text-sm border transition focus:ring-2 focus:ring-blue-500"
                            style="background: var(--card); border-color: var(--border);"
                            required>
                        <p class="text-[11px] mt-1" style="color: var(--muted-foreground);">Nama resmi sesuai dokumen akademik.</p>
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
                                <option value="{{ $prodi->id }}" {{ old('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                    {{ $prodi->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1.5">
                            Tahun Angkatan <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="angkatan" value="{{ old('angkatan', date('Y')) }}"
                            min="2000" max="{{ date('Y') }}"
                            placeholder="Contoh: {{ date('Y') }}"
                            class="w-full rounded-xl px-4 py-2.5 text-sm border transition focus:ring-2 focus:ring-blue-500 font-mono"
                            style="background: var(--card); border-color: var(--border);"
                            required>
                        <p class="text-[11px] mt-1" style="color: var(--muted-foreground);">Tahun masuk mahasiswa (4 digit angka).</p>
                    </div>
                </div>

                {{-- Baris 3: Capaian & Status (IPK Terakhir & Status) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium mb-1.5">
                            IPK Terakhir <span class="text-xs font-normal" style="color: var(--muted-foreground);">(Opsional)</span>
                        </label>
                        <input type="number" step="0.01" min="0" max="4" name="ipk_terakhir" value="{{ old('ipk_terakhir') }}"
                            placeholder="Contoh: 3.75"
                            class="w-full rounded-xl px-4 py-2.5 text-sm border transition focus:ring-2 focus:ring-blue-500 font-mono"
                            style="background: var(--card); border-color: var(--border);">
                        <p class="text-[11px] mt-1" style="color: var(--muted-foreground);">Skala 0.00 - 4.00 (kosongkan jika mahasiswa baru).</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1.5">
                            Status Mahasiswa <span class="text-red-500">*</span>
                        </label>
                        <select name="status"
                            class="w-full rounded-xl px-4 py-2.5 text-sm border transition focus:ring-2 focus:ring-blue-500"
                            style="background: var(--card); border-color: var(--border);"
                            required>
                            <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif (Sedang Berkuliah)</option>
                            <option value="cuti" {{ old('status') == 'cuti' ? 'selected' : '' }}>Cuti Akademik</option>
                            <option value="lulus" {{ old('status') == 'lulus' ? 'selected' : '' }}>Lulus (Alumni)</option>
                            <option value="DO" {{ old('status') == 'DO' ? 'selected' : '' }}>DO (Drop Out / Keluar)</option>
                        </select>
                        <p class="text-[11px] mt-1" style="color: var(--muted-foreground);">Default status untuk mahasiswa baru adalah Aktif.</p>
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
                        Simpan Data Mahasiswa
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

    {{-- Kolom Kanan: Panduan & Informasi Pendukung (4 Kolom) --}}
    <div class="lg:col-span-4 space-y-6">

        {{-- Pintasan Import Excel --}}
        <div class="rounded-2xl border p-6 card-hover" style="background: var(--card); border-color: var(--border);">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3.5" style="background: #e8f5e9; color: #2e7d32;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="font-serif-display font-bold text-base mb-1">Punya Banyak Data?</h3>
            <p class="text-xs mb-4" style="color: var(--muted-foreground);">
                Jika Anda ingin menambahkan puluhan atau ratusan mahasiswa sekaligus, gunakan fitur Import Excel agar data terisi secara otomatis.
            </p>
            <a href="{{ route('mahasiswa.import.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-medium border transition hover:border-emerald-500 hover:text-emerald-700 hover:bg-emerald-50"
                style="border-color: var(--border); color: var(--primary);">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Buka Halaman Import Excel
            </a>
        </div>

        {{-- Petunjuk Pengisian --}}
        <div class="rounded-2xl border p-6" style="background: var(--card); border-color: var(--border);">
            <h3 class="font-serif-display font-bold text-base mb-3">Petunjuk Pengisian:</h3>
            <div class="space-y-3 text-xs" style="color: var(--muted-foreground);">
                <div class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full flex items-center justify-center shrink-0 font-bold text-[10px]" style="background: var(--secondary); color: var(--primary);">1</span>
                    <p><strong>NIM Unik:</strong> Pastikan NIM tidak tertukar atau sama dengan data mahasiswa yang telah tersimpan.</p>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full flex items-center justify-center shrink-0 font-bold text-[10px]" style="background: var(--secondary); color: var(--primary);">2</span>
                    <p><strong>Akses Mahasiswa:</strong> Setelah tersimpan, mahasiswa bersangkutan dapat login mandiri menggunakan NIM tersebut.</p>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="w-5 h-5 rounded-full flex items-center justify-center shrink-0 font-bold text-[10px]" style="background: var(--secondary); color: var(--primary);">3</span>
                    <p><strong>Status Alumni:</strong> Jika status diset ke <em>Lulus</em>, mahasiswa tersebut dapat mengisi kuesioner Tracer Study.</p>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection