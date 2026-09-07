@extends('layouts.app')

@section('title', 'Profil Saya - Evaluasi PBM')
@section('page-title', 'Profil Saya')
@section('page-desc', 'Informasi akun Anda.')

@section('content')
    <div class="rounded-2xl border p-6 max-w-xl" style="background: var(--card); border-color: var(--border);">
        <div class="space-y-4 text-sm">
            <div>
                <p style="color: var(--muted-foreground);">Nama</p>
                <p class="font-medium">{{ $user->name }}</p>
            </div>
            <div>
                <p style="color: var(--muted-foreground);">Email</p>
                <p class="font-medium">{{ $user->email }}</p>
            </div>
            <div>
                <p style="color: var(--muted-foreground);">Status Akun</p>
                <p class="font-medium">{{ ucfirst($user->status) }}</p>
            </div>
            @if ($user->dosen)
                <div>
                    <p style="color: var(--muted-foreground);">NIDN</p>
                    <p class="font-medium">{{ $user->dosen->nidn }}</p>
                </div>
                <div>
                    <p style="color: var(--muted-foreground);">Program Studi</p>
                    <p class="font-medium">{{ $user->dosen->prodi->nama ?? '-' }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection