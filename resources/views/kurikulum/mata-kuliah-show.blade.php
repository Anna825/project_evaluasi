@extends('layouts.app')

@section('title', 'Detail Mata Kuliah - Evaluasi PBM')
@section('page-title', $mataKuliah->kode . ' — ' . $mataKuliah->nama)
@section('page-desc', $mataKuliah->sks . ' SKS, Semester ke-' . $mataKuliah->semester_ke)

@section('content')

    {{-- Kembali ke Kurikulum --}}
    <div class="mb-4 text-left">
        <a
            href="{{ route('kurikulum.show', $mataKuliah->kurikulum_id) }}"
            class="text-sm underline hover:opacity-70"
            style="color: var(--muted-foreground);"
        >
            ← Kembali ke Kurikulum
        </a>
    </div>

    {{-- Informasi Mata Kuliah --}}
    <div
        class="w-full rounded-2xl border overflow-hidden mb-6"
        style="background: var(--card); border-color: var(--border);"
    >
        <div
            class="px-6 py-4 border-b"
            style="border-color: var(--border);"
        >
            <h2 class="font-serif-display text-lg">
                Informasi Mata Kuliah
            </h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <p
                        class="text-xs mb-1"
                        style="color: var(--muted-foreground);"
                    >
                        Kode Mata Kuliah
                    </p>
                    <p class="font-medium">
                        {{ $mataKuliah->kode }}
                    </p>
                </div>

                <div>
                    <p
                        class="text-xs mb-1"
                        style="color: var(--muted-foreground);"
                    >
                        Nama Mata Kuliah
                    </p>
                    <p class="font-medium">
                        {{ $mataKuliah->nama }}
                    </p>
                </div>

                <div>
                    <p
                        class="text-xs mb-1"
                        style="color: var(--muted-foreground);"
                    >
                        SKS
                    </p>
                    <p class="font-medium">
                        {{ $mataKuliah->sks }}
                    </p>
                </div>

                <div>
                    <p
                        class="text-xs mb-1"
                        style="color: var(--muted-foreground);"
                    >
                        Semester
                    </p>
                    <p class="font-medium">
                        Semester ke-{{ $mataKuliah->semester_ke }}
                    </p>
                </div>

                <div>
                    <p
                        class="text-xs mb-1"
                        style="color: var(--muted-foreground);"
                    >
                        Jenis Mata Kuliah
                    </p>
                    <p class="font-medium capitalize">
                        {{ $mataKuliah->jenis }}
                    </p>
                </div>

            </div>
        </div>
    </div>

    {{-- CPMK --}}
    <div
        class="w-full rounded-2xl border overflow-hidden mb-6"
        style="background: var(--card); border-color: var(--border);"
    >
        <div
            class="px-6 py-4 border-b"
            style="border-color: var(--border);"
        >
            <h2 class="font-serif-display text-lg">
                CPMK
            </h2>
        </div>

        <div class="p-2">
            @forelse ($mataKuliah->cpmk as $cpmk)

                <div
                    class="p-4 border-b last:border-b-0"
                    style="border-color: var(--border);"
                >
                    <p class="font-medium text-sm">
                        {{ $cpmk->kode }}
                    </p>

                    <p
                        class="text-sm mt-1"
                        style="color: var(--muted-foreground);"
                    >
                        {{ $cpmk->deskripsi }}
                    </p>

                    @if ($cpmk->cpl->isNotEmpty())
                        <p
                            class="text-xs mt-2"
                            style="color: var(--muted-foreground);"
                        >
                            Dipetakan ke:
                            @foreach ($cpmk->cpl as $cpl)
                                {{ $cpl->kode }}
                                ({{ $cpl->pivot->bobot }}%)
                                @if (!$loop->last), @endif
                            @endforeach
                        </p>
                    @endif
                </div>

            @empty

                <p
                    class="p-6 text-center text-sm"
                    style="color: var(--muted-foreground);"
                >
                    Belum ada CPMK.
                </p>

            @endforelse
        </div>
    </div>

    {{-- RPS --}}
    <div
        class="w-full rounded-2xl border overflow-hidden"
        style="background: var(--card); border-color: var(--border);"
    >
        <div
            class="px-6 py-4 border-b"
            style="border-color: var(--border);"
        >
            <h2 class="font-serif-display text-lg">
                RPS
            </h2>
        </div>

        <div class="p-2">
            @forelse ($mataKuliah->rps as $rps)

                <div
                    class="p-4 border-b last:border-b-0"
                    style="border-color: var(--border);"
                >
                    <p class="font-medium text-sm">
                        Versi {{ $rps->versi }} — {{ $rps->tanggal_disusun }}
                    </p>

                    <p
                        class="text-sm mt-1"
                        style="color: var(--muted-foreground);"
                    >
                        {{ $rps->deskripsi_singkat ?? '-' }}
                    </p>
                </div>

            @empty

                <p
                    class="p-6 text-center text-sm"
                    style="color: var(--muted-foreground);"
                >
                    Belum ada RPS.
                </p>

            @endforelse
        </div>
    </div>

@endsection