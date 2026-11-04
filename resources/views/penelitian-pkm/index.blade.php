@extends('layouts.app')

@section('title', 'Penelitian & PKM Saya - Evaluasi PBM')
@section('page-title', 'Penelitian & PKM Saya')
@section('page-desc', '')

@section('content')
    <div class="flex justify-end mb-4">
        <button type="button" onclick="document.getElementById('modalAjukanPkm').showModal()" class="inline-block px-4 py-2 rounded-xl text-sm font-medium text-white" style="background: var(--primary);">+ Ajukan Baru</button>
    </div>

    @if (session('status'))<div class="mb-4 rounded-xl border px-4 py-3 text-sm" style="background:#f0fdf4;border-color:#bbf7d0;color:#166534">{{ session('status') }}</div>@endif
    @if ($errors->any())<div class="mb-4 rounded-xl border px-4 py-3 text-sm" style="background:#fef2f2;border-color:#fecaca;color:#991b1b"><ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <dialog id="modalAjukanPkm" class="ui-modal">
        <form method="POST" action="{{ route('penelitian-pkm.store') }}" class="ui-modal__panel">
            @csrf <input type="hidden" name="form_modal" value="pkm-create">
            <div class="ui-modal__header"><div><h2 class="text-lg font-semibold">Ajukan Penelitian / PKM</h2><p class="text-sm" style="color:var(--ui-muted)">Isi informasi pengajuan dan anggota tim.</p></div><button type="button" class="ui-modal__close" onclick="this.closest('dialog').close()">×</button></div>
            <div class="ui-modal__body space-y-4">
                <div><label class="block text-sm font-medium mb-1">Judul *</label><input name="judul" value="{{ old('judul') }}" required maxlength="255" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                <div><label class="block text-sm font-medium mb-1">Deskripsi Kegiatan *</label><textarea name="deskripsi_kegiatan" rows="5" minlength="20" maxlength="5000" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)">{{ old('deskripsi_kegiatan') }}</textarea><p class="text-xs mt-1" style="color:var(--ui-muted)">Minimal 20 dan maksimal 5.000 karakter.</p></div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium mb-1">Jenis *</label><select name="jenis" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><option value="Penelitian" @selected(old('jenis')==='Penelitian')>Penelitian</option><option value="PKM" @selected(old('jenis')==='PKM')>PKM</option></select></div>
                    <div><label class="block text-sm font-medium mb-1">Kategori Pendanaan</label><input name="kategori_pendanaan" value="{{ old('kategori_pendanaan') }}" placeholder="Internal, DIKTI, dsb." class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                </div>
                <div><label class="block text-sm font-medium mb-1">Tahun Akademik *</label><select name="tahun_akademik_id" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><option value="">Pilih Tahun Akademik</option>@foreach(\App\Models\TahunAkademik::orderByDesc('id')->get() as $ta)<option value="{{ $ta->id }}" @selected(old('tahun_akademik_id')==$ta->id)>{{ $ta->label }}</option>@endforeach</select></div>
                <div><label class="block text-sm font-medium mb-1">Dosen Anggota (opsional)</label><select name="anggota[]" multiple size="5" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)">@foreach(\App\Models\Dosen::where('id','!=',auth()->user()->dosen?->id)->orderBy('nama')->get() as $dosen)<option value="{{ $dosen->id }}" @selected(in_array($dosen->id, old('anggota',[])))>{{ $dosen->nama }}</option>@endforeach</select><p class="text-xs mt-1" style="color:var(--ui-muted)">Gunakan Ctrl (Windows) atau Command (Mac) untuk memilih lebih dari satu dosen.</p></div>
            </div>
            <div class="ui-modal__footer"><button type="button" class="px-4 py-2 rounded-xl border" onclick="this.closest('dialog').close()">Batal</button><button type="submit" class="px-4 py-2 rounded-xl text-white" style="background:var(--ui-primary)">Ajukan</button></div>
        </form>
    </dialog>
    <script>
    const pkmDialog=document.getElementById('modalAjukanPkm');pkmDialog?.addEventListener('click',function(e){if(e.target===pkmDialog)pkmDialog.close();});
    @if($errors->any() && old('form_modal')==='pkm-create')document.addEventListener('DOMContentLoaded',()=>pkmDialog?.showModal());@endif
    </script>


    @foreach($penelitianList as $p)
    @if(($p->pivot->peran ?? null)==='Ketua')
    <dialog id="modalEditPkm{{ $p->id }}" class="ui-modal">
        <form method="POST" action="{{ route('penelitian-pkm.update',$p) }}" class="ui-modal__panel">
            @csrf @method('PUT') <input type="hidden" name="form_modal" value="pkm-edit-{{ $p->id }}">
            <div class="ui-modal__header"><div><h2 class="text-lg font-semibold">{{ $p->status==='ditolak'?'Perbaiki Pengajuan':'Edit Penelitian / PKM' }}</h2><p class="text-sm" style="color:var(--ui-muted)">{{ $p->judul }}</p></div><button type="button" class="ui-modal__close" onclick="this.closest('dialog').close()">×</button></div>
            <div class="ui-modal__body space-y-4">
                @if($p->status==='ditolak' && $p->alasan_penolakan)<div class="rounded-xl border p-3 text-sm" style="background:#fff7ed;border-color:#fed7aa;color:#9a3412"><strong>Catatan Kaprodi:</strong><p class="mt-1 whitespace-pre-line">{{ $p->alasan_penolakan }}</p></div>@endif
                <div><label class="block text-sm font-medium mb-1">Judul *</label><input name="judul" value="{{ old('judul',$p->judul) }}" required maxlength="255" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                <div><label class="block text-sm font-medium mb-1">Deskripsi Kegiatan *</label><textarea name="deskripsi_kegiatan" rows="5" minlength="20" maxlength="5000" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)">{{ old('deskripsi_kegiatan',$p->deskripsi_kegiatan) }}</textarea></div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium mb-1">Jenis *</label><select name="jenis" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><option value="Penelitian" @selected(old('jenis',$p->jenis)==='Penelitian')>Penelitian</option><option value="PKM" @selected(old('jenis',$p->jenis)==='PKM')>PKM</option></select></div>
                    <div><label class="block text-sm font-medium mb-1">Kategori Pendanaan</label><input name="kategori_pendanaan" value="{{ old('kategori_pendanaan',$p->kategori_pendanaan) }}" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                </div>
                <div><label class="block text-sm font-medium mb-1">Tahun Akademik *</label><select name="tahun_akademik_id" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)">@foreach(\App\Models\TahunAkademik::orderByDesc('id')->get() as $ta)<option value="{{ $ta->id }}" @selected(old('tahun_akademik_id',$p->tahun_akademik_id)==$ta->id)>{{ $ta->label }}</option>@endforeach</select></div>
                <div><label class="block text-sm font-medium mb-1">Dosen Anggota</label><select name="anggota[]" multiple size="5" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)">@foreach(\App\Models\Dosen::where('id','!=',auth()->user()->dosen?->id)->orderBy('nama')->get() as $dosen)<option value="{{ $dosen->id }}" @selected(in_array($dosen->id, old('anggota',$p->dosen->where('pivot.peran','Anggota')->pluck('id')->all())))>{{ $dosen->nama }}</option>@endforeach</select><p class="text-xs mt-1" style="color:var(--ui-muted)">Gunakan Ctrl/Command untuk memilih beberapa dosen.</p></div>
            </div>
            <div class="ui-modal__footer"><button type="button" class="px-4 py-2 rounded-xl border" onclick="this.closest('dialog').close()">Batal</button><button type="submit" class="px-4 py-2 rounded-xl text-white" style="background:var(--ui-primary)">{{ $p->status==='ditolak'?'Simpan & Ajukan Ulang':'Simpan Perubahan' }}</button></div>
        </form>
    </dialog>
    @endif
    @endforeach
    <script>
    document.querySelectorAll('dialog.ui-modal').forEach(d=>d.addEventListener('click',e=>{if(e.target===d)d.close()}));
    @if($errors->any() && old('form_modal') && str_starts_with(old('form_modal'),'pkm-edit-'))
    document.addEventListener('DOMContentLoaded',()=>document.getElementById('modalEditPkm'+@json(substr(old('form_modal'),9)))?.showModal());
    @endif
    </script>

    <div class="rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
        <table class="w-full text-left text-sm">
            <thead style="background: var(--secondary);">
                <tr>
                    <th class="p-3 font-medium">Judul</th>
                    <th class="p-3 font-medium">Jenis</th>
                    <th class="p-3 font-medium">Peran</th>
                    <th class="p-3 font-medium">Status</th>
                    <th class="p-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>

                @forelse ($penelitianList as $p)

                    <tr
                        class="border-t"
                        style="border-color: var(--border);"
                    >

                        <td class="p-3">
                            {{ $p->judul }}
                        </td>

                        <td class="p-3">
                            {{ $p->jenis }}
                        </td>

                        <td class="p-3">
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium"
                                style="background: var(--secondary);"
                            >
                                {{ $p->pivot->peran ?? '-' }}
                            </span>
                        </td>

                        <td class="p-3">
                            <span
                                class="px-2 py-1 rounded-full text-xs font-medium"
                                style="background: var(--secondary);"
                            >
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>

                        <td class="p-3">

                            <div class="flex gap-3 items-center">

                                {{-- Semua anggota boleh melihat detail --}}
                                <a
                                    href="{{ route('penelitian-pkm.show', $p) }}"
                                    class="hover:underline"
                                    style="color: var(--primary);"
                                >
                                    Detail
                                </a>

                                {{-- Hanya Ketua yang boleh Edit dan Hapus --}}
                                @if (($p->pivot->peran ?? null) === 'Ketua')

                                    <button type="button" onclick="document.getElementById('modalEditPkm{{ $p->id }}').showModal()" class="hover:underline text-left" style="color: var(--accent);">{{ $p->status === 'ditolak' ? 'Perbaiki & Ajukan Ulang' : 'Edit' }}</button>

                                    <form
                                        method="POST"
                                        action="{{ route('penelitian-pkm.destroy', $p) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus penelitian/PKM ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="hover:underline"
                                            style="color: #a13d3d;"
                                        >
                                            Hapus
                                        </button>
                                    </form>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="5"
                            class="p-6 text-center"
                            style="color: var(--muted-foreground);"
                        >
                            Belum ada penelitian/PKM.
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>
    </div>
@endsection