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
        <a href="{{ route('prodi.create') }}"
           class="inline-block px-4 py-2 rounded-xl text-sm font-medium text-white hover:opacity-90"
           style="background: var(--primary);">
            + Tambah Prodi
        </a>
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
                                    <a href="{{ route('prodi.edit', $p) }}"
                                       class="hover:underline"
                                       style="color: var(--accent);">
                                        Edit
                                    </a>

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


    {{-- =========================================================
         JAVASCRIPT MODAL
         ========================================================= --}}

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

@endsection