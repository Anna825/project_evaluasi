@extends('layouts.app')

@section('title', 'Program Studi - Evaluasi PBM')
@section('page-title', 'Program Studi')
@section('page-desc', 'Kelola data program studi di bawah jurusan.')

@section('content')

    @if (session('status'))
        <div class="mb-4 rounded-xl border px-4 py-3 text-sm"
             style="background: var(--card); border-color: var(--border); color: var(--foreground);">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-xl border px-4 py-3 text-sm"
             style="background: var(--card); border-color: #e5a3a3; color: #a13d3d;">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex justify-end mb-4">
        <button type="button" onclick="document.getElementById('modalTambahProdi').showModal()"
           class="inline-block px-4 py-2 rounded-xl text-sm font-medium text-white hover:opacity-90"
           style="background: var(--primary);">
            + Tambah Prodi
        </button>
    </div>

    <div class="rounded-2xl border overflow-hidden"
         style="background: var(--card); border-color: var(--border);">

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">

                <thead style="background: var(--secondary);">
                    <tr>
                        <th class="p-3 font-medium">Nama Prodi</th>
                        <th class="p-3 font-medium">Jurusan</th>
                        <th class="p-3 font-medium">Kaprodi</th>
                        <th class="p-3 font-medium">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($prodi as $p)

                        @php
                            $kaprodi = $kaprodiUsers->first(function ($user) use ($p) {
                                return $user->roles
                                    ->where('nama_role', 'kaprodi')
                                    ->where('pivot.prodi_id', $p->id)
                                    ->isNotEmpty();
                            });
                        @endphp

                        <tr class="border-t" style="border-color: var(--border);">

                            <td class="p-3">
                                {{ $p->nama }}
                            </td>

                            <td class="p-3"
                                style="color: var(--muted-foreground);">
                                {{ $p->jurusan->nama ?? '-' }}
                            </td>

                            <td class="p-3">

                                @if ($kaprodi)

                                    <div class="font-medium">
                                        {{ $kaprodi->dosen->nama ?? $kaprodi->name }}
                                    </div>

                                    @if ($kaprodi->dosen?->nidn)
                                        <div class="text-xs mt-1"
                                             style="color: var(--muted-foreground);">
                                            NIDN: {{ $kaprodi->dosen->nidn }}
                                        </div>
                                    @endif

                                @else

                                    <span style="color: var(--muted-foreground);">
                                        Belum ditentukan
                                    </span>

                                @endif

                            </td>

                            <td class="p-3">

                                <div class="flex flex-wrap items-center gap-3">

                                    {{-- Tombol Atur / Ubah Kaprodi --}}
                                    <button type="button"
                                            onclick="openKaprodiModal({{ $p->id }})"
                                            class="hover:underline"
                                            style="color: var(--accent);">
                                        {{ $kaprodi ? 'Ubah Kaprodi' : 'Atur Kaprodi' }}
                                    </button>

                                    {{-- Edit Prodi --}}
                                    <button type="button" onclick="document.getElementById('modalEditProdi{{ $p->id }}').showModal()"
                                       class="hover:underline" style="color: var(--accent);">Edit</button>

                                    {{-- Hapus Prodi --}}
                                    <form method="POST"
                                          action="{{ route('prodi.destroy', $p) }}"
                                          onsubmit="return confirm('Yakin hapus prodi ini? Semua data mahasiswa/dosen terkait akan ikut terhapus!')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="hover:underline"
                                                style="color: #a13d3d;">
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4"
                                class="p-6 text-center"
                                style="color: var(--muted-foreground);">
                                Belum ada data prodi.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

    </div>


    {{-- =========================================================
         MODAL ATUR / UBAH KAPRODI
         ========================================================= --}}

    @foreach ($prodi as $p)

        @php
            $kaprodi = $kaprodiUsers->first(function ($user) use ($p) {
                return $user->roles
                    ->where('nama_role', 'kaprodi')
                    ->where('pivot.prodi_id', $p->id)
                    ->isNotEmpty();
            });

            $dosenAktif = $dosenAktifPerProdi->get($p->id, collect());
        @endphp


        <div id="kaprodi-modal-{{ $p->id }}"
             class="fixed inset-0 z-50 hidden items-center justify-center p-4"
             style="background: rgba(0, 0, 0, 0.45);"
             onclick="closeKaprodiModal({{ $p->id }})">

            <div class="w-full max-w-md rounded-2xl border p-6 shadow-xl"
                 style="background: var(--card); border-color: var(--border);"
                 onclick="event.stopPropagation()">


                {{-- Header Modal --}}
                <div class="flex items-center justify-between mb-5">

                    <div>
                        <h2 class="text-lg font-semibold">
                            {{ $kaprodi ? 'Ubah Kaprodi' : 'Atur Kaprodi' }}
                        </h2>

                        <p class="text-sm mt-1"
                           style="color: var(--muted-foreground);">
                            {{ $p->nama }}
                        </p>
                    </div>


                    {{-- Tombol X --}}
                    <button type="button"
                            onclick="closeKaprodiModal({{ $p->id }})"
                            class="text-xl leading-none hover:opacity-70"
                            aria-label="Tutup">
                        &times;
                    </button>

                </div>


                {{-- Form --}}
                <form method="POST"
                      action="{{ route('prodi.set-kaprodi', $p) }}">

                    @csrf


                    <label class="block text-sm font-medium mb-2">
                        Pilih Dosen
                    </label>


                    <select name="dosen_id"
                            required
                            class="w-full rounded-xl border px-3 py-2 text-sm"
                            style="background: var(--card); border-color: var(--border);">

                        <option value="">
                            -- Pilih Dosen --
                        </option>


                        @forelse ($dosenAktif as $dosen)

                            <option value="{{ $dosen->id }}"
                                @selected($kaprodi && $kaprodi->dosen?->id === $dosen->id)>

                                {{ $dosen->nama }}

                                @if ($dosen->nidn)
                                    — {{ $dosen->nidn }}
                                @endif

                            </option>

                        @empty

                            <option value="" disabled>
                                Belum ada Dosen aktif di Prodi ini
                            </option>

                        @endforelse

                    </select>


                    {{-- Tombol --}}
                    <div class="flex justify-end gap-3 mt-5">

                        <button type="button"
                                onclick="closeKaprodiModal({{ $p->id }})"
                                class="px-4 py-2 rounded-xl text-sm border"
                                style="border-color: var(--border);">
                            Batal
                        </button>


                        <button type="submit"
                                class="px-4 py-2 rounded-xl text-sm font-medium text-white hover:opacity-90"
                                style="background: var(--primary);">
                            Simpan Kaprodi
                        </button>

                    </div>

                </form>

            </div>

        </div>

    @endforeach


    {{-- =======JAVASCRIPT MODAL======= --}}
    <script>
        function openKaprodiModal(prodiId) {
            const modal = document.getElementById(
                'kaprodi-modal-' + prodiId
            );

            if (!modal) {
                return;
            }
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeKaprodiModal(prodiId) {
            const modal = document.getElementById(
                'kaprodi-modal-' + prodiId
            );
            if (!modal) {
                return;
            }
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        // Tekan tombol Escape untuk menutup modal
        document.addEventListener('keydown', function (event) {
            if (event.key !== 'Escape') {
                return;
            }
            document.querySelectorAll('[id^="kaprodi-modal-"]').forEach(function (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });
        });
    </script>

    <dialog id="modalTambahProdi" class="ui-modal">
        <form method="POST" action="{{ route('prodi.store') }}?modal=create" class="ui-modal__panel">
            @csrf <input type="hidden" name="form_modal" value="create">
            <div class="ui-modal__header"><div><h2 class="text-lg font-semibold">Tambah Program Studi</h2><p class="text-sm" style="color:var(--ui-muted)">Lengkapi informasi program studi.</p></div><button type="button" class="ui-modal__close" onclick="this.closest('dialog').close()" aria-label="Tutup">×</button></div>
            <div class="ui-modal__body space-y-4">
                <div><label class="block text-sm font-medium mb-1" for="prodi_jurusan_baru">Jurusan</label><select id="prodi_jurusan_baru" name="jurusan_id" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><option value="">-- Pilih Jurusan --</option>@foreach ($jurusanList as $jurusan)<option value="{{ $jurusan->id }}" @selected(old('jurusan_id') == $jurusan->id)>{{ $jurusan->nama }}</option>@endforeach</select></div>
                <div><label class="block text-sm font-medium mb-1" for="prodi_nama_baru">Nama Program Studi</label><input id="prodi_nama_baru" name="nama" value="{{ old('nama') }}" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
            </div>
            <div class="ui-modal__footer"><button type="button" class="px-4 py-2 rounded-xl border" onclick="this.closest('dialog').close()">Batal</button><button type="submit" class="px-4 py-2 rounded-xl text-white" style="background:var(--ui-primary)">Simpan Prodi</button></div>
        </form>
    </dialog>
    @foreach ($prodi as $p)
        <dialog id="modalEditProdi{{ $p->id }}" class="ui-modal">
            <form method="POST" action="{{ route('prodi.update', $p) }}?modal=edit-{{ $p->id }}" class="ui-modal__panel">
                @csrf @method('PUT') <input type="hidden" name="form_modal" value="edit-{{ $p->id }}">
                <div class="ui-modal__header"><div><h2 class="text-lg font-semibold">Edit Program Studi</h2><p class="text-sm" style="color:var(--ui-muted)">{{ $p->nama }}</p></div><button type="button" class="ui-modal__close" onclick="this.closest('dialog').close()" aria-label="Tutup">×</button></div>
                <div class="ui-modal__body space-y-4">
                    <div><label class="block text-sm font-medium mb-1" for="prodi_jurusan_{{ $p->id }}">Jurusan</label><select id="prodi_jurusan_{{ $p->id }}" name="jurusan_id" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><option value="">-- Pilih Jurusan --</option>@foreach ($jurusanList as $jurusan)<option value="{{ $jurusan->id }}" @selected(old('jurusan_id', $p->jurusan_id) == $jurusan->id)>{{ $jurusan->nama }}</option>@endforeach</select></div>
                    <div><label class="block text-sm font-medium mb-1" for="prodi_nama_{{ $p->id }}">Nama Program Studi</label><input id="prodi_nama_{{ $p->id }}" name="nama" value="{{ old('nama', $p->nama) }}" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                </div>
                <div class="ui-modal__footer"><button type="button" class="px-4 py-2 rounded-xl border" onclick="this.closest('dialog').close()">Batal</button><button type="submit" class="px-4 py-2 rounded-xl text-white" style="background:var(--ui-primary)">Simpan Perubahan</button></div>
            </form>
        </dialog>
    @endforeach
    <script>
    document.querySelectorAll('dialog.ui-modal').forEach(function(dialog) { dialog.addEventListener('click', function(event) { if (event.target === dialog) dialog.close(); }); });
    @if ($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        const key = @json(request('modal') ?: old('form_modal'));
        if (key === 'create') document.getElementById('modalTambahProdi')?.showModal();
        else if (key && key.startsWith('edit-')) document.getElementById('modalEditProdi' + key.slice(5))?.showModal();
    });
    @endif
    </script>

@endsection