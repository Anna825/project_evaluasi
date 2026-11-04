@extends('layouts.app')

@section('title', 'Mata Kuliah Saya - Evaluasi PBM')
@section('page-title', 'Mata Kuliah Saya')
@section('page-desc', 'Mata kuliah yang Anda ampu.')
@section('content')

<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold">
                Daftar Mata Kuliah
            </h2>
            <p class="text-sm mt-1" style="color: var(--muted-foreground);">
                Kelola CPMK dan RPS untuk mata kuliah yang tersedia.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <button type="button" onclick="document.getElementById('modalTambahMataKuliahDosen').showModal()" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium text-white" style="background: var(--primary);"><span class="text-lg leading-none">+</span>Tambah Mata Kuliah</button>

            <a href="{{ route('cpmk.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium border"
            style="border-color: var(--border); color: var(--primary); background: var(--card);">
            <span class="text-lg leading-none">+</span>
                Tambah CPMK
            </a>
        </div>
    </div>


    @if($errors->any())<div class="mb-4 rounded-xl border p-4 text-sm" style="background:#fef2f2;border-color:#fecaca;color:#991b1b"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <dialog id="modalTambahMataKuliahDosen" class="ui-modal">
        <form method="POST" action="{{ route('mata-kuliah.store') }}" class="ui-modal__panel">
            @csrf <input type="hidden" name="form_modal" value="mata-kuliah-dosen-create">
            <div class="ui-modal__header"><div><h2 class="text-lg font-semibold">Tambah Mata Kuliah</h2><p class="text-sm" style="color:var(--ui-muted)">Mata kuliah dan kelas yang Anda ampu.</p></div><button type="button" class="ui-modal__close" onclick="this.closest('dialog').close()">×</button></div>
            <div class="ui-modal__body space-y-4">
                <div><label class="block text-sm font-medium mb-1">Kurikulum *</label><select name="kurikulum_id" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><option value="">Pilih Kurikulum</option>@foreach($kurikulumList as $kurikulum)<option value="{{ $kurikulum->id }}" @selected(old('kurikulum_id')==$kurikulum->id)>{{ $kurikulum->nama }} — {{ $kurikulum->prodi->nama ?? '-' }}</option>@endforeach</select></div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium mb-1">Kode Mata Kuliah *</label><input name="kode" value="{{ old('kode') }}" maxlength="50" required placeholder="Contoh: TI301" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                    <div><label class="block text-sm font-medium mb-1">Nama Mata Kuliah *</label><input name="nama" value="{{ old('nama') }}" maxlength="255" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                    <div><label class="block text-sm font-medium mb-1">SKS *</label><input type="number" name="sks" value="{{ old('sks') }}" min="1" max="10" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                    <div><label class="block text-sm font-medium mb-1">Semester Ke- *</label><input type="number" name="semester_ke" value="{{ old('semester_ke') }}" min="1" max="8" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                </div>
                <div><label class="block text-sm font-medium mb-1">Jenis Mata Kuliah *</label><select name="jenis" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><option value="">Pilih Jenis</option><option value="praktek" @selected(old('jenis')==='praktek')>Praktek</option><option value="teori" @selected(old('jenis')==='teori')>Teori</option></select></div>
                <div><label class="block text-sm font-medium mb-1">Semester Akademik *</label><select name="semester_id" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><option value="">Pilih Semester Akademik</option>@foreach($semesterList as $semester)<option value="{{ $semester->id }}" @selected(old('semester_id')==$semester->id)>{{ ucfirst($semester->jenis) }} — {{ $semester->tahunAkademik->label ?? '-' }}</option>@endforeach</select></div>
                <div><label class="block text-sm font-medium mb-1">Nama Kelas *</label><input name="nama_kelas" value="{{ old('nama_kelas') }}" required maxlength="255" placeholder="Contoh: Kelas A" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
            </div>
            <div class="ui-modal__footer"><button type="button" class="px-4 py-2 rounded-xl border" onclick="this.closest('dialog').close()">Batal</button><button type="submit" class="px-4 py-2 rounded-xl text-white" style="background:var(--ui-primary)">Simpan Mata Kuliah</button></div>
        </form>
    </dialog>
    <script>const mkDosenDialog=document.getElementById('modalTambahMataKuliahDosen');mkDosenDialog?.addEventListener('click',e=>{if(e.target===mkDosenDialog)mkDosenDialog.close()});@if($errors->any() && old('form_modal')==='mata-kuliah-dosen-create')document.addEventListener('DOMContentLoaded',()=>mkDosenDialog?.showModal());@endif</script>

    {{-- Pesan sukses --}}
    @if (session('status'))
        <div
            class="rounded-xl border p-4 text-sm"
            style="
                background: rgba(34, 197, 94, 0.08);
                border-color: rgba(34, 197, 94, 0.25);
                color: #16a34a;
            "
        >
            {{ session('status') }}
        </div>
    @endif

    {{-- Pesan error --}}
    @if (session('error'))
        <div
            class="rounded-xl border p-4 text-sm"
            style="
                background: rgba(239, 68, 68, 0.08);
                border-color: rgba(239, 68, 68, 0.25);
                color: #dc2626;
            "
        >
            {{ session('error') }}
        </div>
    @endif

    {{-- Tabel Mata Kuliah --}}
    <div
        class="rounded-2xl border overflow-hidden"
        style="
            background: var(--card);
            border-color: var(--border);
        "
    >
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead style="background: var(--secondary);">
                    <tr>
                        <th class="p-3 font-medium">
                            Kode
                        </th>
                        <th class="p-3 font-medium">
                            Nama Mata Kuliah
                        </th>
                        <th class="p-3 font-medium">
                            SKS
                        </th>
                        <th class="p-3 font-medium">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($mataKuliahList as $mk)
                        <tr
                            class="border-t"
                            style="border-color: var(--border);"
                        >
                            <td class="p-3">
                                {{ $mk->kode }}
                            </td>
                            <td class="p-3 font-medium">
                                {{ $mk->nama }}
                            </td>
                            <td class="p-3">
                                {{ $mk->sks }}
                            </td>
                            <td class="p-3">
                                <a
                                    href="{{ route('mata-kuliah.show', $mk) }}"
                                    class="hover:underline"
                                    style="color: var(--primary);"
                                >
                                    Kelola CPMK &amp; RPS
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-10 text-center">
                                <div class="space-y-2">
                                    <p class="font-medium">
                                        Belum ada mata kuliah yang ditugaskan kepada Anda.
                                    </p>
                                    <p class="text-sm" style="color: var(--muted-foreground);">
                                        Hubungi administrator atau Kaprodi untuk memastikan
                                        penugasan kelas dan mata kuliah Anda sudah tersedia.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection