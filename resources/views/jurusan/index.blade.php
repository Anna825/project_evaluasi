
@extends('layouts.app')

@section('title', 'Kelola Jurusan - Evaluasi PBM')
@section('page-title', 'Kelola Jurusan')
@section('page-desc', 'Kelola data jurusan dan ketua jurusan.')

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded-xl border px-4 py-3 text-sm"
             style="background: var(--card); border-color: var(--border); color: var(--foreground);">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-xl border px-4 py-3 text-sm"
             style="background: var(--card); border-color: #e5a3a3; color: #a13d3d;">
            {{ session('error') }}
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
        <button type="button" onclick="document.getElementById('modalTambahJurusan').showModal()"
           class="inline-block px-4 py-2 rounded-xl text-sm font-medium text-white hover:opacity-90"
           style="background: var(--primary);">
            + Tambah Jurusan
        </button>
    </div>

    <div class="rounded-2xl border overflow-hidden"
         style="background: var(--card); border-color: var(--border);">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead style="background: var(--secondary);">
                    <tr>
                        <th class="p-3 font-medium">No.</th>
                        <th class="p-3 font-medium">Nama Jurusan</th>
                        <th class="p-3 font-medium">Ketua Jurusan</th>
                        <th class="p-3 font-medium">Jumlah Prodi</th>
                        <th class="p-3 font-medium">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($jurusan as $j)
                        <tr class="border-t" style="border-color: var(--border);">
                            <td class="p-3">
                                {{ $jurusan->firstItem() + $loop->index }}
                            </td>
                            <td class="p-3 font-medium">
                                {{ $j->nama }}
                            </td>
                            <td class="p-3" style="color: var(--muted-foreground);">
                                {{ $j->kajur ?: 'Belum ditentukan' }}
                            </td>
                            <td class="p-3">
                                {{ $j->prodi_count }}
                            </td>
                            <td class="p-3">
                                <div class="flex flex-wrap items-center gap-3">
                                    <button type="button" onclick="document.getElementById('modalEditJurusan{{ $j->id }}').showModal()"
                                       class="hover:underline" style="color: var(--accent);">Edit</button>

                                    <form method="POST"
                                          action="{{ route('jurusan.destroy', $j) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus jurusan ini?')">
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
                            <td colspan="5" class="p-6 text-center"
                                style="color: var(--muted-foreground);">
                                Belum ada data jurusan. Silakan tambahkan jurusan terlebih dahulu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($jurusan->hasPages())
            <div class="border-t p-4" style="border-color: var(--border);">
                {{ $jurusan->links() }}
            </div>
        @endif
    </div>

    <dialog id="modalTambahJurusan" class="ui-modal">
        <form method="POST" action="{{ route('jurusan.store') }}?modal=create" class="ui-modal__panel">
            @csrf
            <input type="hidden" name="form_modal" value="create">
            <div class="ui-modal__header"><div><h2 class="text-lg font-semibold">Tambah Jurusan</h2><p class="text-sm" style="color:var(--ui-muted)">Masukkan informasi jurusan baru.</p></div><button type="button" class="ui-modal__close" onclick="this.closest('dialog').close()" aria-label="Tutup">×</button></div>
            <div class="ui-modal__body space-y-4">
                <div><label class="block text-sm font-medium mb-1" for="jurusan_nama_baru">Nama Jurusan</label><input id="jurusan_nama_baru" name="nama" value="{{ old('nama') }}" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                <div><label class="block text-sm font-medium mb-1" for="jurusan_kajur_baru">Ketua Jurusan</label><input id="jurusan_kajur_baru" name="kajur" value="{{ old('kajur') }}" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
            </div>
            <div class="ui-modal__footer"><button type="button" class="px-4 py-2 rounded-xl border" onclick="this.closest('dialog').close()">Batal</button><button type="submit" class="px-4 py-2 rounded-xl text-white" style="background:var(--ui-primary)">Simpan Jurusan</button></div>
        </form>
    </dialog>
    @foreach ($jurusan as $j)
        <dialog id="modalEditJurusan{{ $j->id }}" class="ui-modal">
            <form method="POST" action="{{ route('jurusan.update', $j) }}?modal=edit-{{ $j->id }}" class="ui-modal__panel">
                @csrf @method('PUT')
                <input type="hidden" name="form_modal" value="edit-{{ $j->id }}">
                <div class="ui-modal__header"><div><h2 class="text-lg font-semibold">Edit Jurusan</h2><p class="text-sm" style="color:var(--ui-muted)">{{ $j->nama }}</p></div><button type="button" class="ui-modal__close" onclick="this.closest('dialog').close()" aria-label="Tutup">×</button></div>
                <div class="ui-modal__body space-y-4">
                    <div><label class="block text-sm font-medium mb-1" for="jurusan_nama_{{ $j->id }}">Nama Jurusan</label><input id="jurusan_nama_{{ $j->id }}" name="nama" value="{{ old('nama', $j->nama) }}" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                    <div><label class="block text-sm font-medium mb-1" for="jurusan_kajur_{{ $j->id }}">Ketua Jurusan</label><input id="jurusan_kajur_{{ $j->id }}" name="kajur" value="{{ old('kajur', $j->kajur) }}" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                </div>
                <div class="ui-modal__footer"><button type="button" class="px-4 py-2 rounded-xl border" onclick="this.closest('dialog').close()">Batal</button><button type="submit" class="px-4 py-2 rounded-xl text-white" style="background:var(--ui-primary)">Simpan Perubahan</button></div>
            </form>
        </dialog>
    @endforeach
    <script>
    document.querySelectorAll('dialog.ui-modal').forEach(function(dialog) {
        dialog.addEventListener('click', function(event) { if (event.target === dialog) dialog.close(); });
    });
    @if ($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        const key = @json(request('modal') ?: old('form_modal'));
        if (key === 'create') document.getElementById('modalTambahJurusan')?.showModal();
        else if (key && key.startsWith('edit-')) document.getElementById('modalEditJurusan' + key.slice(5))?.showModal();
    });
    @endif
    </script>

@endsection
