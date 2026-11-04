@extends('layouts.app')

@section('title', 'Detail Mata Kuliah - Evaluasi PBM')
@section('page-title', $mataKuliah->kode . ' — ' . $mataKuliah->nama)
@section('page-desc', $mataKuliah->sks . ' SKS, Semester ke-' . $mataKuliah->semester_ke)

@section('content')
     {{-- Kembali --}}
    <div class="mb-4 text-left">
        <a
            href="{{ route('mata-kuliah.index') }}"
            class="text-sm underline hover:opacity-70"
            style="color: var(--muted-foreground);"
        >
            ← Kembali ke Mata Kuliah
        </a>
    </div>

    <dialog id="modalTambahCpmk" class="ui-modal">
        <form method="POST" action="{{ route('cpmk.store') }}" class="ui-modal__panel">
            @csrf
            <input type="hidden" name="mata_kuliah_id" value="{{ $mataKuliah->id }}">
            <input type="hidden" name="form_modal" value="cpmk-create">
            <div class="ui-modal__header"><div><h2 class="text-lg font-semibold">Tambah CPMK</h2><p class="text-sm" style="color:var(--ui-muted)">{{ $mataKuliah->kode }} — {{ $mataKuliah->nama }}</p></div><button type="button" class="ui-modal__close" onclick="this.closest('dialog').close()">×</button></div>
            <div class="ui-modal__body space-y-4">
                <div><label for="cpmk_kode_baru" class="block text-sm font-medium mb-1">Kode CPMK *</label><input id="cpmk_kode_baru" name="kode" value="{{ old('kode') }}" required placeholder="Contoh: CPMK-01" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                <div><label for="cpmk_deskripsi_baru" class="block text-sm font-medium mb-1">Deskripsi CPMK *</label><textarea id="cpmk_deskripsi_baru" name="deskripsi" rows="4" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)">{{ old('deskripsi') }}</textarea></div>
                <div><p class="text-sm font-medium mb-1">Pemetaan CPL *</p><p class="text-xs mb-3" style="color:var(--ui-muted)">Pilih CPL dan isi bobot pemetaannya.</p><div id="modal-cpl-container" class="space-y-3"></div></div>
            </div>
            <div class="ui-modal__footer"><button type="button" class="px-4 py-2 rounded-xl border" onclick="this.closest('dialog').close()">Batal</button><button type="submit" class="px-4 py-2 rounded-xl text-white" style="background:var(--ui-primary)">Simpan CPMK</button></div>
        </form>
    </dialog>
    <script>
    document.addEventListener('DOMContentLoaded',function(){
        const container=document.getElementById('modal-cpl-container');
        const cpls=@json($mataKuliah->kurikulum?->cpl ?? []);
        const oldIds=@json(array_map('strval',old('cpl_ids',[])));
        const oldWeights=@json(old('bobot',[]));
        cpls.forEach(function(cpl){
            const card=document.createElement('div');card.className='rounded-xl border p-3';card.style.borderColor='var(--ui-border)';
            const label=document.createElement('label');label.className='flex items-start gap-2';
            const check=document.createElement('input');check.type='checkbox';check.name='cpl_ids[]';check.value=cpl.id;check.checked=oldIds.includes(String(cpl.id));
            const title=document.createElement('span');title.className='text-sm font-medium';title.textContent=cpl.kode+(cpl.domain?' ('+cpl.domain+')':'')+' — '+(cpl.deskripsi||'');
            label.append(check,title);const weight=document.createElement('input');weight.type='number';weight.name='bobot['+cpl.id+']';weight.min='0';weight.max='100';weight.step='0.01';weight.value=oldWeights[cpl.id]??'100';weight.required=check.checked;weight.disabled=!check.checked;weight.placeholder='Bobot (%)';weight.className='mt-3 w-full rounded-xl border px-3 py-2 text-sm';weight.style.borderColor='var(--ui-border)';
            check.addEventListener('change',function(){weight.disabled=!check.checked;weight.required=check.checked;});card.append(label,weight);container.append(card);
        });
        if(!cpls.length){container.textContent='Belum ada CPL pada kurikulum mata kuliah ini.';}
        document.querySelectorAll('dialog.ui-modal').forEach(function(d){d.addEventListener('click',function(e){if(e.target===d)d.close();});});
        @if ($errors->any() && old('form_modal') === 'cpmk-create')
        document.getElementById('modalTambahCpmk')?.showModal();
        @endif
    });
    </script>

    <div class="rounded-2xl border overflow-hidden mb-6" style="background: var(--card); border-color: var(--border);">
        <div class="px-6 py-4 border-b flex items-center justify-between" style="border-color: var(--border);">
            <h2 class="font-serif-display text-lg">CPMK</h2>
            <button type="button" onclick="document.getElementById('modalTambahCpmk').showModal()" class="px-3 py-1.5 rounded-lg text-xs font-medium text-white" style="background: var(--primary);">+ Tambah CPMK</button>
        </div>
        <div class="p-2">
            @forelse ($mataKuliah->cpmk as $cpmk)
                <div class="p-4 border-b last:border-b-0" style="border-color: var(--border);">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium text-sm">{{ $cpmk->kode }}</p>
                            <p class="text-sm mt-1" style="color: var(--muted-foreground);">{{ $cpmk->deskripsi }}</p>
                        </div>
                        <div class="flex gap-3 shrink-0 ml-4 text-sm">
                            <button type="button" onclick="document.getElementById('modalEditCpmk{{ $cpmk->id }}').showModal()" class="hover:underline" style="color: var(--accent);">Edit</button>
                            <form method="POST" action="{{ route('cpmk.destroy', $cpmk) }}" onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="hover:underline" style="color: #a13d3d;">Hapus</button>
                            </form>
                        </div>
                    </div>
                    @if ($cpmk->cpl->isNotEmpty())
                        <p class="text-xs mt-1" style="color: var(--muted-foreground);">
                            Dipetakan ke: @foreach ($cpmk->cpl as $cpl){{ $cpl->kode }} ({{ $cpl->pivot->bobot }}%){{ !$loop->last ? ', ' : '' }}@endforeach
                        </p>
                    @endif
                </div>
            @empty
                <p class="p-6 text-center text-sm" style="color: var(--muted-foreground);">Belum ada CPMK.</p>
            @endforelse
        </div>
    </div>

    <div class="rounded-2xl border overflow-hidden mb-6" style="background: var(--card); border-color: var(--border);">
        <div class="px-6 py-4 border-b flex items-center justify-between" style="border-color: var(--border);">
            <h2 class="font-serif-display text-lg">RPS</h2>
            <button type="button" onclick="document.getElementById('modalTambahRps').showModal()" class="px-3 py-1.5 rounded-lg text-xs font-medium text-white" style="background: var(--primary);">+ Tambah RPS</button>
        </div>
        <div class="p-2">
            @forelse ($mataKuliah->rps as $rps)
                <div class="p-4 border-b last:border-b-0 flex justify-between items-start" style="border-color: var(--border);">
                    <div>
                        <p class="font-medium text-sm">Versi {{ $rps->versi }} — {{ $rps->tanggal_disusun }}</p>
                        <p class="text-sm mt-1" style="color: var(--muted-foreground);">{{ $rps->deskripsi_singkat ?? '-' }}</p>
                    </div>
                    <div class="flex gap-3 shrink-0 ml-4 text-sm">
                        <button type="button" onclick="document.getElementById('modalEditRps{{ $rps->id }}').showModal()" class="hover:underline" style="color: var(--accent);">Edit</button>
                        <form method="POST" action="{{ route('rps.destroy', $rps) }}" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="hover:underline" style="color: #a13d3d;">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="p-6 text-center text-sm" style="color: var(--muted-foreground);">Belum ada RPS.</p>
            @endforelse
        </div>
    </div>
    

