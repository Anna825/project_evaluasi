@extends('layouts.app')

@section('title', 'Ajukan Penelitian/PKM - Evaluasi PBM')
@section('page-title', 'Ajukan Penelitian/PKM')
@section('page-desc', '')

@section('content')
    {{-- Kembali --}}
    <div class="mb-4 text-left">
        <a
            href="{{ route('penelitian-pkm.index') }}"
            class="text-sm underline hover:opacity-70"
            style="color: var(--muted-foreground);"
        >
            ← Kembali ke Penelitian & PKM
        </a>
    </div>
    <div class="w-full rounded-2xl border p-6 sm:p-8" style="background: var(--card); border-color: var(--border);">        
        @if ($errors->any())
            <div class="mb-4 px-4 py-3 rounded-xl text-sm" style="background: #fdecea; color: #a13d3d;">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('penelitian-pkm.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Judul</label>
                <input type="text" name="judul" value="{{ old('judul') }}" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Jenis</label>
                <select name="jenis" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
                    <option value="Penelitian" {{ old('jenis') == 'Penelitian' ? 'selected' : '' }}>Penelitian</option>
                    <option value="PKM" {{ old('jenis') == 'PKM' ? 'selected' : '' }}>PKM</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Kategori Pendanaan (opsional)</label>
                <input type="text" name="kategori_pendanaan" value="{{ old('kategori_pendanaan') }}" placeholder="Internal, DIKTI, dsb" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Tahun Akademik</label>
                <select name="tahun_akademik_id" class="w-full rounded-xl border px-3 py-2 text-sm" style="border-color: var(--border);" required>
                    <option value="">-- Pilih --</option>
                    @foreach ($tahunAkademikList as $ta)
                        <option value="{{ $ta->id }}" {{ old('tahun_akademik_id') == $ta->id ? 'selected' : '' }}>{{ $ta->label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">
                    Dosen Anggota <span style="color: var(--muted-foreground);">(opsional)</span>
                </label>

                <p
                    class="text-xs mb-3"
                    style="color: var(--muted-foreground);"
                >
                    Ketik nama dosen untuk mencari dan memilih anggota tim.
                </p>

                {{-- Input pencarian --}}
                <div class="relative">
                    <input
                        type="text"
                        id="dosen-search"
                        placeholder="Cari nama dosen..."
                        autocomplete="off"
                        class="w-full rounded-xl border px-3 py-2.5 text-sm"
                        style="border-color: var(--border);"
                    >

                    {{-- Hasil pencarian --}}
                    <div
                        id="dosen-results"
                        class="hidden absolute z-20 left-0 right-0 mt-1 rounded-xl border shadow-lg overflow-hidden"
                        style="background: var(--card); border-color: var(--border);"
                    ></div>
                </div>

                {{-- Dosen yang dipilih --}}
                <div id="selected-dosen" class="mt-3 flex flex-wrap gap-2"></div>

                {{-- ID dosen yang dikirim ke Laravel --}}
                <div id="selected-dosen-inputs"></div>
            </div>

            <script>
                const dosenList = @json(
                    $dosenList->map(fn ($dosen) => [
                        'id' => $dosen->id,
                        'nama' => $dosen->nama,
                    ])->values()
                );

                const searchInput = document.getElementById('dosen-search');
                const resultsContainer = document.getElementById('dosen-results');
                const selectedContainer = document.getElementById('selected-dosen');
                const selectedInputs = document.getElementById('selected-dosen-inputs');

                let selectedDosen = [];

                searchInput.addEventListener('input', function () {
                    const keyword = this.value.trim().toLowerCase();

                    resultsContainer.innerHTML = '';

                    if (!keyword) {
                        resultsContainer.classList.add('hidden');
                        return;
                    }

                    const results = dosenList.filter(dosen =>
                        dosen.nama.toLowerCase().includes(keyword) &&
                        !selectedDosen.some(selected => selected.id === dosen.id)
                    );

                    if (results.length === 0) {
                        resultsContainer.innerHTML = `
                            <div class="px-4 py-3 text-sm" style="color: var(--muted-foreground);">
                                Dosen tidak ditemukan.
                            </div>
                        `;

                        resultsContainer.classList.remove('hidden');
                        return;
                    }

                    results.forEach(dosen => {
                        const item = document.createElement('button');

                        item.type = 'button';
                        item.className = 'w-full text-left px-4 py-3 text-sm hover:opacity-70';

                        item.style.borderBottom = '1px solid var(--border)';
                        item.textContent = dosen.nama;

                        item.addEventListener('click', function () {
                            addDosen(dosen);
                        });

                        resultsContainer.appendChild(item);
                    });

                    resultsContainer.classList.remove('hidden');
                });

                function addDosen(dosen) {
                    if (selectedDosen.some(selected => selected.id === dosen.id)) {
                        return;
                    }

                    selectedDosen.push(dosen);

                    renderSelectedDosen();

                    searchInput.value = '';
                    resultsContainer.innerHTML = '';
                    resultsContainer.classList.add('hidden');

                    searchInput.focus();
                }

                function removeDosen(id) {
                    selectedDosen = selectedDosen.filter(dosen => dosen.id !== id);

                    renderSelectedDosen();
                }

                function renderSelectedDosen() {
                    selectedContainer.innerHTML = '';
                    selectedInputs.innerHTML = '';

                    selectedDosen.forEach(dosen => {
                        const badge = document.createElement('div');

                        badge.className = 'inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm';
                        badge.style.background = 'var(--secondary)';

                        badge.innerHTML = `
                            <span>${dosen.nama}</span>
                            <button
                                type="button"
                                class="font-bold hover:opacity-60"
                                aria-label="Hapus ${dosen.nama}"
                                onclick="removeDosen(${dosen.id})"
                            >
                                ×
                            </button>
                        `;

                        selectedContainer.appendChild(badge);

                        const input = document.createElement('input');

                        input.type = 'hidden';
                        input.name = 'anggota[]';
                        input.value = dosen.id;

                        selectedInputs.appendChild(input);
                    });
                }

                document.addEventListener('click', function (event) {
                    if (
                        !searchInput.contains(event.target) &&
                        !resultsContainer.contains(event.target)
                    ) {
                        resultsContainer.classList.add('hidden');
                    }
                });
            </script>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 rounded-xl text-sm font-medium text-white" style="background: var(--primary);">Ajukan</button>
                <a href="{{ route('penelitian-pkm.index') }}" class="px-4 py-2 rounded-xl text-sm font-medium" style="background: var(--secondary); color: var(--foreground);">Batal</a>
            </div>
        </form>
    </div>
@endsection