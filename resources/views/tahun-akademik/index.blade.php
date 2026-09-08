@extends('layouts.app')

@section('title', 'Tambah Tahun Akademik - Evaluasi PBM')
@section('page-title', 'Tambah Tahun Akademik')
@section('page-desc', 'Tambahkan tahun akademik baru dan lihat daftar tahun akademik yang telah terdaftar.')

@section('content')

    {{-- Alert Error Validasi --}}
    @if ($errors->any())
        <div class="flex items-start gap-3 rounded-2xl p-4 mb-6 border text-sm"
             style="background: #fef2f2; border-color: #fecaca; color: #991b1b;">

            <svg class="w-5 h-5 shrink-0 mt-0.5 text-red-600"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>

            <div>
                <p class="font-semibold mb-1">Terjadi Kesalahan:</p>

                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Notifikasi Berhasil --}}
    @if (session('status'))
        <div class="flex items-start gap-3 rounded-2xl p-4 mb-6 border text-sm"
             style="background: #f0fdf4; border-color: #bbf7d0; color: #166534;">

            <svg class="w-5 h-5 shrink-0 mt-0.5 text-emerald-600"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>

            <div>
                <p class="font-semibold">Berhasil!</p>
                <p>{{ session('status') }}</p>
            </div>
        </div>
    @endif

    {{-- Form Tambah Tahun Akademik --}}
    <div class="w-full mb-8">
        <div class="rounded-2xl border p-6 sm:p-8"
             style="background: var(--card); border-color: var(--border);">

            <div class="flex items-start gap-4 mb-8">

                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0"
                     style="background: var(--secondary); color: var(--primary);">

                    <svg width="26"
                         height="26"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8 7V3m8 4V3m-9 8h10M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                    </svg>

                </div>

                <div>
                    <h2 class="font-serif-display text-lg font-bold">
                        Informasi Tahun Akademik
                    </h2>

                    <p class="text-sm mt-1"
                       style="color: var(--muted-foreground);">
                        Masukkan label tahun akademik yang ingin ditambahkan ke dalam sistem.
                    </p>
                </div>

            </div>

            <form method="POST"
                  action="{{ route('tahun-akademik.store') }}"
                  class="space-y-6">

                @csrf

                <div>
                    <label class="block text-sm font-medium mb-2">
                        Label Tahun Akademik
                    </label>

                    <input
                        type="text"
                        name="label"
                        value="{{ old('label') }}"
                        placeholder="Contoh: 2026/2027"
                        class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none"
                        style="border-color: var(--border);"
                        required
                    >

                    <p class="text-xs mt-2"
                       style="color: var(--muted-foreground);">
                        Gunakan format tahun akademik, misalnya <strong>2026/2027</strong>.
                    </p>
                </div>

                <div class="flex items-center gap-3 pt-2">

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-medium text-white transition hover:opacity-90 shadow-sm"
                        style="background: var(--primary);"
                    >
                        <svg
                            width="18"
                            height="18"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 4v16m8-8H4"
                            />
                        </svg>

                        Simpan Tahun Akademik
                    </button>

                    <a
                        href="{{ route('tahun-akademik.index') }}"
                        class="px-5 py-3 rounded-xl text-sm font-medium border transition hover:bg-gray-50"
                        style="border-color: var(--border); color: var(--muted-foreground);"
                    >
                        Batal
                    </a>

                </div>

            </form>
        </div>
    </div>

    {{-- Daftar Tahun Akademik --}}
    <div class="w-full">
        <div class="rounded-2xl border overflow-hidden"
             style="background: var(--card); border-color: var(--border);">

            <div class="p-6 border-b"
                 style="border-color: var(--border);">

                <h2 class="font-serif-display text-lg font-bold">
                    Daftar Tahun Akademik
                </h2>

                <p class="text-sm mt-1"
                   style="color: var(--muted-foreground);">
                    Tahun akademik yang telah ditambahkan ke dalam sistem.
                </p>

            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                <!-- <table class="w-full max-w-2xl text-left text-sm"> -->
                    <colgroup>
                        <col style="width: 80px;">
                        <col style="width: 280px;">
                        <col style="width: 140px;">
                    </colgroup>
                    <!-- <colgroup>
                        <col style="width: 80px;">
                        <col>
                        <col style="width: 140px;">
                    </colgroup> -->

                    <thead style="background: var(--secondary);">
                        <tr>
                            <th class="px-5 py-4 font-semibold">
                                No.
                            </th>

                            <th class="px-5 py-4 font-semibold">
                                Tahun Akademik
                            </th>

                            <th class="px-5 py-4 font-semibold text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y"
                        style="border-color: var(--border);">

                        @forelse ($tahunAkademik as $tahun)

                            <tr class="transition hover:bg-gray-50/50">

                                {{-- Nomor --}}
                                <td class="px-5 py-4"
                                    style="color: var(--muted-foreground);">
                                    {{ $loop->iteration }}
                                </td>

                                {{-- Tahun Akademik --}}
                                <td class="px-5 py-4">
                                    <span class="font-medium">
                                        {{ $tahun->label }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center">

                                        <form
                                            method="POST"
                                            action="{{ route('tahun-akademik.destroy', $tahun) }}"
                                            onsubmit="return confirm('Yakin ingin menghapus tahun akademik {{ $tahun->label }}?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-medium transition hover:opacity-80"
                                                style="background: #fee2e2; color: #b91c1c;"
                                            >
                                                <svg
                                                    width="15"
                                                    height="15"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-3a1 1 0 00-1 1v3M4 7h16"
                                                    />
                                                </svg>

                                                Hapus
                                            </button>

                                        </form>

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="3"
                                    class="px-5 py-10 text-center"
                                    style="color: var(--muted-foreground);"
                                >
                                    Belum ada tahun akademik yang ditambahkan.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>
        </div>
    </div>

@endsection