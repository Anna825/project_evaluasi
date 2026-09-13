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

            <a href="{{ route('mahasiswa.create') }}"
                class="btn-primary px-4 py-2.5 rounded-lg text-sm font-medium text-white"
                style="background: var(--primary);">
                + Tambah Mahasiswa
            </a>
        </div>
    </div>

    {{-- Daftar Folder Kelas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

        @forelse ($kelasMahasiswa as $kelas)

            <a href="{{ route('mahasiswa.kelas.show', $kelas) }}"
               class="group block rounded-2xl border p-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-md"
               style="background: var(--card); border-color: var(--border);">

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
                        {{ $kelas->nama_kelas }} ({{ $kelas->angkatan }})
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
                                Angkatan
                            </p>

                            <p class="text-sm font-semibold mt-0.5">
                                {{ $kelas->angkatan }}
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