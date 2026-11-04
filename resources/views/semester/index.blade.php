@extends('layouts.app')

@section('title', 'Semester - Evaluasi PBM')

@section('page-title', 'Semester')

@section('page-desc', 'Kelola semester akademik yang digunakan dalam proses pembelajaran.')

@section('content')

<div class="w-full">

    {{-- Pesan sukses --}}
    @if (session('status'))
        <div
            class="mb-5 rounded-xl border px-4 py-3 text-sm"
            style="background: rgba(46, 125, 50, 0.08); border-color: rgba(46, 125, 50, 0.25); color: var(--foreground);"
        >
            {{ session('status') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-lg font-semibold">
                Daftar Semester
            </h2>

            <p
                class="text-sm mt-1"
                style="color: var(--muted-foreground);"
            >
                Semester yang tersedia berdasarkan tahun akademik.
            </p>
        </div>

        <button type="button" onclick="document.getElementById('modalTambahSemester').showModal()"
            class="inline-flex items-center gap-2 rounded-xl px-5 py-3 text-sm font-medium text-white transition hover:opacity-90"
            style="background: var(--primary);">+ Tambah Semester</button>
    </div>


    {{-- Tabel Semester --}}
    <div
        class="rounded-2xl border overflow-hidden"
        style="background: var(--card); border-color: var(--border);"
    >

        @if ($semesterList->count())

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead
                        style="background: var(--secondary);"
                    >
                        <tr>
                            <th class="px-5 py-4 text-left font-semibold">
                                No
                            </th>

                            <th class="px-5 py-4 text-left font-semibold">
                                Tahun Akademik
                            </th>

                            <th class="px-5 py-4 text-left font-semibold">
                                Jenis Semester
                            </th>

                            <th class="px-5 py-4 text-left font-semibold">
                                Tanggal Mulai
                            </th>

                            <th class="px-5 py-4 text-left font-semibold">
                                Tanggal Selesai
                            </th>

                            <th class="px-5 py-4 text-right font-semibold">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($semesterList as $index => $semester)

                            <tr
                                class="border-t"
                                style="border-color: var(--border);"
                            >

                                <td class="px-5 py-4">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-5 py-4 font-medium">
                                    {{ $semester->tahunAkademik->label ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ ucfirst($semester->jenis) }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ \Carbon\Carbon::parse($semester->tanggal_mulai)->format('d/m/Y') }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ \Carbon\Carbon::parse($semester->tanggal_selesai)->format('d/m/Y') }}
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end items-center gap-2">

                                        <button type="button" onclick="document.getElementById('modalEditSemester{{ $semester->id }}').showModal()"
                                            class="rounded-lg border px-3 py-2 text-xs font-medium transition hover:opacity-70"
                                            style="border-color: var(--border);">Edit</button>

                                        <form
                                            action="{{ route('semester.destroy', $semester) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus semester ini?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="rounded-lg border px-3 py-2 text-xs font-medium transition hover:opacity-70"
                                                style="border-color: #ef4444; color: #ef4444;"
                                            >
                                                Hapus
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            {{-- Empty state --}}
            <div class="px-6 py-14 text-center">

                <div
                    class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl"
                    style="background: var(--secondary);"
                >
                    <span class="text-2xl">📅</span>
                </div>

                <h3 class="text-lg font-semibold mb-2">
                    Belum Ada Semester
                </h3>

                <p
                    class="text-sm mb-6"
                    style="color: var(--muted-foreground);"
                >
                    Belum ada semester akademik yang dibuat.
                    Silakan tambahkan semester terlebih dahulu.
                </p>

                <button type="button" onclick="document.getElementById('modalTambahSemester').showModal()"
                    class="inline-flex items-center gap-2 rounded-xl px-5 py-3 text-sm font-medium text-white transition hover:opacity-90"
                    style="background: var(--primary);">+ Tambah Semester</button>

            </div>

        @endif

    </div>

