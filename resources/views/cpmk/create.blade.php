
@extends('layouts.app')

@section('title', 'Tambah CPMK - Evaluasi PBM')
@section('page-title', 'Tambah CPMK')
@section('page-desc', 'Buat CPMK dan petakan ke CPL yang relevan.')

@section('content')
<div class="mb-4">
    <a href="{{ route('mata-kuliah.index') }}"
       class="text-sm underline"
       style="color: var(--muted-foreground);">
        ← Kembali ke Mata Kuliah
    </a>
</div>

<div class="w-full rounded-2xl border p-6 sm:p-8"
     style="background: var(--card); border-color: var(--border);">

    @if ($errors->any())
        <div class="mb-4 px-4 py-3 rounded-xl text-sm"
             style="background: #fdecea; color: #a13d3d;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('cpmk.store') }}">
        @csrf

        {{-- Pilih Mata Kuliah --}}
        <div class="mb-6">
            <label for="mata_kuliah_id"
                   class="block text-sm font-medium mb-2">
                Mata Kuliah <span class="text-red-500">*</span>
            </label>

            <select id="mata_kuliah_id"
                    name="mata_kuliah_id"
                    required
                    class="w-full rounded-xl border px-3 py-3 text-sm"
                    style="border-color: var(--border);">
                <option value="">-- Pilih Mata Kuliah --</option>

                @foreach ($mataKuliahList as $mk)
                    <option value="{{ $mk->id }}"
                            data-cpls="{{ $mk->kurikulum?->cpl->toJson() }}"
                            {{ (string) old('mata_kuliah_id') === (string) $mk->id ? 'selected' : '' }}>
                        {{ $mk->kode }} — {{ $mk->nama }}
                        ({{ $mk->kurikulum?->nama ?? 'Tanpa kurikulum' }})
                    </option>
                @endforeach
            </select>

            @if ($mataKuliahList->isEmpty())
                <p class="text-sm mt-2" style="color: #a13d3d;">
                    Belum ada mata kuliah. Pastikan data mata kuliah sudah tersedia
                    pada kurikulum yang dibuat Kaprodi.
                </p>
            @endif
        </div>

        {{-- Kode CPMK --}}
        <div class="mb-4">
            <label for="kode" class="block text-sm font-medium mb-1">
                Kode CPMK <span class="text-red-500">*</span>
            </label>
            <input id="kode"
                   type="text"
                   name="kode"
                   value="{{ old('kode') }}"
                   placeholder="Contoh: CPMK-01"
                   required
                   class="w-full rounded-xl border px-3 py-2 text-sm"
                   style="border-color: var(--border);">
        </div>

        {{-- Deskripsi --}}
        <div class="mb-6">
            <label for="deskripsi" class="block text-sm font-medium mb-1">
                Deskripsi CPMK <span class="text-red-500">*</span>
            </label>
            <textarea id="deskripsi"
                      name="deskripsi"
                      rows="4"
                      required
                      class="w-full rounded-xl border px-3 py-2 text-sm"
                      style="border-color: var(--border);">{{ old('deskripsi') }}</textarea>
        </div>

        {{-- Pemetaan CPL --}}
        <div class="mb-6">
            <label class="block text-sm font-medium mb-2">
                Pemetaan CPL <span class="text-red-500">*</span>
            </label>

            <p class="text-xs mb-3" style="color: var(--muted-foreground);">
                Pilih CPL yang sesuai dengan CPMK ini dan tentukan bobot pemetaannya.
            </p>

            <div id="cpl-container" class="space-y-3">
                <p class="text-sm" style="color: var(--muted-foreground);">
                    Pilih mata kuliah terlebih dahulu untuk melihat CPL.
                </p>
            </div>
        </div>

        <div class="flex flex-wrap gap-2">
            <button type="submit"
                    class="px-4 py-2 rounded-xl text-sm font-medium text-white"
                    style="background: var(--primary);">
                Simpan CPMK
            </button>

            <a href="{{ route('mata-kuliah.index') }}"
               class="px-4 py-2 rounded-xl text-sm font-medium"
               style="background: var(--secondary); color: var(--foreground);">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const mataKuliahSelect = document.getElementById('mata_kuliah_id');
    const cplContainer = document.getElementById('cpl-container');

    const oldCplIds = @json(array_map('strval', old('cpl_ids', [])));
    const oldBobot = @json(old('bobot', []));

    function tampilkanCpl() {
        cplContainer.replaceChildren();

        const option = mataKuliahSelect.selectedOptions[0];

        if (!option || !option.value) {
            cplContainer.textContent =
                'Pilih mata kuliah terlebih dahulu untuk melihat CPL.';
            return;
        }

        let cpls = [];

        try {
            cpls = JSON.parse(option.dataset.cpls || '[]');
        } catch (error) {
            cpls = [];
        }

        if (!cpls.length) {
            cplContainer.textContent =
                'Belum ada CPL pada kurikulum mata kuliah ini.';
            return;
        }

        cpls.forEach(function (cpl) {
            const card = document.createElement('div');
            card.className = 'rounded-xl border p-4';
            card.style.borderColor = 'var(--border)';

            const label = document.createElement('label');
            label.className = 'flex items-start gap-3';

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.name = 'cpl_ids[]';
            checkbox.value = cpl.id;
            checkbox.className = 'mt-1';
            checkbox.checked = oldCplIds.includes(String(cpl.id));

            const info = document.createElement('span');
            info.className = 'flex-1 min-w-0';

            const title = document.createElement('span');
            title.className = 'block text-sm font-semibold';
            title.textContent = cpl.kode +
                (cpl.domain ? ' (' + cpl.domain + ')' : '');

            const description = document.createElement('span');
            description.className = 'block text-sm mt-1';
            description.style.color = 'var(--muted-foreground)';
            description.textContent = cpl.deskripsi || '';

            info.append(title, description);
            label.append(checkbox, info);

            const weightWrapper = document.createElement('div');
            weightWrapper.className = 'mt-3';

            const weightLabel = document.createElement('label');
            weightLabel.className = 'block text-xs font-medium mb-1';
            weightLabel.textContent = 'Bobot pemetaan (%)';

            const weight = document.createElement('input');
            weight.type = 'number';
            weight.name = 'bobot[' + cpl.id + ']';
            weight.min = '0';
            weight.max = '100';
            weight.step = '0.01';
            weight.value = oldBobot[cpl.id] ?? '100';
            weight.className = 'w-full rounded-xl border px-3 py-2 text-sm';
            weight.style.borderColor = 'var(--border)';
            weight.required = checkbox.checked;
            weight.disabled = !checkbox.checked;

            checkbox.addEventListener('change', function () {
                weight.disabled = !checkbox.checked;
                weight.required = checkbox.checked;
            });

            weightWrapper.append(weightLabel, weight);
            card.append(label, weightWrapper);
            cplContainer.append(card);
        });
    }

    mataKuliahSelect.addEventListener('change', tampilkanCpl);
    tampilkanCpl();
});
</script>
@endsection
