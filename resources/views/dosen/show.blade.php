@extends('layouts.app')

@section('title', 'Detail Dosen - Evaluasi PBM')
@section('page-title', 'Detail Dosen')
@section('page-desc', 'Informasi lengkap dosen pada Program Studi')

@section('content')

    <div class="mb-4 text-left">
        <a href="{{ route('kaprodi.dosen.index') }}"
           class="text-sm underline hover:opacity-70"
           style="color: var(--muted-foreground);">
            ← Kembali ke Daftar Dosen
        </a>
    </div>

    <div class="w-full rounded-2xl border overflow-hidden"
         style="background: var(--card); border-color: var(--border);">

        {{-- Header --}}
        <div class="px-6 py-5 border-b flex items-center justify-between"
             style="border-color: var(--border);">

            <div>
                <h2 class="font-serif-display text-2xl font-bold">
                    {{ $dosen->nama }}
                </h2>

                <p class="text-sm mt-1"
                   style="color: var(--muted-foreground);">
                    Detail informasi dosen
                </p>
            </div>

            <span class="px-3 py-1 rounded-full text-xs font-medium"
                  style="background: #f0fdf4; color: #166534;">
                Dosen
            </span>

        </div>


        {{-- Informasi Dosen --}}
        <div class="p-6">

            <h3 class="font-serif-display text-lg mb-4">
                Informasi Dosen
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                {{-- NIDN --}}
                <div class="rounded-xl border p-4"
                     style="border-color: var(--border);">

                    <p class="text-xs mb-2"
                       style="color: var(--muted-foreground);">
                        NIDN
                    </p>

                    <p class="text-sm font-semibold">
                        {{ $dosen->nidn ?? '-' }}
                    </p>

                </div>


                {{-- Nama Lengkap --}}
                <div class="rounded-xl border p-4"
                     style="border-color: var(--border);">

                    <p class="text-xs mb-2"
                       style="color: var(--muted-foreground);">
                        Nama Lengkap
                    </p>

                    <p class="text-sm font-semibold">
                        {{ $dosen->nama ?? '-' }}
                    </p>

                </div>


                {{-- Program Studi --}}
                <div class="rounded-xl border p-4"
                     style="border-color: var(--border);">

                    <p class="text-xs mb-2"
                       style="color: var(--muted-foreground);">
                        Program Studi
                    </p>

                    <p class="text-sm font-semibold">
                        {{ $dosen->prodi->nama ?? '-' }}
                    </p>

                </div>


                {{-- Jenis Kelamin --}}
                <div class="rounded-xl border p-4"
                     style="border-color: var(--border);">

                    <p class="text-xs mb-2"
                       style="color: var(--muted-foreground);">
                        Jenis Kelamin
                    </p>

                    <p class="text-sm font-semibold">
                        {{ $dosen->jenis_kelamin ?? '-' }}
                    </p>

                </div>


                {{-- Jabatan Fungsional --}}
                <div class="rounded-xl border p-4"
                     style="border-color: var(--border);">

                    <p class="text-xs mb-2"
                       style="color: var(--muted-foreground);">
                        Jabatan Fungsional
                    </p>

                    <p class="text-sm font-semibold">
                        {{ $dosen->jabatan_fungsional ?? '-' }}
                    </p>

                </div>


                {{-- Pendidikan Terakhir --}}
                <div class="rounded-xl border p-4"
                     style="border-color: var(--border);">

                    <p class="text-xs mb-2"
                       style="color: var(--muted-foreground);">
                        Pendidikan Terakhir
                    </p>

                    <p class="text-sm font-semibold">
                        {{ $dosen->pendidikan_terakhir ?? '-' }}
                    </p>

                </div>


                {{-- Institusi Lulusan --}}
                <div class="rounded-xl border p-4"
                     style="border-color: var(--border);">

                    <p class="text-xs mb-2"
                       style="color: var(--muted-foreground);">
                        Institusi Lulusan
                    </p>

                    <p class="text-sm font-semibold">
                        {{ $dosen->institusi_lulusan ?? '-' }}
                    </p>

                </div>


                {{-- Nomor HP --}}
                <div class="rounded-xl border p-4"
                     style="border-color: var(--border);">

                    <p class="text-xs mb-2"
                       style="color: var(--muted-foreground);">
                        Nomor HP
                    </p>

                    <p class="text-sm font-semibold">
                        {{ $dosen->nomor_hp ?? '-' }}
                    </p>

                </div>

            </div>

        </div>


        {{-- Footer --}}
        <div class="px-6 py-4 border-t"
             style="border-color: var(--border);">

            <a href="{{ route('kaprodi.dosen.index') }}"
               class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium"
               style="background: var(--secondary); color: var(--foreground);">
                Kembali
            </a>

        </div>

    </div>

@endsection