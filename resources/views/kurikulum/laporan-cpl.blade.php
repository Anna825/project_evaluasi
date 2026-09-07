<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Capaian CPL</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-green-700 text-white px-6 py-4">
        <span class="font-bold">Evaluasi PBM - Laporan Capaian CPL ({{ $kurikulum->nama }})</span>
    </nav>

    <div class="p-8">
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3">NIM</th>
                        <th class="p-3">Nama</th>
                        @foreach ($cplList as $cpl)
                            <th class="p-3">{{ $cpl->kode }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rekap as $r)
                        <tr class="border-t">
                            <td class="p-3">{{ $r['mahasiswa']->nim }}</td>
                            <td class="p-3">{{ $r['mahasiswa']->nama }}</td>
                            @foreach ($cplList as $cpl)
                                <td class="p-3">
                                    {{ $r['capaian'][$cpl->id]['nilai'] ?? '-' }}
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr><td colspan="{{ $cplList->count() + 2 }}" class="p-3 text-center text-gray-500">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <a href="{{ route('kurikulum.show', $kurikulum) }}" class="inline-block mt-4 text-blue-600 hover:underline">&larr; Kembali</a>
    </div>
</body>
</html>