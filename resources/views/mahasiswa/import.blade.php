@extends('layouts.app')

@section('title', 'Import Data Mahasiswa - Evaluasi PBM')
@section('page-title', 'Import Data Mahasiswa')
@section('page-desc', 'Unggah data mahasiswa secara massal menggunakan file template Excel atau CSV.')

@section('content')
@php
    $totalMahasiswa = $totalMahasiswa ?? \App\Models\Mahasiswa::count();
    $prodis = $prodis ?? \App\Models\Prodi::orderBy('nama')->get();
@endphp

{{-- Header Bar: Breadcrumb & Statistik --}}
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-2 text-sm">
        <a href="{{ route('mahasiswa.index') }}" class="inline-flex items-center gap-1.5 font-medium hover:underline" style="color: var(--primary);">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Data Mahasiswa
        </a>
        <span style="color: var(--muted-foreground);">/</span>
        <span style="color: var(--muted-foreground);">Import Excel</span>
    </div>

    <div class="flex items-center gap-2 px-3.5 py-1.5 rounded-full border text-xs font-medium" style="background: var(--card); border-color: var(--border);">
        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
        <span style="color: var(--muted-foreground);">Total Mahasiswa Terdaftar:</span>
        <strong class="mono" style="color: var(--foreground);">{{ number_format($totalMahasiswa) }}</strong>
    </div>
</div>

{{-- Alert Notifikasi --}}
@if (session('status'))
    <div class="flex items-start gap-3 rounded-2xl p-4 mb-6 border text-sm" style="background: #f0fdf4; border-color: #bbf7d0; color: #166534;">
        <svg class="w-5 h-5 shrink-0 mt-0.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div>
            <p class="font-semibold">Berhasil!</p>
            <p>{{ session('status') }}</p>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="flex items-start gap-3 rounded-2xl p-4 mb-6 border text-sm" style="background: #fef2f2; border-color: #fecaca; color: #991b1b;">
        <svg class="w-5 h-5 shrink-0 mt-0.5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div>
            <p class="font-semibold mb-1">Terjadi Kesalahan Upload:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

