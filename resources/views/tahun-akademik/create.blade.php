<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Tahun Akademik</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-blue-700 text-white px-6 py-4">
        <span class="font-bold">Evaluasi PBM - Tambah Tahun Akademik</span>
    </nav>

    <div class="p-8 max-w-xl mx-auto">
        <div class="bg-white p-6 rounded-lg shadow">
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('tahun-akademik.store') }}">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-1">Label (contoh: 2026/2027)</label>
                    <input type="text" name="label" value="{{ old('label') }}"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
                    <a href="{{ route('tahun-akademik.index') }}" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>