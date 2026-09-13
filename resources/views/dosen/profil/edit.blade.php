@extends('layouts.app')

@section('page-title', 'Edit Profil Dosen')
@section('page-desc', 'Perbarui informasi profesional dan akademik Anda.')

@section('content')

    <div class="mb-5">
        <a href="{{ route('dosen.profil.show') }}"
           class="inline-flex items-center gap-2 text-sm font-medium transition hover:opacity-70"
           style="color: var(--muted-foreground);">
            ← Kembali ke Profil Dosen
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-xl border px-4 py-3"
             style="border-color: var(--border); background: var(--secondary);">
            <p class="text-sm font-medium mb-2">
                Periksa kembali data yang dimasukkan:
            </p>

            <ul class="text-sm list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('dosen.profil.update') }}"
          method="POST"
          class="rounded-2xl border p-6"
          style="background: var(--card); border-color: var(--border);">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <label class="block text-sm font-medium mb-2">
                    NIDN
                </label>

                <input type="text"
                       value="{{ $dosen->nidn }}"
                       disabled
                       class="w-full rounded-xl border px-4 py-3 text-sm"
                       style="background: var(--secondary); border-color: var(--border); color: var(--muted-foreground);">

                <p class="text-xs mt-1"
                   style="color: var(--muted-foreground);">
                    NIDN tidak dapat diubah melalui profil.
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">
                    Nama Lengkap
                </label>

                <input type="text"
                       value="{{ $dosen->nama }}"
                       disabled
                       class="w-full rounded-xl border px-4 py-3 text-sm"
                       style="background: var(--secondary); border-color: var(--border); color: var(--muted-foreground);">

                <p class="text-xs mt-1"
                   style="color: var(--muted-foreground);">
                    Nama mengikuti data akun Dosen.
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">
                    Program Studi
                </label>

                <input type="text"
                       value="{{ $dosen->prodi?->nama }}"
                       disabled
                       class="w-full rounded-xl border px-4 py-3 text-sm"
                       style="background: var(--secondary); border-color: var(--border); color: var(--muted-foreground);">
            </div>

            <div>
                <label for="jabatan_fungsional"
                       class="block text-sm font-medium mb-2">
                    Jabatan Fungsional
                </label>

                <input type="text"
                       id="jabatan_fungsional"
                       name="jabatan_fungsional"
                       value="{{ old('jabatan_fungsional', $dosen->jabatan_fungsional) }}"
                       placeholder="Contoh: Asisten Ahli"
                       class="w-full rounded-xl border px-4 py-3 text-sm"
                       style="background: var(--card); border-color: var(--border);">

                <p class="text-xs mt-1"
                   style="color: var(--muted-foreground);">
                    Perubahan jabatan akan dicatat sebagai riwayat.
                </p>
            </div>

            <div>
                <label for="pendidikan_terakhir"
                       class="block text-sm font-medium mb-2">
                    Pendidikan Terakhir
                </label>

                <input type="text"
                       id="pendidikan_terakhir"
                       name="pendidikan_terakhir"
                       value="{{ old('pendidikan_terakhir', $dosen->pendidikan_terakhir) }}"
                       placeholder="Contoh: S2"
                       class="w-full rounded-xl border px-4 py-3 text-sm"
                       style="background: var(--card); border-color: var(--border);">
            </div>

            <div>
                <label for="institusi_lulusan"
                       class="block text-sm font-medium mb-2">
                    Institusi Lulusan
                </label>

                <input type="text"
                       id="institusi_lulusan"
                       name="institusi_lulusan"
                       value="{{ old('institusi_lulusan', $dosen->institusi_lulusan) }}"
                       placeholder="Contoh: Institut Teknologi Bandung"
                       class="w-full rounded-xl border px-4 py-3 text-sm"
                       style="background: var(--card); border-color: var(--border);">
            </div>

            <div class="md:col-span-2">
                <label for="no_hp"
                       class="block text-sm font-medium mb-2">
                    Nomor HP
                </label>

                <input type="text"
                       id="no_hp"
                       name="no_hp"
                       value="{{ old('no_hp', $dosen->no_hp) }}"
                       placeholder="Contoh: 081234567890"
                       class="w-full rounded-xl border px-4 py-3 text-sm"
                       style="background: var(--card); border-color: var(--border);">
            </div>

        </div>

        <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t"
             style="border-color: var(--border);">

            <a href="{{ route('dosen.profil.show') }}"
               class="rounded-xl px-4 py-2 text-sm font-medium border transition hover:opacity-80"
               style="border-color: var(--border);">
                Batal
            </a>

            <button type="submit"
                    class="rounded-xl px-4 py-2 text-sm font-medium transition hover:opacity-90"
                    style="background: var(--primary); color: white;">
                Simpan Perubahan
            </button>

        </div>

    </form>

@endsection