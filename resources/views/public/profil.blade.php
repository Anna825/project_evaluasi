@extends('layouts.mahasiswa')

@section('title', 'Profil Saya - Evaluasi PBM')
@section('page-title', 'Profil Saya')
@section('page-desc', 'Informasi data pribadi dan akademik Anda.')

@section('content')

    <div class="mb-4">
        <a
            href="{{ route('public.mahasiswa.menu', $mahasiswa->nim) }}"
            class="text-sm hover:underline"
            style="color: var(--muted-foreground);"
        >
            ← Kembali ke Dashboard
        </a>
    </div>

    <div class="w-full rounded-2xl border overflow-hidden"
         style="background: var(--card); border-color: var(--border);">

        {{-- Header Profil --}}
        <div
            class="px-6 py-5 border-b"
            style="border-color: var(--border); background: var(--secondary);"
        >
            <h2 class="font-serif-display text-xl">
                Data Mahasiswa
            </h2>

            <p
                class="text-sm mt-1"
                style="color: var(--muted-foreground);"
            >
                Informasi identitas dan status akademik Anda.
            </p>
        </div>

        {{-- Informasi Profil --}}
        <div class="p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <p
                        class="text-xs uppercase tracking-wide"
                        style="color: var(--muted-foreground);"
                    >
                        Nama Lengkap
                    </p>

                    <p class="font-medium mt-1">
                        {{ $mahasiswa->nama }}
                    </p>
                </div>

                <div>
                    <p
                        class="text-xs uppercase tracking-wide"
                        style="color: var(--muted-foreground);"
                    >
                        NIM
                    </p>

                    <p class="font-medium mt-1 mono">
                        {{ $mahasiswa->nim }}
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
                        {{ $mahasiswa->prodi->nama ?? '-' }}
                    </p>
                </div>

                <div>
                    <p
                        class="text-xs uppercase tracking-wide"
                        style="color: var(--muted-foreground);"
                    >
                        Angkatan
                    </p>

                    <p class="font-medium mt-1">
                        {{ $mahasiswa->angkatan ?? '-' }}
                    </p>
                </div>

                <div>
                    <p
                        class="text-xs uppercase tracking-wide"
                        style="color: var(--muted-foreground);"
                    >
                        Status Akademik
                    </p>

                    <div class="mt-1">
                        <span
                            class="inline-flex px-3 py-1 rounded-full text-xs font-medium"
                            style="background: var(--secondary);"
                        >
                            {{ ucfirst($mahasiswa->status) }}
                        </span>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Catatan --}}
    <div
        class="mt-4 rounded-2xl border p-5"
        style="background: var(--card); border-color: var(--border);"
    >
        <p
            class="text-sm"
            style="color: var(--muted-foreground);"
        >
            Data profil mahasiswa dikelola oleh Admin/Kaprodi.
            Jika terdapat kesalahan pada data, silakan hubungi
            program studi untuk melakukan pembaruan.
        </p>
    </div>

@endsection