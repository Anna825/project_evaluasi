@extends('layouts.app')

@section('title', 'Detail Tracer Study - Kaprodi')
@section('page-title', 'Detail Tracer Study')
@section('page-desc', 'Riwayat pengisian Tracer Study alumni Program Studi.')

@section('content')
    <div class="mb-6">
        <a href="{{ route('kaprodi.tracer-study.index') }}" class="inline-flex items-center gap-2 text-sm font-medium hover:underline" style="color: var(--primary);">
            ← Kembali ke Rekap Tracer Study
        </a>
    </div>

    <div class="ui-card overflow-hidden mb-6">
        <div class="px-6 py-5 border-b" style="border-color: var(--border);">
            <h2 class="font-serif-display text-2xl">Data Alumni</h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach ([
                'Nama Alumni' => $tracerStudy->nama_alumni,
                'NIM' => $tracerStudy->mahasiswa->nim ?? '-',
                'Program Studi' => $tracerStudy->prodi->nama ?? $tracerStudy->mahasiswa->prodi->nama ?? '-',
                'Tahun Masuk' => $tracerStudy->tahun_masuk,
                'Tahun Lulus' => $tracerStudy->tahun_lulus,
                'Periode Pelacakan' => $tracerStudy->periode_pelacakan,
                'Tanggal Pengisian' => $tracerStudy->tanggal_pengisian?->format('d-m-Y'),
                'Jenis Pengisian' => $tracerStudy->jenis_pengisian === 'awal' ? 'Pengisian Awal' : 'Perubahan Karier',
            ] as $label => $value)
                <div>
                    <p class="text-sm mb-1" style="color: var(--muted-foreground);">{{ $label }}</p>
                    <p class="font-medium">{{ $value ?? '-' }}</p>
                </div>
            @endforeach
        </div>
    </div>

    @if ($tracerStudy->jenis_pengisian === 'awal')
        <div class="ui-card overflow-hidden mb-6">
            <div class="px-6 py-5 border-b" style="border-color: var(--border);"><h2 class="font-serif-display text-2xl">Pendidikan Lanjutan</h2></div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-5">
                <div><p class="ui-label">Melanjutkan S2</p><p class="font-medium">{{ $tracerStudy->melanjutkan_s2 ?? '-' }}</p></div>
                <div><p class="ui-label">Perguruan Tinggi S2</p><p class="font-medium">{{ $tracerStudy->perguruan_tinggi_s2 ?? '-' }}</p></div>
                <div><p class="ui-label">Program Studi S2</p><p class="font-medium">{{ $tracerStudy->prodi_s2 ?? '-' }}</p></div>
            </div>
        </div>

        <div class="ui-card overflow-hidden mb-6">
            <div class="px-6 py-5 border-b" style="border-color: var(--border);"><h2 class="font-serif-display text-2xl">Pekerjaan Pertama</h2></div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach ([
                    'Waktu Tunggu' => $tracerStudy->waktu_tunggu_kerja_pertama,
                    'Sumber Lowongan' => $tracerStudy->sumber_lowongan_pertama,
                    'Sumber Lowongan Lainnya' => $tracerStudy->sumber_lowongan_pertama_lainnya,
                    'Jenis Pekerjaan' => $tracerStudy->jenis_pekerjaan_pertama,
                    'Jenis Pekerjaan Lainnya' => $tracerStudy->jenis_pekerjaan_pertama_lainnya,
                    'Nama Instansi' => $tracerStudy->nama_instansi_pertama,
                    'Tingkat Perusahaan' => $tracerStudy->tingkat_perusahaan_pertama,
                    'Kesesuaian Bidang' => $tracerStudy->kesesuaian_bidang_pertama,
                    'Gaji' => $tracerStudy->gaji_pertama,
                    'Posisi' => $tracerStudy->posisi_pertama,
                ] as $label => $value)
                    <div><p class="ui-label">{{ $label }}</p><p class="font-medium">{{ $value ?? '-' }}</p></div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="ui-card overflow-hidden mb-6">
        <div class="px-6 py-5 border-b" style="border-color: var(--border);"><h2 class="font-serif-display text-2xl">Pekerjaan Saat Ini / Terakhir</h2></div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach ([
                'Jenis Pekerjaan' => $tracerStudy->jenis_pekerjaan_saat_ini,
                'Jenis Pekerjaan Lainnya' => $tracerStudy->jenis_pekerjaan_saat_ini_lainnya,
                'Nama Instansi' => $tracerStudy->nama_instansi_saat_ini,
                'Tingkat Perusahaan' => $tracerStudy->tingkat_perusahaan_saat_ini,
                'Jumlah Pindah Kerja' => $tracerStudy->jumlah_pindah_kerja,
                'Alasan Pindah' => $tracerStudy->alasan_pindah,
                'Alasan Pindah Lainnya' => $tracerStudy->alasan_pindah_lainnya,
                'Sumber Lowongan' => $tracerStudy->sumber_lowongan_saat_ini,
                'Sumber Lowongan Lainnya' => $tracerStudy->sumber_lowongan_saat_ini_lainnya,
                'Gaji' => $tracerStudy->gaji_saat_ini,
                'Posisi' => $tracerStudy->posisi_saat_ini,
            ] as $label => $value)
                <div><p class="ui-label">{{ $label }}</p><p class="font-medium">{{ $value ?? '-' }}</p></div>
            @endforeach
        </div>
    </div>
@endsection
