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
                Mata kuliah yang sedang Anda ampu.
            </p>
        </div>
        {{-- Tombol Tambah Mata Kuliah --}}
        <a href="{{ route('mata-kuliah.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium text-white" style="background: var(--primary);">
            <span class="text-lg leading-none">+</span>
            Tambah Mata Kuliah
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
                            <td
                                colspan="4"
                                class="p-10 text-center"
                            >
                                <div class="space-y-2">
                                    <p class="font-medium">
                                        Anda belum mengampu mata kuliah apa pun.
                                    </p>
                                    <p
                                        class="text-sm"
                                        style="color: var(--muted-foreground);"
                                    >
                                        Silakan tambahkan mata kuliah yang Anda ampu.
                                    </p>

                                    <div class="pt-2">
                                        <a
                                            href="{{ route('mata-kuliah.create') }}"
                                            class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium text-white"
                                            style="background: var(--primary);"
                                        >
                                            <span class="text-lg leading-none">+</span>
                                            Tambah Mata Kuliah
                                        </a>
                                    </div>
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