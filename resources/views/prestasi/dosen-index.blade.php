@extends('layouts.app')

@section('title', 'Prestasi Saya - Evaluasi PBM')
@section('page-title', 'Prestasi Saya')
@section('page-desc', 'Catat dan lihat prestasi yang Anda raih.')

@section('content')

    <div class="flex justify-end mb-4">
        <button type="button" onclick="document.getElementById('modalTambahPrestasi').showModal()" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-medium text-white hover:opacity-95 transition" style="background: var(--primary);">+ Tambah Prestasi</button>
    </div>


    @if(session('status'))<div class="mb-4 rounded-xl border px-4 py-3 text-sm" style="background:#f0fdf4;border-color:#bbf7d0;color:#166534">{{ session('status') }}</div>@endif
    @if($errors->any())<div class="mb-4 rounded-xl border px-4 py-3 text-sm" style="background:#fef2f2;border-color:#fecaca;color:#991b1b"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <dialog id="modalTambahPrestasi" class="ui-modal">
        <form method="POST" action="{{ route('prestasi-dosen.store') }}" class="ui-modal__panel">
            @csrf <input type="hidden" name="form_modal" value="prestasi-create">
            <div class="ui-modal__header"><div><h2 class="text-lg font-semibold">Tambah Prestasi</h2><p class="text-sm" style="color:var(--ui-muted)">Catat pencapaian yang Anda raih.</p></div><button type="button" class="ui-modal__close" onclick="this.closest('dialog').close()">×</button></div>
            <div class="ui-modal__body space-y-4">
                <div><label class="block text-sm font-medium mb-1">Nama Kegiatan *</label><input name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" required maxlength="255" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium mb-1">Tingkat</label><input name="tingkat" value="{{ old('tingkat') }}" placeholder="Lokal/Nasional/Internasional" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                    <div><label class="block text-sm font-medium mb-1">Jenis</label><input name="jenis" value="{{ old('jenis') }}" placeholder="Akademik/Non-Akademik" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                    <div><label class="block text-sm font-medium mb-1">Peringkat/Capaian</label><input name="peringkat" value="{{ old('peringkat') }}" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                    <div><label class="block text-sm font-medium mb-1">Tempat Pelaksanaan</label><input name="tempat_pelaksanaan" value="{{ old('tempat_pelaksanaan') }}" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                </div>
                <div><label class="block text-sm font-medium mb-1">Tanggal Penerimaan</label><input type="date" name="tanggal_penerimaan" value="{{ old('tanggal_penerimaan') }}" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                <div><label class="block text-sm font-medium mb-1">Tahun Akademik *</label><select name="tahun_akademik_id" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><option value="">Pilih Tahun Akademik</option>@foreach(\App\Models\TahunAkademik::orderByDesc('id')->get() as $ta)<option value="{{ $ta->id }}" @selected(old('tahun_akademik_id')==$ta->id)>{{ $ta->label }}</option>@endforeach</select></div>
            </div>
            <div class="ui-modal__footer"><button type="button" class="px-4 py-2 rounded-xl border" onclick="this.closest('dialog').close()">Batal</button><button type="submit" class="px-4 py-2 rounded-xl text-white" style="background:var(--ui-primary)">Simpan Prestasi</button></div>
        </form>
    </dialog>
    <script>const prestasiDialog=document.getElementById('modalTambahPrestasi');prestasiDialog?.addEventListener('click',e=>{if(e.target===prestasiDialog)prestasiDialog.close()});@if($errors->any() && old('form_modal')==='prestasi-create')document.addEventListener('DOMContentLoaded',()=>prestasiDialog?.showModal());@endif</script>

    <div
        class="rounded-2xl border overflow-hidden"
        style="background: var(--card); border-color: var(--border);"
    >
        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm">

                <thead style="background: var(--secondary);">
                    <tr>
                        <th class="p-3 font-medium">
                            Nama Kegiatan
                        </th>

                        <th class="p-3 font-medium">
                            Tingkat
                        </th>

                        <th class="p-3 font-medium">
                            Peringkat / Capaian
                        </th>

                        <th class="p-3 font-medium">
                            Tahun
                        </th>

                        <th class="p-3 font-medium text-center">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($prestasiList as $p)

                        <tr
                            class="border-t"
                            style="border-color: var(--border);"
                        >

                            <td class="p-3 font-medium">
                                {{ $p->nama_kegiatan }}
                            </td>

                            <td class="p-3">
                                {{ $p->tingkat ?? '-' }}
                            </td>

                            <td class="p-3">
                                {{ $p->peringkat ?? '-' }}
                            </td>

                            <td class="p-3">
                                {{ $p->tahunAkademik->label ?? '-' }}
                            </td>

                            <td class="p-3 text-center">

                                <a
                                    href="{{ route('prestasi-dosen.show', $p->id) }}"
                                    class="inline-flex items-center justify-center px-3 py-2 rounded-lg text-xs font-semibold"
                                    style="background: var(--secondary); color: var(--foreground);"
                                >
                                    Lihat Detail
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="5"
                                class="p-8 text-center"
                                style="color: var(--muted-foreground);"
                            >
                                Belum ada prestasi.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

@endsection