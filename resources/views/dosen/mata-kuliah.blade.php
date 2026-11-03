@extends('layouts.app')

@section('title', 'Mata Kuliah Saya - Evaluasi PBM')
@section('page-title', 'Mata Kuliah Saya')
@section('page-desc', 'Mata kuliah yang Anda ampu.')
@section('content')

<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold">
                Daftar Mata Kuliah
            </h2>
            <p class="text-sm mt-1" style="color: var(--muted-foreground);">
                Kelola CPMK dan RPS untuk mata kuliah yang tersedia.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('mata-kuliah.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium text-white"
               style="background: var(--primary);">
                <span class="text-lg leading-none">+</span>
                Tambah Mata Kuliah
            </a>

            <a href="{{ route('cpmk.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium border"
            style="border-color: var(--border); color: var(--primary); background: var(--card);">
            <span class="text-lg leading-none">+</span>
            Tambah CPMK
        </a>
    </div>

    {{-- Pesan sukses --}}
    @if (session('status'))
        <div
            class="rounded-xl border p-4 text-sm"
            style="
                background: rgba(34, 197, 94, 0.08);
                border-color: rgba(34, 197, 94, 0.25);
                color: #16a34a;
            "
        >
            {{ session('status') }}
        </div>
    @endif

    {{-- Pesan error --}}
    @if (session('error'))
        <div
            class="rounded-xl border p-4 text-sm"
            style="
                background: rgba(239, 68, 68, 0.08);
                border-color: rgba(239, 68, 68, 0.25);
                color: #dc2626;
            "
        >
            {{ session('error') }}
        </div>
    @endif

    {{-- Tabel Mata Kuliah --}}
    <div
        class="rounded-2xl border overflow-hidden"
        style="
            background: var(--card);
            border-color: var(--border);
        "
    >
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead style="background: var(--secondary);">
                    <tr>
                        <th class="p-3 font-medium">
                            Kode
                        </th>
                        <th class="p-3 font-medium">
                            Nama Mata Kuliah
                        </th>
                        <th class="p-3 font-medium">
                            SKS
                        </th>
                        <th class="p-3 font-medium">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($mataKuliahList as $mk)
                        <tr
                            class="border-t"
                            style="border-color: var(--border);"
                        >
                            <td class="p-3">
                                {{ $mk->kode }}
                            </td>
                            <td class="p-3 font-medium">
                                {{ $mk->nama }}
                            </td>
                            <td class="p-3">
                                {{ $mk->sks }}
                            </td>
                            <td class="p-3">
                                <a
                                    href="{{ route('mata-kuliah.show', $mk) }}"
                                    class="hover:underline"
                                    style="color: var(--primary);"
                                >
                                    Kelola CPMK &amp; RPS
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-10 text-center">
                                <div class="space-y-2">
                                    <p class="font-medium">
                                        Belum ada mata kuliah yang ditugaskan kepada Anda.
                                    </p>
                                    <p class="text-sm" style="color: var(--muted-foreground);">
                                        Hubungi administrator atau Kaprodi untuk memastikan
                                        penugasan kelas dan mata kuliah Anda sudah tersedia.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection