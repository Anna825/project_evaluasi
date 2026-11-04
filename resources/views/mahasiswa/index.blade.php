@extends('layouts.app')

@section('title', 'Data Mahasiswa - Evaluasi PBM')
@section('page-title', 'Data Mahasiswa')
@section('page-desc', 'Kelola data mahasiswa berdasarkan kelas')

@section('content')

    @if (session('status'))
        <div class="text-sm rounded-lg px-4 py-3 mb-5"
             style="background:#e8f5e9; color:#2e7d32;">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div class="text-sm rounded-lg px-4 py-3 mb-5 border"
             style="background:#fef2f2; color:#991b1b; border-color:#fecaca;">
            {{ session('error') }}
        </div>
    @endif

    {{-- Header Action --}}
    <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between mb-6">

        <div>
            <h2 class="text-lg font-semibold">
                Kelas Mahasiswa
            </h2>
            <p class="text-sm mt-1" style="color: var(--muted-foreground);">
                Pilih kelas untuk melihat daftar mahasiswa.
            </p>
        </div>

        <div class="flex gap-2 shrink-0">
            <a href="{{ route('mahasiswa.import.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium border hover:bg-gray-50 transition"
                style="border-color: var(--border); color: var(--primary); background: var(--card);">

                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>

                Import Excel
            </a>

            <button type="button" onclick="document.getElementById('modalTambahMahasiswa').showModal()" class="btn-primary px-4 py-2.5 rounded-lg text-sm font-medium text-white" style="background: var(--primary);">+ Tambah Mahasiswa</button>
        </div>
    </div>


    @if($errors->any())<div class="mb-4 rounded-xl border px-4 py-3 text-sm" style="background:#fef2f2;border-color:#fecaca;color:#991b1b"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <dialog id="modalTambahMahasiswa" class="ui-modal">
        <form method="POST" action="{{ route('mahasiswa.store') }}" class="ui-modal__panel">
            @csrf <input type="hidden" name="form_modal" value="mahasiswa-create">
            <div class="ui-modal__header"><div><h2 class="text-lg font-semibold">Tambah Mahasiswa</h2><p class="text-sm" style="color:var(--ui-muted)">Lengkapi identitas dan penempatan kelas mahasiswa.</p></div><button type="button" class="ui-modal__close" onclick="this.closest('dialog').close()">×</button></div>
            <div class="ui-modal__body space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium mb-1">NIM *</label><input name="nim" value="{{ old('nim') }}" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                    <div><label class="block text-sm font-medium mb-1">Nama Lengkap *</label><input name="nama" value="{{ old('nama') }}" required maxlength="255" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                </div>
                <div><label class="block text-sm font-medium mb-1">Program Studi *</label><select id="mhs_prodi_id" name="prodi_id" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><option value="">Pilih Program Studi</option>@foreach($prodiList as $prodi)<option value="{{ $prodi->id }}" @selected(old('prodi_id')==$prodi->id)>{{ $prodi->nama }}</option>@endforeach</select></div>
                <div><label class="block text-sm font-medium mb-1">Kelas *</label><select id="mhs_kelas_id" name="kelas_mahasiswa_id" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><option value="">Pilih Kelas</option>@foreach($kelasList as $kelas)<option value="{{ $kelas->id }}" data-prodi="{{ $kelas->prodi_id }}" @selected(old('kelas_mahasiswa_id')==$kelas->id)>{{ $kelas->nama_kelas }} — {{ $kelas->prodi->nama ?? '-' }}</option>@endforeach<option value="baru" @selected(old('kelas_mahasiswa_id')==='baru')>+ Kelas belum tersedia</option></select></div>
                <div id="mhs_kelas_baru_wrap" class="{{ old('kelas_mahasiswa_id')==='baru'?'':'hidden' }}"><label class="block text-sm font-medium mb-1">Nama Kelas Baru *</label><input id="mhs_kelas_baru" name="kelas_baru" value="{{ old('kelas_baru') }}" placeholder="Contoh: 1AEA-1" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><p class="text-xs mt-1" style="color:var(--ui-muted)">Format nama kelas: tingkat + kode prodi + nomor paralel, misalnya 1AEA-1.</p></div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium mb-1">Angkatan Masuk *</label><input type="number" name="angkatan" min="2000" max="{{ date('Y')+1 }}" value="{{ old('angkatan',date('Y')) }}" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                    <div><label class="block text-sm font-medium mb-1">Status *</label><select name="status" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><option value="aktif" @selected(old('status','aktif')==='aktif')>Aktif</option><option value="cuti" @selected(old('status')==='cuti')>Cuti</option><option value="lulus" @selected(old('status')==='lulus')>Lulus</option><option value="DO" @selected(old('status')==='DO')>DO</option></select></div>
                </div>
            </div>
            <div class="ui-modal__footer"><button type="button" class="px-4 py-2 rounded-xl border" onclick="this.closest('dialog').close()">Batal</button><button type="submit" class="px-4 py-2 rounded-xl text-white" style="background:var(--ui-primary)">Simpan Mahasiswa</button></div>
        </form>
    </dialog>
    <script>
    const mhsDialog=document.getElementById('modalTambahMahasiswa'),prodiSelect=document.getElementById('mhs_prodi_id'),kelasSelect=document.getElementById('mhs_kelas_id'),kelasBaruWrap=document.getElementById('mhs_kelas_baru_wrap'),kelasBaruInput=document.getElementById('mhs_kelas_baru');
    function filterMhsKelas(){const prodi=prodiSelect?.value;Array.from(kelasSelect?.options||[]).forEach(o=>{if(o.dataset.prodi){o.hidden=!!prodi&&o.dataset.prodi!==prodi;if(o.hidden&&o.selected)kelasSelect.value='';}});const isNew=kelasSelect?.value==='baru';kelasBaruWrap?.classList.toggle('hidden',!isNew);if(kelasBaruInput)kelasBaruInput.required=isNew;}
    prodiSelect?.addEventListener('change',filterMhsKelas);kelasSelect?.addEventListener('change',filterMhsKelas);filterMhsKelas();mhsDialog?.addEventListener('click',e=>{if(e.target===mhsDialog)mhsDialog.close()});
    @if($errors->any() && old('form_modal')==='mahasiswa-create')document.addEventListener('DOMContentLoaded',()=>mhsDialog?.showModal());@endif
    </script>

    {{-- Pencarian mahasiswa dan filter Program Studi --}}
    <form method="GET" action="{{ route('mahasiswa.index') }}"
          class="rounded-2xl border p-4 mb-5 flex flex-col md:flex-row gap-3"
          style="background: var(--card); border-color: var(--border);">
        <div class="flex-1">
            <label for="search" class="block text-sm font-medium mb-1">Cari mahasiswa atau kelas</label>
            <input id="search" type="search" name="search" value="{{ $search ?? request('search') }}"
                   placeholder="Masukkan nama mahasiswa, NIM, atau nama kelas..."
                   class="w-full rounded-xl border px-3 py-2.5 text-sm"
                   style="background: var(--background); border-color: var(--border); color: var(--foreground);">
        </div>
        <div class="md:w-64">
            <label for="prodi_id" class="block text-sm font-medium mb-1">Program Studi</label>
            <select id="prodi_id" name="prodi_id"
                    class="w-full rounded-xl border px-3 py-2.5 text-sm"
                    style="background: var(--background); border-color: var(--border); color: var(--foreground);">
                <option value="">Semua Program Studi</option>
                @foreach ($prodiList as $prodi)
                    <option value="{{ $prodi->id }}" @selected((string) ($prodiId ?? '') === (string) $prodi->id)>
                        {{ $prodi->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-white"
                    style="background: var(--primary);">Cari</button>
            <a href="{{ route('mahasiswa.index') }}"
               class="px-4 py-2.5 rounded-xl text-sm font-medium border"
               style="border-color: var(--border); color: var(--foreground);">Reset</a>
        </div>
    </form>

    @if (($search ?? '') !== '' || ($prodiId ?? '') !== '')
        <p class="text-sm mb-4" style="color: var(--muted-foreground);">
            Menampilkan {{ $kelasMahasiswa->count() }} kelas yang sesuai dengan filter.
        </p>
    @endif

    {{-- Daftar Folder Kelas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

        @forelse ($kelasMahasiswa as $kelas)

            <div
               class="group rounded-2xl border p-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-md"
               style="background: var(--card); border-color: var(--border);">
                <a href="{{ route('mahasiswa.kelas.show', $kelas) }}" class="block">

                <div class="flex items-start justify-between gap-4">

                    {{-- Icon Folder --}}
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0"
                         style="background: var(--secondary); color: var(--primary);">

                        <svg width="25" height="25" fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M3 7.5A2.5 2.5 0 015.5 5h4l2 2h7A2.5 2.5 0 0121 9.5v7A2.5 2.5 0 0118.5 19h-13A2.5 2.5 0 013 16.5v-9z" />

                        </svg>
                    </div>

                    {{-- Arrow --}}
                    <svg width="20" height="20"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2"
                         class="transition-transform duration-200 group-hover:translate-x-1"
                         style="color: var(--muted-foreground);">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 5l7 7-7 7" />

                    </svg>
                </div>

                {{-- Informasi Kelas --}}
                <div class="mt-5">

                    <h3 class="text-lg font-semibold">
                        {{ $kelas->nama_kelas }}
                        @if ($kelas->angkatan_mayoritas)
                            <span class="font-normal">({{ $kelas->angkatan_mayoritas }})</span>
                        @endif
                    </h3>

                    <p class="text-sm mt-1"
                       style="color: var(--muted-foreground);">
                        {{ $kelas->prodi->nama ?? '-' }}
                    </p>

                    <div class="flex items-center justify-between mt-5 pt-4 border-t"
                         style="border-color: var(--border);">

                        <div>
                            <p class="text-xs"
                               style="color: var(--muted-foreground);">
                                Tingkat
                            </p>

                            <p class="text-sm font-semibold mt-0.5">
                                {{ $kelas->tingkat }}
                            </p>
                        </div>

                        <div class="text-right">
                            <p class="text-xs"
                               style="color: var(--muted-foreground);">
                                Mahasiswa
                            </p>

                            <p class="text-sm font-semibold mt-0.5">
                                {{ $kelas->mahasiswa_count }} orang
                            </p>
                        </div>

                    </div>

                </div>
                </a>

                <div class="flex justify-end mt-4 pt-3 border-t" style="border-color: var(--border);">
                    <form action="{{ route('mahasiswa.kelas.destroy', $kelas) }}"
                          method="POST"
                          onsubmit="return confirm('Hapus card kelas {{ $kelas->nama_kelas }}? Kelas hanya dapat dihapus jika seluruh relasi mahasiswa dan riwayatnya sudah tidak ada.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-2 text-sm font-medium px-3 py-2 rounded-lg border hover:bg-red-50 transition"
                                style="color: #b91c1c; border-color: #fecaca;">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12m-10 0V5h8v2m-9 0 1 13h8l1-13M10 11v5m4-5v5"/>
                            </svg>
                            Hapus Card
                        </button>
                    </form>
                </div>
            </div>

        @empty

            <div class="col-span-full rounded-2xl border p-10 text-center"
                 style="background: var(--card); border-color: var(--border);">

                <div class="mx-auto w-14 h-14 rounded-xl flex items-center justify-center mb-4"
                     style="background: var(--secondary); color: var(--muted-foreground);">

                    <svg width="28" height="28"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M3 7.5A2.5 2.5 0 015.5 5h4l2 2h7A2.5 2.5 0 0121 9.5v7A2.5 2.5 0 0118.5 19h-13A2.5 2.5 0 013 16.5v-9z" />

                    </svg>

                </div>

                <h3 class="font-semibold">
                    Belum ada kelas mahasiswa
                </h3>

                <p class="text-sm mt-1"
                   style="color: var(--muted-foreground);">
                    Import data mahasiswa untuk membuat folder kelas secara otomatis.
                </p>

            </div>

        @endforelse

    </div>

@endsection