@if (session('failures') && session('failures')->isNotEmpty())
    <div class="rounded-2xl border p-5 mb-6" style="background: #fef2f2; border-color: #fecaca;">
        <div class="flex items-center gap-2 mb-3">
            <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h3 class="font-semibold text-sm text-red-800">Terdapat {{ count(session('failures')) }} Baris Data yang Gagal Diimport:</h3>
        </div>
        <div class="overflow-x-auto rounded-xl border border-red-200 bg-white">
            <table class="w-full text-left text-xs">
                <thead class="bg-red-50 text-red-700 font-semibold border-b border-red-200">
                    <tr>
                        <th class="py-2.5 px-4 w-20">Baris Excel</th>
                        <th class="py-2.5 px-4 w-36">Atribut / Kolom</th>
                        <th class="py-2.5 px-4">Penyebab Masalah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-red-100 text-red-900">
                    @foreach (session('failures') as $failure)
                        <tr class="hover:bg-red-50/50">
                            <td class="py-2.5 px-4 font-mono font-bold text-center">#{{ $failure->row() }}</td>
                            <td class="py-2.5 px-4 font-mono font-semibold">{{ $failure->attribute() }}</td>
                            <td class="py-2.5 px-4">{{ implode(', ', $failure->errors()) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="text-xs text-red-700 mt-2.5">Silakan perbaiki data pada baris-baris tersebut di file Excel Anda, lalu unggah kembali.</p>
    </div>
@endif

{{-- Grid Utama: 2 Kolom --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

    {{-- Kolom Kiri: Download Template & Form Upload (7 Kolom) --}}
    <div class="lg:col-span-7 space-y-6">

        {{-- Langkah 1: Download Template --}}
        <div class="rounded-2xl border p-6 card-hover" style="background: var(--card); border-color: var(--border);">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background: #e8f5e9; color: #2e7d32;">
                    {{-- Icon Excel --}}
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider text-emerald-800 bg-emerald-100">Langkah 1</span>
                        <h2 class="font-serif-display text-lg font-bold">Unduh Template Format</h2>
                    </div>
                    <p class="text-sm mb-4" style="color: var(--muted-foreground);">
                        Gunakan format template resmi agar nama kolom dan struktur data terbaca secara otomatis oleh sistem tanpa terjadi kesalahan.
                    </p>
                    <a href="{{ route('mahasiswa.import.template') }}"
                        class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-sm font-medium text-white transition hover:opacity-90 shadow-sm"
                        style="background: #1b5e20;">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download Template Excel (.xlsx)
                    </a>
                </div>
            </div>
        </div>

        {{-- Langkah 2: Upload File Mahasiswa --}}
        <div class="rounded-2xl border p-6 card-hover" style="background: var(--card); border-color: var(--border);">
            <div class="flex items-center gap-2 mb-4">
                <span class="px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider text-blue-800 bg-blue-100">Langkah 2</span>
                <h2 class="font-serif-display text-lg font-bold">Unggah File Data Mahasiswa</h2>
            </div>

            <form id="importForm" method="POST" action="{{ route('mahasiswa.import.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Interactive Drag & Drop Area --}}
                <div id="dropZone"
                    class="relative border-2 border-dashed rounded-2xl p-8 text-center cursor-pointer transition-all duration-200 mb-6 group hover:border-blue-500 hover:bg-blue-50/30"
                    style="border-color: var(--border); background: var(--secondary);">
                    <input type="file" id="fileInput" name="file" accept=".xlsx,.xls,.csv" class="hidden" required>

                    {{-- Tampilan Kosong (Belum ada file dipilih) --}}
                    <div id="uploadPrompt" class="space-y-3">
                        <div class="w-14 h-14 mx-auto rounded-2xl flex items-center justify-center transition transform group-hover:scale-110 shadow-sm"
                            style="background: var(--card); color: var(--primary);">
                            <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold mb-1">
                                Tarik &amp; letakkan file di sini, atau <span class="font-bold underline" style="color: var(--primary);">telusuri dari komputer</span>
                            </p>
                            <p class="text-xs" style="color: var(--muted-foreground);">
                                Mendukung format file <strong class="font-mono">.xlsx</strong>, <strong class="font-mono">.xls</strong>, atau <strong class="font-mono">.csv</strong> (Maksimal 10 MB)
                            </p>
                        </div>
                    </div>

                    {{-- Tampilan Preview (Ketika file dipilih) --}}
                    <div id="filePreview" class="hidden">
                        <div class="flex items-center justify-between p-4 rounded-xl border bg-white text-left" style="border-color: var(--border);">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 bg-emerald-100 text-emerald-700">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p id="fileName" class="text-sm font-bold font-mono truncate text-gray-900"></p>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span id="fileSize" class="text-xs font-mono text-gray-500"></span>
                                        <span class="text-xs text-emerald-600 font-medium flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Siap diupload
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="removeFileBtn" title="Hapus / Ganti File"
                                class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition">
                                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-3">
                    <button type="submit" id="submitBtn" disabled
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-medium text-white transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                        style="background: var(--primary);">
                        <svg id="submitSpinner" class="hidden animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg id="submitIcon" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        <span id="submitText">Mulai Import Data</span>
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

    {{-- Kolom Kanan: Panduan & Format Kolom (5 Kolom) --}}
    <div class="lg:col-span-5 space-y-6">

        {{-- Petunjuk Struktur Kolom --}}
        <div class="rounded-2xl border p-6" style="background: var(--card); border-color: var(--border);">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-5 h-5" style="color: var(--accent);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="font-serif-display font-bold text-base">Panduan Format Kolom Excel</h3>
            </div>
            <p class="text-xs mb-4" style="color: var(--muted-foreground);">
                Pastikan baris pertama file Anda adalah header dengan nama kolom persis seperti di bawah ini:
            </p>

            <div class="overflow-x-auto rounded-xl border" style="border-color: var(--border);">
                <table class="w-full text-left text-xs">
                    <thead style="background: var(--secondary);">
                        <tr>
                            <th class="py-2.5 px-3 font-semibold">Kolom</th>
                            <th class="py-2.5 px-2 font-semibold">Sifat</th>
                            <th class="py-2.5 px-3 font-semibold">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color: var(--border);">
                        <tr>
                            <td class="py-2 px-3 font-mono font-bold" style="color: var(--primary);">nim</td>
                            <td class="py-2 px-2"><span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-100 text-red-700">Wajib</span></td>
                            <td class="py-2 px-3" style="color: var(--muted-foreground);">Nomor Induk unik, tidak boleh duplikat</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-3 font-mono font-bold" style="color: var(--primary);">nama</td>
                            <td class="py-2 px-2"><span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-100 text-red-700">Wajib</span></td>
                            <td class="py-2 px-3" style="color: var(--muted-foreground);">Nama lengkap mahasiswa</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-3 font-mono font-bold" style="color: var(--primary);">prodi</td>
                            <td class="py-2 px-2"><span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-100 text-red-700">Wajib</span></td>
                            <td class="py-2 px-3" style="color: var(--muted-foreground);">Harus sesuai nama prodi terdaftar</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-3 font-mono font-bold" style="color: var(--primary);">angkatan</td>
                            <td class="py-2 px-2"><span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-red-100 text-red-700">Wajib</span></td>
                            <td class="py-2 px-3" style="color: var(--muted-foreground);">Tahun 4 digit (misal: 2024)</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-3 font-mono font-bold" style="color: var(--primary);">ipk_terakhir</td>
                            <td class="py-2 px-2"><span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-gray-100 text-gray-600">Opsional</span></td>
                            <td class="py-2 px-3" style="color: var(--muted-foreground);">Angka desimal dengan titik (misal: 3.75)</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-3 font-mono font-bold" style="color: var(--primary);">status</td>
                            <td class="py-2 px-2"><span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-gray-100 text-gray-600">Opsional</span></td>
                            <td class="py-2 px-3" style="color: var(--muted-foreground);">aktif / cuti / lulus / DO (default: aktif)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tips Penting --}}
        <div class="rounded-2xl p-5 border" style="background: #fffbeb; border-color: #fde68a;">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="text-xs text-amber-900 space-y-1">
                    <p class="font-bold">Tips Sebelum Mengunggah:</p>
                    <ul class="list-disc list-inside space-y-0.5 opacity-90">
                        <li>Jangan mengubah susunan atau nama kolom di baris paling atas (header).</li>
                        <li>Pastikan tidak ada baris data yang kosong di tengah tabel Excel.</li>
                        <li>NIM harus unik; data dengan NIM yang sudah ada di database akan dilewati.</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

</div>

{{-- Script Interaktif Drag & Drop, File Preview, dan Copy Clip --}}
<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const uploadPrompt = document.getElementById('uploadPrompt');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const removeFileBtn = document.getElementById('removeFileBtn');
    const submitBtn = document.getElementById('submitBtn');
    const submitIcon = document.getElementById('submitIcon');
    const submitSpinner = document.getElementById('submitSpinner');
    const submitText = document.getElementById('submitText');
    const importForm = document.getElementById('importForm');

    function formatBytes(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function setFile(file) {
        if (!file) return;
        fileName.textContent = file.name;
        fileSize.textContent = formatBytes(file.size);
        uploadPrompt.classList.add('hidden');
        filePreview.classList.remove('hidden');
        submitBtn.removeAttribute('disabled');
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }

    function resetFile() {
        fileInput.value = '';
        uploadPrompt.classList.remove('hidden');
        filePreview.classList.add('hidden');
        submitBtn.setAttribute('disabled', 'disabled');
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }

    // Klik pada dropZone untuk memilih file
    dropZone.addEventListener('click', () => {
        fileInput.click();
    });

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            setFile(fileInput.files[0]);
        }
    });

    removeFileBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        resetFile();
    });

    // Drag and Drop Events
    ['dragenter', 'dragover'].forEach(event => {
        dropZone.addEventListener(event, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.add('border-blue-500', 'bg-blue-50/50');
        });
    });

    ['dragleave', 'dragend'].forEach(event => {
        dropZone.addEventListener(event, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove('border-blue-500', 'bg-blue-50/50');
        });
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropZone.classList.remove('border-blue-500', 'bg-blue-50/50');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            setFile(files[0]);
        }
    });

    // Form submit loading state
    importForm.addEventListener('submit', () => {
        submitBtn.setAttribute('disabled', 'disabled');
        submitIcon.classList.add('hidden');
        submitSpinner.classList.remove('hidden');
        submitText.textContent = 'Memproses Data Import...';
    });
</script>
@endsection