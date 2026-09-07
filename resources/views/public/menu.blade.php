@extends('layouts.guest')

@section('title', 'Menu Mahasiswa - Evaluasi PBM')

@section('content')
    <h1 class="font-serif-display text-xl mb-1">Halo, {{ $mahasiswa->nama }}</h1>
    <p class="text-sm mb-6" style="color: var(--muted-foreground);">NIM {{ $mahasiswa->nim }} — Status {{ ucfirst($mahasiswa->status) }}</p>

    @if (session('status'))
        <div class="mb-4 px-4 py-3 rounded-xl text-sm" style="background: #e3f2e6; color: #2e7d32;">{{ session('status') }}</div>
    @endif

    <div class="space-y-3">
        <a href="{{ route('public.prestasi.create', $mahasiswa->nim) }}" class="block text-center py-2.5 rounded-xl text-sm font-medium text-white" style="background: var(--primary);">
            Isi Data Prestasi
        </a>

        @if ($mahasiswa->status === 'lulus')
            <a href="{{ route('public.tracer.create', $mahasiswa->nim) }}" class="block text-center py-2.5 rounded-xl text-sm font-medium text-white" style="background: #2e7d32;">
                Isi Tracer Study
            </a>
        @else
            <p class="text-xs text-center" style="color: var(--muted-foreground);">Tracer Study hanya untuk alumni yang sudah lulus.</p>
        @endif
    </div>

    <p class="text-center text-sm mt-6">
        <a href="{{ route('public.cek-nim') }}" class="hover:underline" style="color: var(--primary);">Ganti NIM</a>
    </p>
@endsection