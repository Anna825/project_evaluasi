<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        h1 { font-size: 16px; margin-bottom: 4px; }
        h2 { font-size: 13px; margin: 16px 0 8px; }
        p { margin: 0 0 12px 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>
    <h1>Laporan Capaian CPL - {{ $kurikulum->nama }}</h1>
    <p>Program Studi: {{ $kurikulum->prodi->nama ?? '-' }} &middot; Tahun Berlaku: {{ $kurikulum->tahun_berlaku_mulai }}</p>

    @forelse ($rekapPerKelas as $dataKelas)
        @if (!$loop->first)
            <div class="page-break"></div>
        @endif
        <h2>Kelas: {{ $dataKelas['kelas']->nama_kelas ?? 'Belum memiliki kelas' }}</h2>
        <table>
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    @foreach ($cplList as $cpl)
                        <th>{{ $cpl->kode }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($dataKelas['rekap'] as $r)
                    <tr>
                        <td>{{ $r['mahasiswa']->nim }}</td>
                        <td>{{ $r['mahasiswa']->nama }}</td>
                        @foreach ($cplList as $cpl)
                            <td>{{ $r['capaian'][$cpl->id]['nilai'] ?? '-' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <p>Belum ada data mahasiswa untuk kurikulum ini.</p>
    @endforelse
</body>
</html>
