<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        h1 { font-size: 16px; margin-bottom: 4px; }
        p { margin: 0 0 12px 0; color: #555; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h1>Laporan Capaian CPL - {{ $kurikulum->nama }}</h1>
    <p>Program Studi: {{ $kurikulum->prodi->nama ?? '-' }} &middot; Tahun Berlaku: {{ $kurikulum->tahun_berlaku_mulai }}</p>

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
            @forelse ($rekap as $r)
                <tr>
                    <td>{{ $r['mahasiswa']->nim }}</td>
                    <td>{{ $r['mahasiswa']->nama }}</td>
                    @foreach ($cplList as $cpl)
                        <td>{{ $r['capaian'][$cpl->id]['nilai'] ?? '-' }}</td>
                    @endforeach
                </tr>
            @empty
                <tr><td colspan="{{ $cplList->count() + 2 }}">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>