</div>


    @if ($errors->any())
        <div class="mb-4 rounded-xl border px-4 py-3 text-sm" style="background:#fef2f2;border-color:#fecaca;color:#991b1b;">
            <ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif
    <dialog id="modalTambahSemester" class="ui-modal">
        <form method="POST" action="{{ route('semester.store') }}?modal=create" class="ui-modal__panel">
            @csrf <input type="hidden" name="form_modal" value="create">
            <div class="ui-modal__header"><div><h2 class="text-lg font-semibold">Tambah Semester</h2><p class="text-sm" style="color:var(--ui-muted)">Masukkan tahun akademik dan periode semester.</p></div><button type="button" class="ui-modal__close" onclick="this.closest('dialog').close()" aria-label="Tutup">×</button></div>
            <div class="ui-modal__body space-y-4">
                <div><label class="block text-sm font-medium mb-1" for="semester_tahun_baru">Tahun Akademik</label><select id="semester_tahun_baru" name="tahun_akademik_id" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><option value="">-- Pilih Tahun Akademik --</option>@foreach ($tahunAkademikList as $tahun)<option value="{{ $tahun->id }}" @selected(old('tahun_akademik_id') == $tahun->id)>{{ $tahun->label }}</option>@endforeach</select></div>
                <div><label class="block text-sm font-medium mb-1" for="semester_jenis_baru">Jenis Semester</label><select id="semester_jenis_baru" name="jenis" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><option value="">-- Pilih Jenis --</option><option value="ganjil" @selected(old('jenis') === 'ganjil')>Ganjil</option><option value="genap" @selected(old('jenis') === 'genap')>Genap</option></select></div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4"><div><label class="block text-sm font-medium mb-1" for="semester_mulai_baru">Tanggal Mulai</label><input id="semester_mulai_baru" type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div><div><label class="block text-sm font-medium mb-1" for="semester_selesai_baru">Tanggal Selesai</label><input id="semester_selesai_baru" type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div></div>
            </div>
            <div class="ui-modal__footer"><button type="button" class="px-4 py-2 rounded-xl border" onclick="this.closest('dialog').close()">Batal</button><button type="submit" class="px-4 py-2 rounded-xl text-white" style="background:var(--ui-primary)">Simpan Semester</button></div>
        </form>
    </dialog>
    @foreach ($semesterList as $semester)
        <dialog id="modalEditSemester{{ $semester->id }}" class="ui-modal">
            <form method="POST" action="{{ route('semester.update', $semester) }}?modal=edit-{{ $semester->id }}" class="ui-modal__panel">
                @csrf @method('PUT') <input type="hidden" name="form_modal" value="edit-{{ $semester->id }}">
                <div class="ui-modal__header"><div><h2 class="text-lg font-semibold">Edit Semester</h2><p class="text-sm" style="color:var(--ui-muted)">{{ $semester->tahunAkademik->label ?? '-' }} · {{ ucfirst($semester->jenis) }}</p></div><button type="button" class="ui-modal__close" onclick="this.closest('dialog').close()" aria-label="Tutup">×</button></div>
                <div class="ui-modal__body space-y-4">
                    <div><label class="block text-sm font-medium mb-1" for="semester_tahun_{{ $semester->id }}">Tahun Akademik</label><select id="semester_tahun_{{ $semester->id }}" name="tahun_akademik_id" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><option value="">-- Pilih Tahun Akademik --</option>@foreach ($tahunAkademikList as $tahun)<option value="{{ $tahun->id }}" @selected(old('tahun_akademik_id', $semester->tahun_akademik_id) == $tahun->id)>{{ $tahun->label }}</option>@endforeach</select></div>
                    <div><label class="block text-sm font-medium mb-1" for="semester_jenis_{{ $semester->id }}">Jenis Semester</label><select id="semester_jenis_{{ $semester->id }}" name="jenis" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><option value="ganjil" @selected(old('jenis', $semester->jenis) === 'ganjil')>Ganjil</option><option value="genap" @selected(old('jenis', $semester->jenis) === 'genap')>Genap</option></select></div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4"><div><label class="block text-sm font-medium mb-1" for="semester_mulai_{{ $semester->id }}">Tanggal Mulai</label><input id="semester_mulai_{{ $semester->id }}" type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', \Illuminate\Support\Carbon::parse($semester->tanggal_mulai)->format('Y-m-d')) }}" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div><div><label class="block text-sm font-medium mb-1" for="semester_selesai_{{ $semester->id }}">Tanggal Selesai</label><input id="semester_selesai_{{ $semester->id }}" type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', \Illuminate\Support\Carbon::parse($semester->tanggal_selesai)->format('Y-m-d')) }}" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div></div>
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
        if (key === 'create') document.getElementById('modalTambahSemester')?.showModal();
        else if (key && key.startsWith('edit-')) document.getElementById('modalEditSemester' + key.slice(5))?.showModal();
    });
    @endif
    </script>

@endsection