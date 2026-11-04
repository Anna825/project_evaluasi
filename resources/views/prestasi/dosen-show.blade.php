@extends('layouts.app')

@section('title', 'Detail Prestasi Dosen - Evaluasi PBM')
@section('page-title', 'Detail Prestasi Dosen')
@section('page-desc', 'Informasi lengkap prestasi yang Anda raih.')

@section('content')

<div class="w-full">

    <div class="mb-6">
        <a href="{{ route('prestasi-dosen.index') }}"
           class="inline-flex items-center gap-2 text-sm font-medium hover:underline"
           style="color: var(--primary);">
            ← Kembali ke Prestasi Saya
        </a>
    </div>

    <div class="rounded-2xl border overflow-hidden"
         style="background: var(--card); border-color: var(--border);">

        {{-- DATA DOSEN --}}
        <div class="px-6 py-5 border-b"
             style="border-color: var(--border);">

            <h2 class="font-serif-display text-2xl">
                Data Dosen
            </h2>

        </div>

        <div class="p-6 md:p-8">

            @foreach ($prestasi->dosen as $dosen)

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

                    <div>
                        <p class="text-sm"
                           style="color: var(--muted-foreground);">
                            Nama Dosen
                        </p>

                        <p class="font-medium mt-1">
                            {{ $dosen->nama }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm"
                           style="color: var(--muted-foreground);">
                            NIDN
                        </p>

                        <p class="font-medium mt-1">
                            {{ $dosen->nidn ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm"
                           style="color: var(--muted-foreground);">
                            Program Studi
                        </p>

                        <p class="font-medium mt-1">
                            {{ $dosen->prodi->nama ?? '-' }}
                        </p>
                    </div>

                </div>

            @endforeach


            {{-- DATA PRESTASI --}}
            <div class="pt-6 border-t"
                 style="border-color: var(--border);">

                <h2 class="font-serif-display text-2xl mb-6">
                    Data Prestasi
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <p class="text-sm"
                           style="color: var(--muted-foreground);">
                            Nama Kegiatan
                        </p>

                        <p class="font-medium mt-1">
                            {{ $prestasi->nama_kegiatan }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm"
                           style="color: var(--muted-foreground);">
                            Tingkat
                        </p>

                        <p class="font-medium mt-1">
                            {{ $prestasi->tingkat ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm"
                           style="color: var(--muted-foreground);">
                            Jenis
                        </p>

                        <p class="font-medium mt-1">
                            {{ $prestasi->jenis ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm"
                           style="color: var(--muted-foreground);">
                            Peringkat / Capaian
                        </p>

                        <p class="font-medium mt-1">
                            {{ $prestasi->peringkat ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm"
                           style="color: var(--muted-foreground);">
                            Tempat Pelaksanaan
                        </p>

                        <p class="font-medium mt-1">
                            {{ $prestasi->tempat_pelaksanaan ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm"
                           style="color: var(--muted-foreground);">
                            Tanggal Penerimaan
                        </p>

                        <p class="font-medium mt-1">
                            {{ $prestasi->tanggal_penerimaan?->format('d-m-Y') ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm"
                           style="color: var(--muted-foreground);">
                            Tahun Akademik
                        </p>

                        <p class="font-medium mt-1">
                            {{ $prestasi->tahunAkademik->label ?? '-' }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- AKSI --}}
            <div class="flex flex-col sm:flex-row gap-3 pt-6 mt-6 border-t"
                 style="border-color: var(--border);">

                <a href="{{ route('prestasi-dosen.index') }}"
                   class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-medium"
                   style="background: var(--secondary); color: var(--foreground);">
                    ← Kembali
                </a>

                <button type="button" onclick="document.getElementById('modalEditPrestasi').showModal()" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white" style="background: var(--primary);">
                    Edit Prestasi
                </button>

                <form method="POST"
                      action="{{ route('prestasi-dosen.destroy', $prestasi->id) }}"
                      onsubmit="return confirm('Yakin ingin menghapus prestasi ini? Data yang dihapus tidak dapat dikembalikan.');">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white"
                            style="background: #dc2626;">
                        Hapus Prestasi
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


    @if($errors->any())<div class="mb-4 rounded-xl border px-4 py-3 text-sm" style="background:#fef2f2;border-color:#fecaca;color:#991b1b"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <dialog id="modalEditPrestasi" class="ui-modal">
        <form method="POST" action="{{ route('prestasi-dosen.update',$prestasi->id) }}" class="ui-modal__panel">
            @csrf @method('PUT') <input type="hidden" name="form_modal" value="prestasi-edit">
            <div class="ui-modal__header"><div><h2 class="text-lg font-semibold">Edit Prestasi</h2><p class="text-sm" style="color:var(--ui-muted)">{{ $prestasi->nama_kegiatan }}</p></div><button type="button" class="ui-modal__close" onclick="this.closest('dialog').close()">×</button></div>
            <div class="ui-modal__body space-y-4">
                <div><label class="block text-sm font-medium mb-1">Nama Kegiatan *</label><input name="nama_kegiatan" value="{{ old('nama_kegiatan',$prestasi->nama_kegiatan) }}" required maxlength="255" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium mb-1">Tingkat</label><input name="tingkat" value="{{ old('tingkat',$prestasi->tingkat) }}" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                    <div><label class="block text-sm font-medium mb-1">Jenis</label><input name="jenis" value="{{ old('jenis',$prestasi->jenis) }}" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                    <div><label class="block text-sm font-medium mb-1">Peringkat/Capaian</label><input name="peringkat" value="{{ old('peringkat',$prestasi->peringkat) }}" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                    <div><label class="block text-sm font-medium mb-1">Tempat Pelaksanaan</label><input name="tempat_pelaksanaan" value="{{ old('tempat_pelaksanaan',$prestasi->tempat_pelaksanaan) }}" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                </div>
                <div><label class="block text-sm font-medium mb-1">Tanggal Penerimaan</label><input type="date" name="tanggal_penerimaan" value="{{ old('tanggal_penerimaan',$prestasi->tanggal_penerimaan?->format('Y-m-d')) }}" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                <div><label class="block text-sm font-medium mb-1">Tahun Akademik *</label><select name="tahun_akademik_id" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)">@foreach(\App\Models\TahunAkademik::orderByDesc('id')->get() as $ta)<option value="{{ $ta->id }}" @selected(old('tahun_akademik_id',$prestasi->tahun_akademik_id)==$ta->id)>{{ $ta->label }}</option>@endforeach</select></div>
            </div>
            <div class="ui-modal__footer"><button type="button" class="px-4 py-2 rounded-xl border" onclick="this.closest('dialog').close()">Batal</button><button type="submit" class="px-4 py-2 rounded-xl text-white" style="background:var(--ui-primary)">Simpan Perubahan</button></div>
        </form>
    </dialog>
    <script>const editPrestasiDialog=document.getElementById('modalEditPrestasi');editPrestasiDialog?.addEventListener('click',e=>{if(e.target===editPrestasiDialog)editPrestasiDialog.close()});@if($errors->any() && old('form_modal')==='prestasi-edit')document.addEventListener('DOMContentLoaded',()=>editPrestasiDialog?.showModal());@endif</script>

@endsection