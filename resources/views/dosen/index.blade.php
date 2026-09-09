@extends('layouts.app')

@section('title', 'Daftar Dosen - Evaluasi PBM')
@section('page-title', 'Daftar Dosen')
@section('page-desc', 'Dosen pada Program Studi yang Anda kelola')

@section('content')

    <div class="mb-4 text-left">
        <a href="{{ route('kaprodi.dashboard') }}"
           class="text-sm underline hover:opacity-70"
           style="color: var(--muted-foreground);">
            ← Kembali ke Dashboard
        </a>
    </div>

    <div class="w-full rounded-2xl border overflow-hidden"
         style="background: var(--card); border-color: var(--border);">

        <div class="px-6 py-4 border-b"
             style="border-color: var(--border);">

            <h2 class="font-serif-display text-lg">
                Daftar Dosen
            </h2>

            <p class="text-sm mt-1"
               style="color: var(--muted-foreground);">
                Menampilkan dosen yang terdaftar pada Program Studi Anda.
            </p>

        </div>

        <div class="p-4">

            @forelse ($dosen as $item)

                <a href="{{ route('kaprodi.dosen.show', $item) }}"
                   class="flex items-center justify-between gap-4 px-4 py-4 mb-2 rounded-xl border transition hover:opacity-80"
                   style="background: var(--secondary); border-color: var(--border);">

                    <div>
                        <p class="text-sm font-medium">
                            {{ $item->nama }}
                        </p>

                        <p class="text-xs mt-1"
                           style="color: var(--muted-foreground);">
                            NIDN: {{ $item->nidn ?? '-' }}
                        </p>
                    </div>

                    <span class="text-sm font-medium shrink-0"
                          style="color: var(--primary);">
                        Lihat Detail →
                    </span>

                </a>

            @empty

                <div class="text-center py-10">

                    <p class="text-sm"
                       style="color: var(--muted-foreground);">
                        Belum ada dosen pada Program Studi ini.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

@endsection