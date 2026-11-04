@extends('layouts.app')

@section('title', 'Kurikulum - Evaluasi PBM')
@section('page-title', 'Kurikulum')
@section('page-desc', 'Kelola kurikulum dan capaian pembelajaran lulusan (CPL).')

@section('content')
    <div class="flex justify-end mb-4">
        <button type="button" onclick="document.getElementById('modalTambahKurikulum').showModal()" class="inline-block px-4 py-2 rounded-xl text-sm font-medium text-white hover:opacity-90" style="background: var(--primary);">+ Tambah Kurikulum</button>
    </div>

    <div class="rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
        <table class="w-full text-left text-sm">
            <thead style="background: var(--secondary);">
                <tr>
                    <th class="p-3 font-medium">Nama</th>
                    <th class="p-3 font-medium">Prodi</th>
                    <th class="p-3 font-medium">Tahun Berlaku</th>
                    <th class="p-3 font-medium">Status</th>
                    <th class="p-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kurikulum as $k)
                    <tr class="border-t" style="border-color: var(--border);">
                        <td class="p-3">{{ $k->nama }}</td>
                        <td class="p-3" style="color: var(--muted-foreground);">{{ $k->prodi->nama ?? '-' }}</td>
                        <td class="p-3">{{ $k->tahun_berlaku_mulai }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium" style="background: var(--secondary);">{{ ucfirst($k->status) }}</span>
                        </td>
                        <td class="p-3">
                            <div class="flex gap-3">
                                <a href="{{ route('kurikulum.show', $k) }}" class="hover:underline" style="color: var(--primary);">Kelola CPL</a>
                                <button type="button" onclick="document.getElementById('modalEditKurikulum{{ $k->id }}').showModal()" class="hover:underline" style="color: var(--accent);">Edit</button>
                                <form method="POST" action="{{ route('kurikulum.destroy', $k) }}" onsubmit="return confirm('Yakin hapus? Semua CPL dan Mata Kuliah terkait akan ikut terhapus!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="hover:underline" style="color: #a13d3d;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center" style="color: var(--muted-foreground);">Belum ada data kurikulum.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($errors->any())
        <div class="my-4 rounded-xl border px-4 py-3 text-sm" style="background:#fef2f2;border-color:#fecaca;color:#991b1b;"><ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <dialog id="modalTambahKurikulum" class="ui-modal">
        <form method="POST" action="{{ route('kurikulum.store') }}?modal=create" class="ui-modal__panel">
            @csrf <input type="hidden" name="form_modal" value="create">
            <div class="ui-modal__header"><div><h2 class="text-lg font-semibold">Tambah Kurikulum</h2><p class="text-sm" style="color:var(--ui-muted)">Lengkapi informasi kurikulum baru.</p></div><button type="button" class="ui-modal__close" onclick="this.closest('dialog').close()">×</button></div>
            <div class="ui-modal__body space-y-4">
                <div><label class="block text-sm font-medium mb-1" for="kurikulum_prodi_baru">Program Studi</label><select id="kurikulum_prodi_baru" name="prodi_id" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><option value="">-- Pilih Prodi --</option>@foreach ($prodiList as $prodi)<option value="{{ $prodi->id }}" @selected(old('prodi_id') == $prodi->id)>{{ $prodi->nama }}</option>@endforeach</select></div>
                <div><label class="block text-sm font-medium mb-1" for="kurikulum_nama_baru">Nama Kurikulum</label><input id="kurikulum_nama_baru" name="nama" value="{{ old('nama') }}" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                <div><label class="block text-sm font-medium mb-1" for="kurikulum_tahun_baru">Tahun Berlaku Mulai</label><input id="kurikulum_tahun_baru" type="number" name="tahun_berlaku_mulai" min="1900" max="2100" value="{{ old('tahun_berlaku_mulai') }}" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                <div><label class="block text-sm font-medium mb-1" for="kurikulum_status_baru">Status</label><select id="kurikulum_status_baru" name="status" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><option value="draft" @selected(old('status','draft')==='draft')>Draft</option><option value="aktif" @selected(old('status')==='aktif')>Aktif</option><option value="nonaktif" @selected(old('status')==='nonaktif')>Nonaktif</option></select></div>
            </div>
            <div class="ui-modal__footer"><button type="button" class="px-4 py-2 rounded-xl border" onclick="this.closest('dialog').close()">Batal</button><button type="submit" class="px-4 py-2 rounded-xl text-white" style="background:var(--ui-primary)">Simpan Kurikulum</button></div>
        </form>
    </dialog>
    @foreach ($kurikulum as $k)
        <dialog id="modalEditKurikulum{{ $k->id }}" class="ui-modal">
            <form method="POST" action="{{ route('kurikulum.update', $k) }}?modal=edit-{{ $k->id }}" class="ui-modal__panel">
                @csrf @method('PUT') <input type="hidden" name="form_modal" value="edit-{{ $k->id }}">
                <div class="ui-modal__header"><div><h2 class="text-lg font-semibold">Edit Kurikulum</h2><p class="text-sm" style="color:var(--ui-muted)">{{ $k->nama }}</p></div><button type="button" class="ui-modal__close" onclick="this.closest('dialog').close()">×</button></div>
                <div class="ui-modal__body space-y-4">
                    <div><label class="block text-sm font-medium mb-1" for="kurikulum_prodi_{{ $k->id }}">Program Studi</label><select id="kurikulum_prodi_{{ $k->id }}" name="prodi_id" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><option value="">-- Pilih Prodi --</option>@foreach ($prodiList as $prodi)<option value="{{ $prodi->id }}" @selected(old('prodi_id', $k->prodi_id) == $prodi->id)>{{ $prodi->nama }}</option>@endforeach</select></div>
                    <div><label class="block text-sm font-medium mb-1" for="kurikulum_nama_{{ $k->id }}">Nama Kurikulum</label><input id="kurikulum_nama_{{ $k->id }}" name="nama" value="{{ old('nama', $k->nama) }}" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                    <div><label class="block text-sm font-medium mb-1" for="kurikulum_tahun_{{ $k->id }}">Tahun Berlaku Mulai</label><input id="kurikulum_tahun_{{ $k->id }}" type="number" name="tahun_berlaku_mulai" min="1900" max="2100" value="{{ old('tahun_berlaku_mulai', $k->tahun_berlaku_mulai) }}" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                    <div><label class="block text-sm font-medium mb-1" for="kurikulum_status_{{ $k->id }}">Status</label><select id="kurikulum_status_{{ $k->id }}" name="status" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><option value="draft" @selected(old('status', $k->status)==='draft')>Draft</option><option value="aktif" @selected(old('status', $k->status)==='aktif')>Aktif</option><option value="nonaktif" @selected(old('status', $k->status)==='nonaktif')>Nonaktif</option></select></div>
                </div>
                <div class="ui-modal__footer"><button type="button" class="px-4 py-2 rounded-xl border" onclick="this.closest('dialog').close()">Batal</button><button type="submit" class="px-4 py-2 rounded-xl text-white" style="background:var(--ui-primary)">Simpan Perubahan</button></div>
            </form>
        </dialog>
    @endforeach
    <script>
    document.querySelectorAll('dialog.ui-modal').forEach(function(dialog){dialog.addEventListener('click',function(event){if(event.target===dialog)dialog.close();});});
    @if ($errors->any())
    document.addEventListener('DOMContentLoaded',function(){const key=@json(request('modal') ?: old('form_modal'));if(key==='create')document.getElementById('modalTambahKurikulum')?.showModal();else if(key&&key.startsWith('edit-'))document.getElementById('modalEditKurikulum'+key.slice(5))?.showModal();});
    @endif
    </script>

@endsection