@foreach ($mataKuliah->cpmk as $cpmk)
    <dialog id="modalEditCpmk{{ $cpmk->id }}" class="ui-modal">
        <form method="POST" action="{{ route('cpmk.update', $cpmk) }}" class="ui-modal__panel">
            @csrf @method('PUT')
            <input type="hidden" name="mata_kuliah_id" value="{{ $mataKuliah->id }}">
            <input type="hidden" name="form_modal" value="cpmk-edit-{{ $cpmk->id }}">
            <div class="ui-modal__header"><div><h2 class="text-lg font-semibold">Edit CPMK</h2><p class="text-sm" style="color:var(--ui-muted)">{{ $cpmk->kode }}</p></div><button type="button" class="ui-modal__close" onclick="this.closest('dialog').close()">×</button></div>
            <div class="ui-modal__body space-y-4">
                <div><label class="block text-sm font-medium mb-1" for="cpmk_kode_{{ $cpmk->id }}">Kode CPMK *</label><input id="cpmk_kode_{{ $cpmk->id }}" name="kode" value="{{ old('kode', $cpmk->kode) }}" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                <div><label class="block text-sm font-medium mb-1" for="cpmk_deskripsi_{{ $cpmk->id }}">Deskripsi *</label><textarea id="cpmk_deskripsi_{{ $cpmk->id }}" name="deskripsi" rows="4" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)">{{ old('deskripsi', $cpmk->deskripsi) }}</textarea></div>
            </div>
            <div class="ui-modal__footer"><button type="button" class="px-4 py-2 rounded-xl border" onclick="this.closest('dialog').close()">Batal</button><button type="submit" class="px-4 py-2 rounded-xl text-white" style="background:var(--ui-primary)">Simpan Perubahan</button></div>
        </form>
    </dialog>
