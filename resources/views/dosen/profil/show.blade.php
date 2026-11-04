@extends('layouts.app')

@section('page-title', 'Profil Dosen')
@section('page-desc', 'Informasi profesional dan akademik dosen.')

@section('content')

    @if (session('status'))
        <div class="mb-5 rounded-xl border px-4 py-3 text-sm"
             style="background: var(--secondary); border-color: var(--border);">
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-5">
        <a href="{{ route('dosen.dashboard') }}"
           class="inline-flex items-center gap-2 text-sm font-medium transition hover:opacity-70"
           style="color: var(--muted-foreground);">
            ← Kembali ke Dashboard
        </a>
    </div>

    <div class="rounded-2xl border p-6"
         style="background: var(--card); border-color: var(--border);">

        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h2 class="text-lg font-semibold">
                    Informasi Dosen
                </h2>

                <p class="text-sm mt-1"
                   style="color: var(--muted-foreground);">
                    Data profesional yang digunakan dalam proses akademik dan evaluasi.
                </p>
            </div>

            <button type="button" onclick="document.getElementById('modalEditProfilDosen').showModal()" class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-medium transition hover:opacity-90" style="background: var(--primary); color: white;">Edit Profil</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div class="rounded-xl border p-4"
                 style="border-color: var(--border); background: var(--secondary);">
                <p class="text-xs mb-1" style="color: var(--muted-foreground);">
                    NIDN
                </p>
                <p class="font-medium">
                    {{ $dosen->nidn ?: '-' }}
                </p>
            </div>

            <div class="rounded-xl border p-4"
                 style="border-color: var(--border); background: var(--secondary);">
                <p class="text-xs mb-1" style="color: var(--muted-foreground);">
                    Nama Lengkap
                </p>
                <p class="font-medium">
                    {{ $dosen->nama ?: '-' }}
                </p>
            </div>

            <div class="rounded-xl border p-4"
                 style="border-color: var(--border); background: var(--secondary);">
                <p class="text-xs mb-1" style="color: var(--muted-foreground);">
                    Program Studi
                </p>
                <p class="font-medium">
                    {{ $dosen->prodi?->nama ?: '-' }}
                </p>
            </div>

            <div class="rounded-xl border p-4"
                 style="border-color: var(--border); background: var(--secondary);">
                <p class="text-xs mb-1" style="color: var(--muted-foreground);">
                    Jabatan Fungsional
                </p>
                <p class="font-medium">
                    {{ $dosen->jabatan_fungsional ?: '-' }}
                </p>
            </div>

            <div class="rounded-xl border p-4"
                 style="border-color: var(--border); background: var(--secondary);">
                <p class="text-xs mb-1" style="color: var(--muted-foreground);">
                    Pendidikan Terakhir
                </p>
                <p class="font-medium">
                    {{ $dosen->pendidikan_terakhir ?: '-' }}
                </p>
            </div>

            <div class="rounded-xl border p-4"
                 style="border-color: var(--border); background: var(--secondary);">
                <p class="text-xs mb-1" style="color: var(--muted-foreground);">
                    Institusi Lulusan
                </p>
                <p class="font-medium">
                    {{ $dosen->institusi_lulusan ?: '-' }}
                </p>
            </div>

            <div class="rounded-xl border p-4 md:col-span-2"
                 style="border-color: var(--border); background: var(--secondary);">
                <p class="text-xs mb-1" style="color: var(--muted-foreground);">
                    Nomor HP
                </p>
                <p class="font-medium">
                    {{ $dosen->no_hp ?: '-' }}
                </p>
            </div>

        </div>
    </div>


    @if($errors->any())<div class="mb-4 rounded-xl border px-4 py-3 text-sm" style="background:#fef2f2;border-color:#fecaca;color:#991b1b"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <dialog id="modalEditProfilDosen" class="ui-modal">
        <form method="POST" action="{{ route('dosen.profil.update') }}" class="ui-modal__panel">
            @csrf @method('PUT') <input type="hidden" name="form_modal" value="profile-edit">
            <div class="ui-modal__header"><div><h2 class="text-lg font-semibold">Edit Profil Dosen</h2><p class="text-sm" style="color:var(--ui-muted)">Perbarui informasi profesional dan akademik.</p></div><button type="button" class="ui-modal__close" onclick="this.closest('dialog').close()">×</button></div>
            <div class="ui-modal__body space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium mb-1">NIDN</label><input value="{{ $dosen->nidn }}" disabled class="w-full rounded-xl border px-3 py-2.5" style="background:var(--ui-secondary);border-color:var(--ui-border)"></div>
                    <div><label class="block text-sm font-medium mb-1">Nama Lengkap</label><input value="{{ $dosen->nama }}" disabled class="w-full rounded-xl border px-3 py-2.5" style="background:var(--ui-secondary);border-color:var(--ui-border)"></div>
                </div>
                <div><label class="block text-sm font-medium mb-1">Program Studi</label><input value="{{ $dosen->prodi?->nama }}" disabled class="w-full rounded-xl border px-3 py-2.5" style="background:var(--ui-secondary);border-color:var(--ui-border)"></div>
                <div><label class="block text-sm font-medium mb-1">Jabatan Fungsional</label><input name="jabatan_fungsional" value="{{ old('jabatan_fungsional',$dosen->jabatan_fungsional) }}" maxlength="255" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><p class="text-xs mt-1" style="color:var(--ui-muted)">Perubahan jabatan dicatat sebagai riwayat.</p></div>
                <div><label class="block text-sm font-medium mb-1">Pendidikan Terakhir</label><input name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir',$dosen->pendidikan_terakhir) }}" maxlength="255" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                <div><label class="block text-sm font-medium mb-1">Institusi Lulusan</label><input name="institusi_lulusan" value="{{ old('institusi_lulusan',$dosen->institusi_lulusan) }}" maxlength="255" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                <div><label class="block text-sm font-medium mb-1">Nomor HP</label><input name="no_hp" value="{{ old('no_hp',$dosen->no_hp) }}" maxlength="30" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
            </div>
            <div class="ui-modal__footer"><button type="button" class="px-4 py-2 rounded-xl border" onclick="this.closest('dialog').close()">Batal</button><button type="submit" class="px-4 py-2 rounded-xl text-white" style="background:var(--ui-primary)">Simpan Perubahan</button></div>
        </form>
    </dialog>
    <script>const profileDialog=document.getElementById('modalEditProfilDosen');profileDialog?.addEventListener('click',e=>{if(e.target===profileDialog)profileDialog.close()});@if($errors->any() && old('form_modal')==='profile-edit')document.addEventListener('DOMContentLoaded',()=>profileDialog?.showModal());@endif</script>

@endsection