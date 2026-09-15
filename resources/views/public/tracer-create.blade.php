@extends('layouts.app')

@section('title', 'Tracer Study - Evaluasi PBM')

@section('page-title', 'Tracer Study Alumni')

@section('page-desc', 'Pelacakan karir dan rekam jejak alumni Politeknik Manufaktur Bandung.')

@section('content')

<div class="w-full">

    {{-- =========================================================
        NAVIGASI
    ========================================================== --}}
    <div class="mb-4 text-left">
        <a
            href="{{ route('alumni.dashboard') }}"
            class="text-sm text-gray-600 underline hover:text-gray-900"
        >
            ← Kembali ke Dashboard
        </a>
    </div>


    {{-- =========================================================
        CEK STATUS MAHASISWA
    ========================================================== --}}
    @if ($mahasiswa->status !== 'lulus')

        <div
            class="rounded-2xl border p-8 md:p-12 text-center w-full"
            style="background: var(--card); border-color: var(--border);"
        >

            <div
                class="w-16 h-16 rounded-2xl mx-auto flex items-center justify-center mb-5"
                style="background: var(--secondary); color: var(--primary);"
            >
                <svg
                    width="32"
                    height="32"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                    />
                </svg>
            </div>

            <h2 class="font-serif-display text-xl md:text-2xl font-bold mb-2">
                Tracer Study Belum Tersedia
            </h2>

            <p
                class="text-sm mb-6 max-w-xl mx-auto leading-relaxed"
                style="color: var(--muted-foreground);"
            >
                Layanan pengisian Tracer Study hanya diperuntukkan bagi mahasiswa
                yang telah menyelesaikan masa studi dan berstatus
                <strong>Lulus</strong>.

                Status akademik Anda saat ini:
                <span
                    class="capitalize font-semibold"
                    style="color: var(--primary);"
                >
                    {{ $mahasiswa->status }}
                </span>.
            </p>

            <a
                href="{{ route('public.mahasiswa.menu', $mahasiswa->nim) }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium text-white"
                style="background: var(--primary);"
            >
                ← Kembali ke Dashboard
            </a>

        </div>

    @else

        {{-- RIWAYAT TRACER STUDY --}}
        <div class="rounded-2xl border overflow-hidden mb-6 w-full" style="background: var(--card); border-color: var(--border);">
            <div class="px-6 py-5 md:px-8 border-b" style="border-color: var(--border);">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="font-serif-display text-xl md:text-2xl font-bold">Riwayat Tracer Study</h2>
                        <p class="text-sm mt-1" style="color: var(--muted-foreground);">Rekam jejak kondisi dan perjalanan karier Anda setelah lulus.</p>
                    </div>
                    <button type="button" onclick="openTracerModal()" class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-sm font-medium text-white" style="background: var(--primary);">+ Tambah Riwayat</button>
                </div>
            </div>
            <div class="p-6 md:p-8">
                @if (session('status'))
                    <div class="mb-5 rounded-xl px-4 py-3 text-sm" style="background: rgba(46,125,50,.10); color: var(--mhs);">{{ session('status') }}</div>
                @endif
                @if ($tracerStudies->isEmpty())
                    <div class="rounded-xl border p-6 text-center" style="border-color: var(--border);">
                        <p class="font-semibold mb-1">Belum ada riwayat Tracer Study</p>
                        <p class="text-sm" style="color: var(--muted-foreground);">Silakan tambahkan data Tracer Study pertama Anda.</p>
                    </div>
                @else
                    <div class="space-y-5">
                        @foreach ($tracerStudies as $tracer)
                            <div class="rounded-xl border overflow-hidden" style="border-color: var(--border);">
                                <div class="px-5 py-4 border-b" style="border-color: var(--border);">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                        <div>
                                            <p class="text-xs uppercase tracking-wider" style="color: var(--muted-foreground);">{{ $tracer->jenis_pengisian === 'awal' ? 'Pengisian Awal' : 'Perubahan Karier' }}</p>
                                            <h3 class="font-semibold text-lg mt-1">{{ $tracer->nama_instansi_saat_ini ?? $tracer->nama_instansi_pertama ?? '-' }}</h3>
                                        </div>
                                        @if ($loop->first)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold" style="background: rgba(46,125,50,.10); color: var(--mhs);">Data Terbaru</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-5">
                                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                                        <div><p class="text-xs mb-1" style="color: var(--muted-foreground);">Tanggal Pengisian</p><p class="font-semibold">{{ $tracer->tanggal_pengisian?->format('d M Y') ?? '-' }}</p></div>
                                        <div><p class="text-xs mb-1" style="color: var(--muted-foreground);">Jenis Pekerjaan</p><p class="font-semibold">{{ $tracer->jenis_pekerjaan_saat_ini ?? $tracer->jenis_pekerjaan_pertama ?? '-' }}</p></div>
                                        <div><p class="text-xs mb-1" style="color: var(--muted-foreground);">Posisi</p><p class="font-semibold">{{ $tracer->posisi_saat_ini ?? $tracer->posisi_pertama ?? '-' }}</p></div>
                                        <div><p class="text-xs mb-1" style="color: var(--muted-foreground);">Program Studi</p><p class="font-semibold">{{ $tracer->prodi->nama ?? '-' }}</p></div>
                                    </div>
                                    <div class="mt-5 pt-4 border-t" style="border-color: var(--border);">
                                        <button type="button" onclick="openDetailModal({{ $tracer->id }})" class="text-sm font-semibold underline" style="color: var(--primary);">Lihat Detail</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- MODAL FORM --}}
        <div id="tracerModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background: rgba(0,0,0,.55);">
            <div class="w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-2xl border" style="background: var(--card); border-color: var(--border);">
                <div class="sticky top-0 z-10 px-6 py-5 md:px-8 border-b" style="background: var(--card); border-color: var(--border);">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="font-serif-display text-xl md:text-2xl font-bold">Form Tracer Study</h2>
                            <p class="text-sm mt-1" style="color: var(--muted-foreground);">
                                {{ $tracerStudies->isEmpty() ? 'Pengisian Awal — Pertanyaan 1–13' : 'Perubahan Karier — Pertanyaan 14–20' }}
                            </p>
                        </div>
                        <button type="button" onclick="closeTracerModal()" class="text-2xl leading-none px-2" style="color: var(--muted-foreground);">&times;</button>
                    </div>
                </div>

                <form method="POST" action="{{ route('alumni.tracer.store') }}" class="p-6 md:p-8">
                    @csrf
                    @if ($errors->any())
                        <div class="mb-6 px-4 py-4 rounded-xl text-sm" style="background: #fdecea; color: #a13d3d;">
                            <p class="font-semibold mb-2">Data belum dapat disimpan:</p>
                            <ul class="list-disc list-inside space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    @if ($tracerStudies->isEmpty())
                        <div class="mb-8">
                            <h3 class="font-semibold text-lg mb-1">Data Alumni</h3>
                            <p class="text-sm" style="color: var(--muted-foreground);">Lengkapi informasi dasar alumni sebelum mengisi riwayat pekerjaan.</p>
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-medium mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_alumni" value="{{ old('nama_alumni', $mahasiswa->nama) }}" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div><label class="block text-sm font-medium mb-1.5">Tahun Masuk Polman <span class="text-red-500">*</span></label><input type="number" name="tahun_masuk" value="{{ old('tahun_masuk') }}" placeholder="Contoh: 2023" min="1900" max="2100" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required></div>
                            <div><label class="block text-sm font-medium mb-1.5">Tahun Lulus dari Polman <span class="text-red-500">*</span></label><input type="number" name="tahun_lulus" value="{{ old('tahun_lulus') }}" placeholder="Contoh: 2027" min="1900" max="2100" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required></div>
                        </div>
                        <div class="mt-5">
                            <label class="block text-sm font-medium mb-1.5">Program Studi <span class="text-red-500">*</span></label>
                            <select name="prodi_id" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required>
                                <option value="">-- Pilih Program Studi --</option>
                                @foreach ($prodiList as $prodi)<option value="{{ $prodi->id }}" {{ old('prodi_id', $mahasiswa->prodi_id) == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama }}</option>@endforeach
                            </select>
                        </div>

                        <div class="mt-8 pt-6 border-t" style="border-color: var(--border);">
                            <h3 class="font-semibold text-lg mb-1">Pendidikan Lanjutan</h3>
                            <p class="text-sm mb-5" style="color: var(--muted-foreground);">Bagi yang melanjutkan ke jenjang Pendidikan S2, silakan lengkapi informasi berikut.</p>
                            <div class="mb-5"><label class="block text-sm font-medium mb-1.5">Apakah saat ini sedang/sudah melanjutkan ke jenjang pendidikan S-2?</label><select name="melanjutkan_s2" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);"><option value="">-- Pilih Jawaban --</option><option value="Ya">Ya</option><option value="Tidak">Tidak</option></select></div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div><label class="block text-sm font-medium mb-1.5">Nama Perguruan Tinggi S2</label><input type="text" name="perguruan_tinggi_s2" value="{{ old('perguruan_tinggi_s2') }}" placeholder="Bagi yang melanjutkan ke jenjang Pendidikan S2" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);"></div>
                                <div><label class="block text-sm font-medium mb-1.5">Jurusan / Program Studi S2</label><input type="text" name="prodi_s2" value="{{ old('prodi_s2') }}" placeholder="Bagi yang melanjutkan ke jenjang Pendidikan S2" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);"></div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t" style="border-color: var(--border);">
                            <h3 class="font-semibold text-lg mb-1">Riwayat Pekerjaan</h3>
                            <p class="text-sm mb-6" style="color: var(--muted-foreground);">Form yang diisi pertama kali setelah lulus.</p>
                            <div class="mb-5"><label class="block text-sm font-medium mb-1.5">Berapa lama menunggu dari mulai lulus untuk mendapatkan pekerjaan pertama? <span class="text-red-500">*</span></label><select name="waktu_tunggu_kerja_pertama" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required><option value="">-- Pilih Jawaban --</option><option value="< 3 bulan">&lt; 3 bulan</option><option value="Antara 3–6 bulan">Antara 3–6 bulan</option><option value="> 6 bulan">&gt; 6 bulan</option></select></div>
                            <div class="mb-5"><label class="block text-sm font-medium mb-1.5">Dari mana mendapatkan info lowongan kerja? <span class="text-red-500">*</span></label><select name="sumber_lowongan_pertama" id="sumber_lowongan_pertama" onchange="toggleOther('sumber_lowongan_pertama','sumber_lowongan_pertama_lainnya')" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required><option value="">-- Pilih Sumber --</option><option>Keluarga/saudara/teman</option><option>Iklan media cetak/koran</option><option>Iklan media elektronik (TV/radio)</option><option>Internet/online</option><option>Almamater/Ikatan Alumni/JCDC</option><option>Yang lain</option></select><input type="text" name="sumber_lowongan_pertama_lainnya" id="sumber_lowongan_pertama_lainnya" placeholder="Tuliskan sumber lainnya" class="hidden w-full rounded-xl border px-4 py-3 text-sm mt-3" style="border-color: var(--border);"></div>
                            <div class="mb-5"><label class="block text-sm font-medium mb-1.5">Jenis pekerjaan pertama <span class="text-red-500">*</span></label><select name="jenis_pekerjaan_pertama" id="jenis_pekerjaan_pertama" onchange="toggleOther('jenis_pekerjaan_pertama','jenis_pekerjaan_pertama_lainnya')" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required><option value="">-- Pilih Jenis Pekerjaan --</option><option>PNS</option><option>Karyawan BUMN/BUMD</option><option>Karyawan swasta</option><option>Wiraswasta/wirausaha</option><option>Yang lain</option></select><input type="text" name="jenis_pekerjaan_pertama_lainnya" id="jenis_pekerjaan_pertama_lainnya" placeholder="Tuliskan jenis pekerjaan lainnya" class="hidden w-full rounded-xl border px-4 py-3 text-sm mt-3" style="border-color: var(--border);"></div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div><label class="block text-sm font-medium mb-1.5">Nama instansi/perusahaan tempat kerja pertama <span class="text-red-500">*</span></label><input type="text" name="nama_instansi_pertama" value="{{ old('nama_instansi_pertama') }}" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required></div>
                                <div><label class="block text-sm font-medium mb-1.5">Tingkat Perusahaan <span class="text-red-500">*</span></label><select name="tingkat_perusahaan_pertama" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required><option value="">-- Pilih Tingkat --</option><option>Lokal/Wilayah</option><option>Nasional</option><option>Multinasional</option></select></div>
                                <div><label class="block text-sm font-medium mb-1.5">Tingkat kesesuaian bidang pekerjaan pertama dengan jurusan/prodi <span class="text-red-500">*</span></label><select name="kesesuaian_bidang_pertama" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required><option value="">-- Pilih Tingkat --</option><option>Rendah</option><option>Sedang</option><option>Tinggi</option></select></div>
                                <div><label class="block text-sm font-medium mb-1.5">Gaji pekerjaan pertama <span class="text-red-500">*</span></label><select name="gaji_pertama" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required><option value="">-- Pilih Gaji --</option><option>&lt; Rp 3.000.000</option><option>Rp 3.000.000 – Rp 4.000.000</option><option>Rp 4.000.000 – Rp 5.000.000</option><option>Rp 5.000.000 – Rp 6.000.000</option><option>&gt; Rp 6.000.000</option></select></div>
                            </div>
                            <div class="mt-5"><label class="block text-sm font-medium mb-1.5">Posisi pada pekerjaan pertama <span class="text-red-500">*</span></label><input type="text" name="posisi_pertama" value="{{ old('posisi_pertama') }}" placeholder="Contoh: Junior Data Engineering" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required></div>
                        </div>

                    @else
                        {{-- DATA ALUMNI TETAP DIISI PADA SETIAP RIWAYAT --}}
                        <div class="mb-8">
                            <h3 class="font-semibold text-lg mb-1">Data Alumni</h3>
                            <p class="text-sm" style="color: var(--muted-foreground);">Data alumni tetap dicatat pada setiap riwayat agar setiap pengisian memiliki informasi yang lengkap.</p>
                        </div>

                        <div class="mb-5"><label class="block text-sm font-medium mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label><input type="text" name="nama_alumni" value="{{ old('nama_alumni', $tracerStudies->first()->nama_alumni ?? $mahasiswa->nama) }}" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium mb-1.5">Tahun Masuk Polman <span class="text-red-500">*</span></label>          
                                <input type="number" name="tahun_masuk" value="{{ old('tahun_masuk', $tracerStudies->first()->tahun_masuk ?? '') }}"placeholder="Contoh: 2023" min="1900" max="2100" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1.5">Tahun Lulus dari Polman <span class="text-red-500">*</span></label>
                                <input type="number" name="tahun_lulus" value="{{ old('tahun_lulus', $tracerStudies->first()->tahun_lulus ?? '') }}"placeholder="Contoh: 2027" min="1900" max="2100" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required>
                            </div>
                        </div>

                        <div class="mt-5">
                            <label class="block text-sm font-medium mb-1.5">Program Studi <span class="text-red-500">*</span></label>
                            <select name="prodi_id" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required>
                                <option value="">-- Pilih Program Studi --</option>

                                @foreach ($prodiList as $prodi)
                                    <option value="{{ $prodi->id }}" {{ old('prodi_id', $tracerStudies->first()->prodi_id ?? $mahasiswa->prodi_id) == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama }}</option>                                @endforeach
                            </select>
                        </div>

                        {{-- PEMISAH --}}
                        <div class="mt-8 pt-6 border-t" style="border-color: var(--border);">
                            <h3 class="font-semibold text-lg mb-1"> Riwayat Pekerjaan Terakhir / Sekarang</h3>
                            <p class="text-sm" style="color: var(--muted-foreground);">Form ini diisi jika Anda berpindah pekerjaan/perusahaan. Riwayat sebelumnya tetap tersimpan.</p>
                        </div>                        
                        <div class="mb-5"><label class="block text-sm font-medium mb-1.5">Jenis pekerjaan saat ini <span class="text-red-500">*</span></label><select name="jenis_pekerjaan_saat_ini" id="jenis_pekerjaan_saat_ini" onchange="toggleOther('jenis_pekerjaan_saat_ini','jenis_pekerjaan_saat_ini_lainnya')" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required><option value="">-- Pilih Jenis Pekerjaan --</option><option>PNS</option><option>Karyawan BUMN/BUMD</option><option>Karyawan swasta</option><option>Wiraswasta/wirausaha</option><option>Yang lain</option></select><input type="text" name="jenis_pekerjaan_saat_ini_lainnya" id="jenis_pekerjaan_saat_ini_lainnya" placeholder="Tuliskan jenis pekerjaan lainnya" class="hidden w-full rounded-xl border px-4 py-3 text-sm mt-3" style="border-color: var(--border);"></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div><label class="block text-sm font-medium mb-1.5">Nama instansi/perusahaan saat ini <span class="text-red-500">*</span></label><input type="text" name="nama_instansi_saat_ini" value="{{ old('nama_instansi_saat_ini') }}" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required></div>
                            <div><label class="block text-sm font-medium mb-1.5">Tingkat Perusahaan <span class="text-red-500">*</span></label><select name="tingkat_perusahaan_saat_ini" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required><option value="">-- Pilih Tingkat --</option><option>Lokal/Wilayah</option><option>Nasional</option><option>Multinasional</option></select></div>
                            <div><label class="block text-sm font-medium mb-1.5">Sudah berapa kali pindah pekerjaan/perusahaan? <span class="text-red-500">*</span></label><select name="jumlah_pindah_kerja" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required><option value="">-- Pilih Jumlah --</option><option>1 kali</option><option>2 kali</option><option>3 kali</option><option>&gt; 3 kali</option></select></div>
                            <div><label class="block text-sm font-medium mb-1.5">Latar belakang pindah <span class="text-red-500">*</span></label><select name="alasan_pindah" id="alasan_pindah" onchange="toggleOther('alasan_pindah','alasan_pindah_lainnya')" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required><option value="">-- Pilih Alasan --</option><option>Gaji tidak memadai</option><option>Bidang pekerjaan tidak cocok</option><option>Suasana kerja tidak kondusif</option><option>Tidak ada jaminan hari tua</option><option>Masalah keluarga</option><option>Yang lain</option></select><input type="text" name="alasan_pindah_lainnya" id="alasan_pindah_lainnya" placeholder="Tuliskan alasan lainnya" class="hidden w-full rounded-xl border px-4 py-3 text-sm mt-3" style="border-color: var(--border);"></div>
                        </div>
                        <div class="mt-5"><label class="block text-sm font-medium mb-1.5">Sumber info lowongan pekerjaan <span class="text-red-500">*</span></label><select name="sumber_lowongan_saat_ini" id="sumber_lowongan_saat_ini" onchange="toggleOther('sumber_lowongan_saat_ini','sumber_lowongan_saat_ini_lainnya')" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required><option value="">-- Pilih Sumber --</option><option>Keluarga/saudara/teman</option><option>Media cetak/koran</option><option>Media elektronik TV/radio</option><option>Internet/online</option><option>Almamater/Ikatan Alumni/JCDC</option><option>Yang lain</option></select><input type="text" name="sumber_lowongan_saat_ini_lainnya" id="sumber_lowongan_saat_ini_lainnya" placeholder="Tuliskan sumber lainnya" class="hidden w-full rounded-xl border px-4 py-3 text-sm mt-3" style="border-color: var(--border);"></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                            <div><label class="block text-sm font-medium mb-1.5">Gaji saat ini <span class="text-red-500">*</span></label><select name="gaji_saat_ini" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required><option value="">-- Pilih Gaji --</option><option>&lt; Rp 3.000.000</option><option>Rp 3.000.000 – Rp 4.000.000</option><option>Rp 4.000.000 – Rp 5.000.000</option><option>Rp 5.000.000 – Rp 6.000.000</option><option>&gt; Rp 6.000.000</option></select></div>
                            <div><label class="block text-sm font-medium mb-1.5">Posisi pekerjaan saat ini <span class="text-red-500">*</span></label><input type="text" name="posisi_saat_ini" value="{{ old('posisi_saat_ini') }}" placeholder="Contoh: Junior Data Engineering" class="w-full rounded-xl border px-4 py-3 text-sm" style="border-color: var(--border);" required></div>
                        </div>
                    @endif

                    <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-6 mt-8 border-t" style="border-color: var(--border);">
                        <button type="button" onclick="closeTracerModal()" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-medium" style="background: var(--secondary); color: var(--foreground);">Batal</button>
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl text-sm font-semibold text-white hover:opacity-95 transition" style="background: var(--primary);">+ Simpan Riwayat</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL DETAIL --}}
        <div id="detailModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" style="background: rgba(0,0,0,.55);">
            <div class="w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-2xl border" style="background: var(--card); border-color: var(--border);">
                <div class="sticky top-0 z-10 px-6 py-5 border-b flex items-center justify-between" style="background: var(--card); border-color: var(--border);">
                    <div><h2 class="font-serif-display text-xl font-bold">Detail Tracer Study</h2><p class="text-sm mt-1" style="color: var(--muted-foreground);">Informasi lengkap riwayat pengisian.</p></div>
                    <button type="button" onclick="closeDetailModal()" class="text-2xl leading-none px-2" style="color: var(--muted-foreground);">&times;</button>
                </div>
                <div id="detailContent" class="p-6 md:p-8"></div>
            </div>
        </div>

        @foreach ($tracerStudies as $tracer)
            <template id="detail-{{ $tracer->id }}">
                <div class="space-y-6">

                    <div>
                        <p
                            class="text-xs uppercase tracking-wider"
                            style="color: var(--muted-foreground);"
                        >
                            {{ $tracer->jenis_pengisian === 'awal' ? 'Pengisian Awal' : 'Perubahan Karier' }}
                        </p>

                        <h3 class="font-semibold text-xl mt-1">
                            {{ $tracer->tanggal_pengisian?->format('d M Y') ?? '-' }}
                        </h3>
                    </div>

                    {{-- DATA ALUMNI --}}
                    <div>
                        <h4 class="font-semibold mb-3">Data Alumni</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs" style="color:var(--muted-foreground);">Nama</p>
                                <p class="font-medium">{{ $tracer->nama_alumni ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-xs" style="color:var(--muted-foreground);">Program Studi</p>
                                <p class="font-medium">{{ $tracer->prodi->nama ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-xs" style="color:var(--muted-foreground);">Tahun Masuk</p>
                                <p class="font-medium">{{ $tracer->tahun_masuk ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-xs" style="color:var(--muted-foreground);">Tahun Lulus</p>
                                <p class="font-medium">{{ $tracer->tahun_lulus ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-xs" style="color:var(--muted-foreground);">Melanjutkan S2</p>
                                <p class="font-medium">{{ $tracer->melanjutkan_s2 ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-xs" style="color:var(--muted-foreground);">Perguruan Tinggi S2</p>
                                <p class="font-medium">{{ $tracer->perguruan_tinggi_s2 ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-xs" style="color:var(--muted-foreground);">Prodi S2</p>
                                <p class="font-medium">{{ $tracer->prodi_s2 ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- DETAIL PEKERJAAN --}}
                    @if ($tracer->jenis_pengisian === 'awal')

                        <div>
                            <h4 class="font-semibold mb-3">Pekerjaan Pertama</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @php
                                    $firstSource = $tracer->sumber_lowongan_pertama === 'Yang lain'
                                        ? $tracer->sumber_lowongan_pertama_lainnya
                                        : $tracer->sumber_lowongan_pertama;

                                    $firstType = $tracer->jenis_pekerjaan_pertama === 'Yang lain'
                                        ? $tracer->jenis_pekerjaan_pertama_lainnya
                                        : $tracer->jenis_pekerjaan_pertama;
                                @endphp

                                <div>
                                    <p class="text-xs" style="color:var(--muted-foreground);">Waktu Tunggu</p>
                                    <p class="font-medium">{{ $tracer->waktu_tunggu_kerja_pertama ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-xs" style="color:var(--muted-foreground);">Sumber Lowongan</p>
                                    <p class="font-medium">{{ $firstSource ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-xs" style="color:var(--muted-foreground);">Jenis Pekerjaan</p>
                                    <p class="font-medium">{{ $firstType ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-xs" style="color:var(--muted-foreground);">Instansi / Perusahaan</p>
                                    <p class="font-medium">{{ $tracer->nama_instansi_pertama ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-xs" style="color:var(--muted-foreground);">Tingkat Perusahaan</p>
                                    <p class="font-medium">{{ $tracer->tingkat_perusahaan_pertama ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-xs" style="color:var(--muted-foreground);">Kesesuaian Bidang</p>
                                    <p class="font-medium">{{ $tracer->kesesuaian_bidang_pertama ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-xs" style="color:var(--muted-foreground);">Gaji</p>
                                    <p class="font-medium">{{ $tracer->gaji_pertama ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-xs" style="color:var(--muted-foreground);">Posisi</p>
                                    <p class="font-medium">{{ $tracer->posisi_pertama ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                    @else

                        <div>
                            <h4 class="font-semibold mb-3">Pekerjaan Saat Ini / Terakhir</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @php
                                    $currentType = $tracer->jenis_pekerjaan_saat_ini === 'Yang lain'
                                        ? $tracer->jenis_pekerjaan_saat_ini_lainnya
                                        : $tracer->jenis_pekerjaan_saat_ini;

                                    $reason = $tracer->alasan_pindah === 'Yang lain'
                                        ? $tracer->alasan_pindah_lainnya
                                        : $tracer->alasan_pindah;

                                    $currentSource = $tracer->sumber_lowongan_saat_ini === 'Yang lain'
                                        ? $tracer->sumber_lowongan_saat_ini_lainnya
                                        : $tracer->sumber_lowongan_saat_ini;
                                @endphp

                                <div>
                                    <p class="text-xs" style="color:var(--muted-foreground);">Jenis Pekerjaan</p>
                                    <p class="font-medium">{{ $currentType ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-xs" style="color:var(--muted-foreground);">Instansi / Perusahaan</p>
                                    <p class="font-medium">{{ $tracer->nama_instansi_saat_ini ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-xs" style="color:var(--muted-foreground);">Tingkat Perusahaan</p>
                                    <p class="font-medium">{{ $tracer->tingkat_perusahaan_saat_ini ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-xs" style="color:var(--muted-foreground);">Jumlah Pindah</p>
                                    <p class="font-medium">{{ $tracer->jumlah_pindah_kerja ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-xs" style="color:var(--muted-foreground);">Alasan Pindah</p>
                                    <p class="font-medium">{{ $reason ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-xs" style="color:var(--muted-foreground);">Sumber Lowongan</p>
                                    <p class="font-medium">{{ $currentSource ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-xs" style="color:var(--muted-foreground);">Gaji Saat Ini</p>
                                    <p class="font-medium">{{ $tracer->gaji_saat_ini ?? '-' }}</p>
                                </div>

                                <div>
                                    <p class="text-xs" style="color:var(--muted-foreground);">Posisi Saat Ini</p>
                                    <p class="font-medium">{{ $tracer->posisi_saat_ini ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                    @endif

                    {{-- TOMBOL HAPUS --}}
                    <div
                        class="mt-6 pt-5 border-t"
                        style="border-color: var(--border);"
                    >
                        <form
                            method="POST"
                            action="{{ route('alumni.tracer.destroy', $tracer->id) }}"
                            onsubmit="return confirm('Yakin ingin menghapus riwayat Tracer Study ini? Data yang dihapus tidak dapat dikembalikan.');"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="w-full px-4 py-3 rounded-lg text-sm font-semibold"
                                style="background: #dc2626; color: white;"
                            >
                                Hapus Riwayat Tracer Study
                            </button>
                        </form>
                    </div>

                </div>
            </template>
        @endforeach

        <script>
            function openTracerModal(){const m=document.getElementById('tracerModal');if(!m)return;m.classList.remove('hidden');m.classList.add('flex');document.body.classList.add('overflow-hidden');}
            function closeTracerModal(){const m=document.getElementById('tracerModal');if(!m)return;m.classList.add('hidden');m.classList.remove('flex');document.body.classList.remove('overflow-hidden');}
            function openDetailModal(id){const m=document.getElementById('detailModal'),c=document.getElementById('detailContent'),t=document.getElementById('detail-'+id);if(!m||!c||!t)return;c.innerHTML=t.innerHTML;m.classList.remove('hidden');m.classList.add('flex');document.body.classList.add('overflow-hidden');}
            function closeDetailModal(){const m=document.getElementById('detailModal');if(!m)return;m.classList.add('hidden');m.classList.remove('flex');document.body.classList.remove('overflow-hidden');}
            function toggleOther(selectId,inputId){const s=document.getElementById(selectId),i=document.getElementById(inputId);if(!s||!i)return;if(s.value==='Yang lain'){i.classList.remove('hidden');i.required=true;}else{i.classList.add('hidden');i.required=false;i.value='';}}
            document.addEventListener('DOMContentLoaded',function(){['sumber_lowongan_pertama','jenis_pekerjaan_pertama','jenis_pekerjaan_saat_ini','alasan_pindah','sumber_lowongan_saat_ini'].forEach(function(x){const i=x+'_lainnya';toggleOther(x,i);});@if($errors->any()) openTracerModal(); @endif});
            document.getElementById('tracerModal')?.addEventListener('click',function(e){if(e.target===this)closeTracerModal();});
            document.getElementById('detailModal')?.addEventListener('click',function(e){if(e.target===this)closeDetailModal();});
            document.addEventListener('keydown',function(e){if(e.key==='Escape'){closeTracerModal();closeDetailModal();}});
        </script>

    @endif

</div>

@endsection