@endforeach

    @foreach ($mataKuliah->rps as $rps)
    <dialog id="modalEditRps{{ $rps->id }}" class="ui-modal">
        <form method="POST" action="{{ route('rps.update', $rps) }}" class="ui-modal__panel">
            @csrf @method('PUT')
            <input type="hidden" name="mata_kuliah_id" value="{{ $mataKuliah->id }}">
            <input type="hidden" name="form_modal" value="rps-edit-{{ $rps->id }}">
            <div class="ui-modal__header"><div><h2 class="text-lg font-semibold">Edit RPS</h2><p class="text-sm" style="color:var(--ui-muted)">Versi {{ $rps->versi }}</p></div><button type="button" class="ui-modal__close" onclick="this.closest('dialog').close()">×</button></div>
            <div class="ui-modal__body space-y-4">
                <div><label class="block text-sm font-medium mb-1" for="rps_versi_{{ $rps->id }}">Versi *</label><input id="rps_versi_{{ $rps->id }}" name="versi" value="{{ old('versi', $rps->versi) }}" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                <div><label class="block text-sm font-medium mb-1" for="rps_tanggal_{{ $rps->id }}">Tanggal Disusun *</label><input id="rps_tanggal_{{ $rps->id }}" type="date" name="tanggal_disusun" value="{{ old('tanggal_disusun', optional($rps->tanggal_disusun)->format('Y-m-d') ?? $rps->tanggal_disusun) }}" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                <div><label class="block text-sm font-medium mb-1" for="rps_deskripsi_{{ $rps->id }}">Deskripsi Singkat</label><textarea id="rps_deskripsi_{{ $rps->id }}" name="deskripsi_singkat" rows="3" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)">{{ old('deskripsi_singkat', $rps->deskripsi_singkat) }}</textarea></div>
            </div>
            <div class="ui-modal__footer"><button type="button" class="px-4 py-2 rounded-xl border" onclick="this.closest('dialog').close()">Batal</button><button type="submit" class="px-4 py-2 rounded-xl text-white" style="background:var(--ui-primary)">Simpan Perubahan</button></div>
        </form>
    </dialog>
