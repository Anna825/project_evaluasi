@extends('layouts.app')

@section('page-title', 'Profil Dosen')
@section('page-desc', 'Informasi profesional dan akademik dosen.')

@section('content')

    @if (session('status'))
        <div class="mb-5 rounded-xl border px-4 py-3 text-sm"
             style="background: var(--secondary); border-color: var(--border);">
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-5">
        <a href="{{ route('dosen.dashboard') }}"
           class="inline-flex items-center gap-2 text-sm font-medium transition hover:opacity-70"
           style="color: var(--muted-foreground);">
            ← Kembali ke Dashboard
        </a>
    </div>

    <div class="rounded-2xl border p-6"
         style="background: var(--card); border-color: var(--border);">

        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h2 class="text-lg font-semibold">
                    Informasi Dosen
                </h2>

                <p class="text-sm mt-1"
                   style="color: var(--muted-foreground);">
                    Data profesional yang digunakan dalam proses akademik dan evaluasi.
                </p>
            </div>

            <a href="{{ route('dosen.profil.edit') }}"
               class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-medium transition hover:opacity-90"
               style="background: var(--primary); color: white;">
                Edit Profil
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div class="rounded-xl border p-4"
                 style="border-color: var(--border); background: var(--secondary);">
                <p class="text-xs mb-1" style="color: var(--muted-foreground);">
                    NIDN
                </p>
                <p class="font-medium">
                    {{ $dosen->nidn ?: '-' }}
                </p>
            </div>

            <div class="rounded-xl border p-4"
                 style="border-color: var(--border); background: var(--secondary);">
                <p class="text-xs mb-1" style="color: var(--muted-foreground);">
                    Nama Lengkap
                </p>
                <p class="font-medium">
                    {{ $dosen->nama ?: '-' }}
                </p>
            </div>

            <div class="rounded-xl border p-4"
                 style="border-color: var(--border); background: var(--secondary);">
                <p class="text-xs mb-1" style="color: var(--muted-foreground);">
                    Program Studi
                </p>
                <p class="font-medium">
                    {{ $dosen->prodi?->nama ?: '-' }}
                </p>
            </div>

            <div class="rounded-xl border p-4"
                 style="border-color: var(--border); background: var(--secondary);">
                <p class="text-xs mb-1" style="color: var(--muted-foreground);">
                    Jabatan Fungsional
                </p>
                <p class="font-medium">
                    {{ $dosen->jabatan_fungsional ?: '-' }}
                </p>
            </div>

            <div class="rounded-xl border p-4"
                 style="border-color: var(--border); background: var(--secondary);">
                <p class="text-xs mb-1" style="color: var(--muted-foreground);">
                    Pendidikan Terakhir
                </p>
                <p class="font-medium">
                    {{ $dosen->pendidikan_terakhir ?: '-' }}
                </p>
            </div>

            <div class="rounded-xl border p-4"
                 style="border-color: var(--border); background: var(--secondary);">
                <p class="text-xs mb-1" style="color: var(--muted-foreground);">
                    Institusi Lulusan
                </p>
                <p class="font-medium">
                    {{ $dosen->institusi_lulusan ?: '-' }}
                </p>
            </div>

            <div class="rounded-xl border p-4 md:col-span-2"
                 style="border-color: var(--border); background: var(--secondary);">
                <p class="text-xs mb-1" style="color: var(--muted-foreground);">
                    Nomor HP
                </p>
                <p class="font-medium">
                    {{ $dosen->no_hp ?: '-' }}
                </p>
            </div>

        </div>
    </div>

@endsection