@extends('layouts.app')

@section('title', 'Detail Kurikulum - Evaluasi PBM')
@section('page-title', $kurikulum->nama)
@section('page-desc', ($kurikulum->prodi->nama ?? '-') . ' — Berlaku sejak ' . $kurikulum->tahun_berlaku_mulai)

@section('content')
    {{-- Kembali --}}
    <div class="mb-5">
        <a href="{{ route('kurikulum.index') }}"class="text-sm hover:underline"style="color: var(--muted-foreground);">
            ← Kembali ke Daftar Kurikulum
        </a>
    </div>
    {{-- Informasi Kurikulum --}}
    <div class="w-full rounded-2xl border p-6 mb-6"style="background: var(--card); border-color: var(--border);">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-xs uppercase tracking-wide"style="color: var(--muted-foreground);">
                    Nama Kurikulum
                </p>
                <p class="font-medium mt-1">
                    {{ $kurikulum->nama }}
                </p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide"style="color: var(--muted-foreground);">
                    Program Studi
                </p>
                <p class="font-medium mt-1">
                    {{ $kurikulum->prodi->nama ?? '-' }}
                </p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide"style="color: var(--muted-foreground);">
                    Berlaku Sejak
                </p>
                <p class="font-medium mt-1">
                    {{ $kurikulum->tahun_berlaku_mulai }}
                </p>
            </div>
        </div>
    </div>
    {{-- Laporan CPL --}}
    <div class="mb-6">
        <a href="{{ route('kurikulum.laporan-cpl', $kurikulum) }}"class="inline-flex items-center text-sm font-medium hover:underline"style="color: var(--primary);">
            Lihat Laporan Capaian CPL Semua Mahasiswa →
        </a>
    </div>
    {{-- Daftar CPL --}}
    <div class="w-full rounded-2xl border overflow-hidden mb-6"style="background: var(--card); border-color: var(--border);">
        <div class="px-6 py-5 border-b flex items-center justify-between gap-4"style="border-color: var(--border);">
            <div>
                <h2 class="font-serif-display text-xl">
                    Daftar CPL
                </h2>
                <p class="text-sm mt-1"style="color: var(--muted-foreground);">
                    Capaian pembelajaran lulusan pada kurikulum ini.
                </p>
            </div>
            <button type="button" onclick="document.getElementById('modalTambahCpl').showModal()" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium text-white hover:opacity-90 shrink-0" style="background:var(--primary)">+ Tambah CPL</button>
        </div>
        <div class="p-2">
            @forelse ($kurikulum->cpl as $cpl)
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 p-4 border-b last:border-b-0"style="border-color: var(--border);">
                    <div class="min-w-0">
                        <p class="font-medium text-sm">
                            {{ $cpl->kode }}
                            @if($cpl->domain)
                                <span class="text-xs"style="color: var(--muted-foreground);">
                                    ({{ $cpl->domain }})
                                </span>
                            @endif
                        </p>
                        <p class="text-sm mt-1"style="color: var(--muted-foreground);">
                            {{ $cpl->deskripsi }}
                        </p>
                    </div>
                    <div class="flex gap-4 shrink-0">
                        <button type="button" onclick="document.getElementById('modalEditCpl{{ $cpl->id }}').showModal()" class="text-sm hover:underline" style="color:var(--accent)">Edit</button>
                        <form method="POST"action="{{ route('cpl.destroy', $cpl) }}"onsubmit="return confirm('Yakin hapus CPL ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm hover:underline"style="color: #a13d3d;">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="p-6 text-center text-sm"style="color: var(--muted-foreground);">
                    Belum ada CPL.
                </p>
            @endforelse
        </div>
    </div>
    {{-- Daftar Mata Kuliah --}}
    <div class="w-full rounded-2xl border overflow-hidden mb-6"
        style="background: var(--card); border-color: var(--border);">

        <div class="px-6 py-5 border-b flex flex-col md:flex-row md:items-center md:justify-between gap-4"
            style="border-color: var(--border);">

            <div>
                <h2 class="font-serif-display text-xl">
                    Daftar Mata Kuliah
                </h2>

                <p class="text-sm mt-1"
                style="color: var(--muted-foreground);">
                    Kelola mata kuliah yang termasuk dalam kurikulum ini.
                </p>
            </div>

            <a href="{{ route('kurikulum.mata-kuliah.create', $kurikulum) }}"
            class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-sm font-medium text-white hover:opacity-90 shrink-0"
            style="background: var(--primary);">
                + Tambah Mata Kuliah
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead style="background: var(--secondary);">
                    <tr>
                        <th class="px-6 py-3 font-medium">Kode</th>
                        <th class="px-6 py-3 font-medium">Nama Mata Kuliah</th>
                        <th class="px-6 py-3 font-medium">SKS</th>
                        <th class="px-6 py-3 font-medium">Semester</th>
                        <th class="px-6 py-3 font-medium">Jenis</th>
                        <th class="px-6 py-3 font-medium">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($kurikulum->mataKuliah as $mk)
                        <tr class="border-t"
                            style="border-color: var(--border);">

                            <td class="px-6 py-4 font-medium">
                                {{ $mk->kode }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $mk->nama }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $mk->sks }}
                            </td>

                            <td class="px-6 py-4">
                                Semester {{ $mk->semester_ke }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $mk->jenis ?: '-' }}
                            </td>

                            <td class="px-6 py-4">
                                <a href="{{ route('kurikulum.mata-kuliah.show', [$kurikulum, $mk]) }}"
                                class="hover:underline"
                                style="color: var(--accent);">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"
                                class="px-6 py-8 text-center"
                                style="color: var(--muted-foreground);">
                                Belum ada mata kuliah dalam kurikulum ini.
                                Silakan tambahkan mata kuliah terlebih dahulu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{-- Pemetaan CPL - CPMK --}}
    <div class="w-full rounded-2xl border overflow-hidden"style="background: var(--card); border-color: var(--border);">
        <div class="px-6 py-5 border-b"style="border-color: var(--border);">
            <h2 class="font-serif-display text-xl">
                Pemetaan CPL–CPMK
            </h2>
            <p class="text-sm mt-1"style="color: var(--muted-foreground);">
                Lihat hubungan CPL kurikulum dengan CPMK
                yang dibuat untuk setiap mata kuliah.
            </p>
        </div>
        <div class="p-4 space-y-5">
            @forelse ($kurikulum->mataKuliah as $mk)
                <div class="rounded-xl border overflow-hidden" style="border-color: var(--border);">
                    {{-- Informasi Mata Kuliah --}}
                    <div class="px-4 py-3"style="background: var(--secondary);">
                        <p class="font-semibold text-sm">
                            {{ $mk->kode }} — {{ $mk->nama }}
                        </p>
                        <p class="text-xs mt-1"style="color: var(--muted-foreground);">
                            {{ $mk->sks }} SKS
                        </p>
                    </div>
                    {{-- Daftar CPMK --}}
                    <div class="divide-y"style="border-color: var(--border);">
                        @forelse ($mk->cpmk as $cpmk)
                            <div class="p-4">
                                <div class="flex flex-col gap-2">
                                    <div>
                                        <p class="font-medium text-sm">
                                            {{ $cpmk->kode }}
                                        </p>
                                        <p class="text-sm mt-1"style="color: var(--muted-foreground);">
                                            {{ $cpmk->deskripsi }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide mb-2"style="color: var(--muted-foreground);">
                                            CPL yang Dipetakan
                                        </p>
                                        @forelse ($cpmk->cpl as $cpl)
                                            <div class="inline-flex flex-col mr-2 mb-2 px-3 py-2 rounded-lg border"style="border-color: var(--border);">
                                                <span class="text-sm font-medium">
                                                    {{ $cpl->kode }}
                                                    @if ($cpl->domain)
                                                        ({{ $cpl->domain }})
                                                    @endif
                                                </span>
                                                <span class="text-xs mt-1"style="color: var(--muted-foreground);">
                                                    {{ $cpl->deskripsi }}
                                                </span>
                                                <span class="text-xs mt-1"style="color: var(--primary);">
                                                    Bobot:
                                                    {{ $cpl->pivot->bobot }}%
                                                </span>
                                            </div>
                                        @empty
                                            <p class="text-sm"style="color: var(--muted-foreground);">
                                                CPMK ini belum dipetakan ke CPL.
                                            </p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="p-4 text-sm"style="color: var(--muted-foreground);">
                                Belum ada CPMK untuk mata kuliah ini.
                            </p>
                        @endforelse
                    </div>
                </div>
            @empty
                <p class="p-6 text-center text-sm"style="color: var(--muted-foreground);">
                    Belum ada mata kuliah pada kurikulum ini.
                </p>
            @endforelse
        </div>
    </div>


    @if (session('status'))
        <div class="mb-4 rounded-xl border px-4 py-3 text-sm" style="background:#f0fdf4;border-color:#bbf7d0;color:#166534">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 rounded-xl border px-4 py-3 text-sm" style="background:#fef2f2;border-color:#fecaca;color:#991b1b"><ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <dialog id="modalTambahCpl" class="ui-modal">
        <form method="POST" action="{{ route('cpl.store') }}" class="ui-modal__panel">
            @csrf <input type="hidden" name="kurikulum_id" value="{{ $kurikulum->id }}"><input type="hidden" name="form_modal" value="create">
            <div class="ui-modal__header"><div><h2 class="text-lg font-semibold">Tambah CPL</h2><p class="text-sm" style="color:var(--ui-muted)">Kurikulum: {{ $kurikulum->nama }}</p></div><button type="button" class="ui-modal__close" onclick="this.closest('dialog').close()">×</button></div>
            <div class="ui-modal__body space-y-4">
                <div><label class="block text-sm font-medium mb-1" for="cpl_kode_baru">Kode CPL</label><input id="cpl_kode_baru" name="kode" value="{{ old('kode') }}" placeholder="Contoh: CPL-01" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                <div><label class="block text-sm font-medium mb-1" for="cpl_domain_baru">Domain (opsional)</label><input id="cpl_domain_baru" name="domain" value="{{ old('domain') }}" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                <div><label class="block text-sm font-medium mb-1" for="cpl_deskripsi_baru">Deskripsi</label><textarea id="cpl_deskripsi_baru" name="deskripsi" rows="5" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)">{{ old('deskripsi') }}</textarea></div>
            </div>
            <div class="ui-modal__footer"><button type="button" class="px-4 py-2 rounded-xl border" onclick="this.closest('dialog').close()">Batal</button><button type="submit" class="px-4 py-2 rounded-xl text-white" style="background:var(--ui-primary)">Simpan CPL</button></div>
        </form>
    </dialog>
    @foreach ($kurikulum->cpl as $cpl)
        <dialog id="modalEditCpl{{ $cpl->id }}" class="ui-modal">
            <form method="POST" action="{{ route('cpl.update', $cpl) }}" class="ui-modal__panel">
                @csrf @method('PUT') <input type="hidden" name="kurikulum_id" value="{{ $kurikulum->id }}"><input type="hidden" name="form_modal" value="edit-{{ $cpl->id }}">
                <div class="ui-modal__header"><div><h2 class="text-lg font-semibold">Edit CPL</h2><p class="text-sm" style="color:var(--ui-muted)">{{ $cpl->kode }}</p></div><button type="button" class="ui-modal__close" onclick="this.closest('dialog').close()">×</button></div>
                <div class="ui-modal__body space-y-4">
                    <div><label class="block text-sm font-medium mb-1" for="cpl_kode_{{ $cpl->id }}">Kode CPL</label><input id="cpl_kode_{{ $cpl->id }}" name="kode" value="{{ old('kode', $cpl->kode) }}" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                    <div><label class="block text-sm font-medium mb-1" for="cpl_domain_{{ $cpl->id }}">Domain (opsional)</label><input id="cpl_domain_{{ $cpl->id }}" name="domain" value="{{ old('domain', $cpl->domain) }}" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                    <div><label class="block text-sm font-medium mb-1" for="cpl_deskripsi_{{ $cpl->id }}">Deskripsi</label><textarea id="cpl_deskripsi_{{ $cpl->id }}" name="deskripsi" rows="5" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)">{{ old('deskripsi', $cpl->deskripsi) }}</textarea></div>
                </div>
                <div class="ui-modal__footer"><button type="button" class="px-4 py-2 rounded-xl border" onclick="this.closest('dialog').close()">Batal</button><button type="submit" class="px-4 py-2 rounded-xl text-white" style="background:var(--ui-primary)">Simpan Perubahan</button></div>
            </form>
        </dialog>
    @endforeach
    <script>
    document.querySelectorAll('dialog.ui-modal').forEach(function(dialog){dialog.addEventListener('click',function(event){if(event.target===dialog)dialog.close();});});
    @if ($errors->any())
    document.addEventListener('DOMContentLoaded',function(){const key=@json(old('form_modal'));if(key==='create')document.getElementById('modalTambahCpl')?.showModal();else if(key&&key.startsWith('edit-'))document.getElementById('modalEditCpl'+key.slice(5))?.showModal();});
    @endif
    </script>

@endsection