@endforeach

    <dialog id="modalTambahRps" class="ui-modal">
        <form method="POST" action="{{ route('rps.store') }}" class="ui-modal__panel">
            @csrf
            <input type="hidden" name="mata_kuliah_id" value="{{ $mataKuliah->id }}">
            <input type="hidden" name="form_modal" value="rps-create">
            <div class="ui-modal__header"><div><h2 class="text-lg font-semibold">Tambah RPS</h2><p class="text-sm" style="color:var(--ui-muted)">{{ $mataKuliah->kode }} — {{ $mataKuliah->nama }}</p></div><button type="button" class="ui-modal__close" onclick="this.closest('dialog').close()">×</button></div>
            <div class="ui-modal__body space-y-4">
                <div><label for="rps_versi_baru" class="block text-sm font-medium mb-1">Versi *</label><input id="rps_versi_baru" name="versi" value="{{ old('versi') }}" required placeholder="Contoh: 1.0" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                <div><label for="rps_tanggal_baru" class="block text-sm font-medium mb-1">Tanggal Disusun *</label><input id="rps_tanggal_baru" type="date" name="tanggal_disusun" value="{{ old('tanggal_disusun') }}" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                <div><label for="rps_deskripsi_baru" class="block text-sm font-medium mb-1">Deskripsi Singkat (opsional)</label><textarea id="rps_deskripsi_baru" name="deskripsi_singkat" rows="4" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)">{{ old('deskripsi_singkat') }}</textarea></div>
            </div>
            <div class="ui-modal__footer"><button type="button" class="px-4 py-2 rounded-xl border" onclick="this.closest('dialog').close()">Batal</button><button type="submit" class="px-4 py-2 rounded-xl text-white" style="background:var(--ui-primary)">Simpan RPS</button></div>
        </form>
    </dialog>

    <div class="rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
        <div class="px-6 py-4 border-b flex items-center justify-between" style="border-color: var(--border);">
            <h2 class="font-serif-display text-lg">Kelas &amp; Input Nilai</h2>

            <button
                type="button"
                onclick="document.getElementById('form-tambah-kelas').classList.toggle('hidden')"
                class="px-3 py-1.5 rounded-lg text-xs font-medium text-white"
                style="background: var(--primary);"
            >
                + Tambah Kelas
            </button>
        </div>

        @if (session('status'))
            <div class="mx-4 mt-4 rounded-lg p-3 text-sm" style="background: var(--card); color: var(--primary);">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mx-4 mt-4 rounded-lg p-3 text-sm" style="background: var(--card); color: #a13d3d;">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div id="form-tambah-kelas" class="hidden p-4 border-b" style="border-color: var(--border);">
            <form method="POST" action="{{ route('mata-kuliah.kelas.store', $mataKuliah) }}" class="space-y-4">
                @csrf
                <div>
                    <label for="kelas_mahasiswa_id" class="block text-sm font-medium mb-1">
                        Rombongan Kelas Mahasiswa
                    </label>

                    <select
                        id="kelas_mahasiswa_id"
                        name="kelas_mahasiswa_id"
                        required
                        class="w-full rounded-lg border px-3 py-2 text-sm"
                        style="background: var(--card); border-color: var(--border); color: var(--foreground);"
                    >
                        <option value="">Pilih rombongan kelas</option>

                        @foreach (\App\Models\KelasMahasiswa::where('status_kelas', 'aktif')->orderBy('nama_kelas')->get() as $kelasMahasiswa)
                            <option
                                value="{{ $kelasMahasiswa->id }}"
                                @selected(old('kelas_mahasiswa_id') == $kelasMahasiswa->id)
                            >
                                {{ $kelasMahasiswa->nama_kelas }}
                                — Angkatan {{ $kelasMahasiswa->angkatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="semester_id" class="block text-sm font-medium mb-1">
                        Semester Akademik
                    </label>

                    <select
                        id="semester_id"
                        name="semester_id"
                        required
                        class="w-full rounded-lg border px-3 py-2 text-sm"
                        style="background: var(--card); border-color: var(--border); color: var(--foreground);"
                    >
                        <option value="">Pilih semester</option>

                        @foreach (\App\Models\Semester::with('tahunAkademik')->orderBy('tanggal_mulai')->get() as $semester)
                            <option
                                value="{{ $semester->id }}"
                                @selected(old('semester_id') == $semester->id)
                            >
                                {{ ucfirst($semester->jenis) }} — {{ $semester->tahunAkademik->label ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg text-sm font-medium text-white"
                        style="background: var(--primary);"
                    >
                        Simpan Kelas
                    </button>

                    <button
                        type="button"
                        onclick="document.getElementById('form-tambah-kelas').classList.add('hidden')"
                        class="px-4 py-2 rounded-lg border text-sm"
                        style="border-color: var(--border);"
                    >
                        Batal
                    </button>
                </div>
            </form>

        </div>

        <div class="p-2">
            @forelse ($mataKuliah->kelas as $kls)
                <div class="p-4 border-b last:border-b-0 flex justify-between items-center" style="border-color: var(--border);">
                    <div>
                        <p class="text-sm font-medium">{{ $kls->nama }}</p>
                        <p class="text-xs mt-1" style="color: var(--muted-foreground);">
                            {{ ucfirst($kls->semester->jenis ?? '-') }}
                            — {{ $kls->semester->tahunAkademik->label ?? '-' }}
                        </p>
                    </div>

                    <a
                        href="{{ route('nilai-cpmk.create', $kls) }}"
                        class="text-sm hover:underline"
                        style="color: var(--primary);"
                    >
                        Input Nilai CPMK
                    </a>
                </div>
            @empty
                <p class="p-6 text-center text-sm" style="color: var(--muted-foreground);">
                    Belum ada kelas.
                </p>
            @endforelse
        </div>
    </div>

<script>
document.querySelectorAll('dialog.ui-modal').forEach(function(dialog){dialog.addEventListener('click',function(event){if(event.target===dialog)dialog.close();});});
@if ($errors->any())
document.addEventListener('DOMContentLoaded',function(){const key=@json(old('form_modal'));if(key==='rps-create')document.getElementById('modalTambahRps')?.showModal();else if(key==='cpmk-create')document.getElementById('modalTambahCpmk')?.showModal();else if(key?.startsWith('cpmk-edit-'))document.getElementById('modalEditCpmk'+key.slice(10))?.showModal();else if(key?.startsWith('rps-edit-'))document.getElementById('modalEditRps'+key.slice(9))?.showModal();});
@endif
</script>
@endsection