@extends('layouts.app')

@section('title', 'Detail Kurikulum - Evaluasi PBM')
@section('page-title', $kurikulum->nama)
@section('page-desc', ($kurikulum->prodi->nama ?? '-') . ' — Berlaku sejak ' . $kurikulum->tahun_berlaku_mulai)

@section('content')

    {{-- Kembali --}}
    <div class="mb-5">
        <a
            href="{{ route('kurikulum.index') }}"
            class="text-sm hover:underline"
            style="color: var(--muted-foreground);"
        >
            ← Kembali ke Daftar Kurikulum
        </a>
    </div>

    {{-- Informasi Kurikulum --}}
    <div
        class="w-full rounded-2xl border p-6 mb-6"
        style="background: var(--card); border-color: var(--border);"
    >
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div>
                <p
                    class="text-xs uppercase tracking-wide"
                    style="color: var(--muted-foreground);"
                >
                    Nama Kurikulum
                </p>

                <p class="font-medium mt-1">
                    {{ $kurikulum->nama }}
                </p>
            </div>

            <div>
                <p
                    class="text-xs uppercase tracking-wide"
                    style="color: var(--muted-foreground);"
                >
                    Program Studi
                </p>

                <p class="font-medium mt-1">
                    {{ $kurikulum->prodi->nama ?? '-' }}
                </p>
            </div>

            <div>
                <p
                    class="text-xs uppercase tracking-wide"
                    style="color: var(--muted-foreground);"
                >
                    Berlaku Sejak
                </p>

                <p class="font-medium mt-1">
                    {{ $kurikulum->tahun_berlaku_mulai }}
                </p>
            </div>

        </div>
    </div>

    {{-- Laporan CPL --}}
    <div class="mb-6">
        <a
            href="{{ route('kurikulum.laporan-cpl', $kurikulum) }}"
            class="inline-flex items-center text-sm font-medium hover:underline"
            style="color: var(--primary);"
        >
            Lihat Laporan Capaian CPL Semua Mahasiswa →
        </a>
    </div>

    {{-- Daftar CPL --}}
    <div
        class="w-full rounded-2xl border overflow-hidden mb-6"
        style="background: var(--card); border-color: var(--border);"
    >
        <div
            class="px-6 py-5 border-b flex items-center justify-between gap-4"
            style="border-color: var(--border);"
        >
            <div>
                <h2 class="font-serif-display text-xl">
                    Daftar CPL
                </h2>

                <p
                    class="text-sm mt-1"
                    style="color: var(--muted-foreground);"
                >
                    Capaian pembelajaran lulusan pada kurikulum ini.
                </p>
            </div>

            <a
                href="{{ route('cpl.create', $kurikulum) }}"
                class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium text-white hover:opacity-90 shrink-0"
                style="background: var(--primary);"
            >
                + Tambah CPL
            </a>
        </div>

        <div class="p-2">

            @forelse ($kurikulum->cpl as $cpl)

                <div
                    class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 p-4 border-b last:border-b-0"
                    style="border-color: var(--border);"
                >
                    <div class="min-w-0">

                        <p class="font-medium text-sm">
                            {{ $cpl->kode }}

                            @if($cpl->domain)
                                <span
                                    class="text-xs"
                                    style="color: var(--muted-foreground);"
                                >
                                    ({{ $cpl->domain }})
                                </span>
                            @endif
                        </p>

                        <p
                            class="text-sm mt-1"
                            style="color: var(--muted-foreground);"
                        >
                            {{ $cpl->deskripsi }}
                        </p>

                    </div>

                    <div class="flex gap-4 shrink-0">

                        <a
                            href="{{ route('cpl.edit', $cpl) }}"
                            class="text-sm hover:underline"
                            style="color: var(--accent);"
                        >
                            Edit
                        </a>

                        <form
                            method="POST"
                            action="{{ route('cpl.destroy', $cpl) }}"
                            onsubmit="return confirm('Yakin hapus CPL ini?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="text-sm hover:underline"
                                style="color: #a13d3d;"
                            >
                                Hapus
                            </button>
                        </form>

                    </div>
                </div>

            @empty

                <p
                    class="p-6 text-center text-sm"
                    style="color: var(--muted-foreground);"
                >
                    Belum ada CPL.
                </p>

            @endforelse

        </div>
    </div>

    {{-- Daftar Mata Kuliah --}}
    <div
        class="w-full rounded-2xl border overflow-hidden"
        style="background: var(--card); border-color: var(--border);"
    >
        <div
            class="px-6 py-5 border-b"
            style="border-color: var(--border);"
        >
            <h2 class="font-serif-display text-xl">
                Daftar Mata Kuliah
            </h2>

            <p
                class="text-sm mt-1"
                style="color: var(--muted-foreground);"
            >
                Mata kuliah yang terdaftar pada kurikulum ini.
            </p>
        </div>

        <div class="p-2">

            @forelse ($kurikulum->mataKuliah as $mk)

                <a
                    href="{{ route('kurikulum.mata-kuliah.show', [$kurikulum, $mk]) }}"
                    class="block p-4 border-b last:border-b-0 text-sm hover:bg-[var(--secondary)] transition"
                    style="border-color: var(--border);"
                >
                    <div class="flex items-center justify-between gap-4">

                        <div>
                            <p class="font-medium">
                                {{ $mk->kode }} — {{ $mk->nama }}
                            </p>

                            <p
                                class="text-xs mt-1"
                                style="color: var(--muted-foreground);"
                            >
                                {{ $mk->sks }} SKS
                            </p>
                        </div>

                        <span
                            class="text-sm shrink-0"
                            style="color: var(--primary);"
                        >
                            Lihat Detail →
                        </span>

                    </div>
                </a>

            @empty

                <p
                    class="p-6 text-center text-sm"
                    style="color: var(--muted-foreground);"
                >
                    Belum ada mata kuliah.
                </p>

            @endforelse

        </div>
    </div>

@endsection