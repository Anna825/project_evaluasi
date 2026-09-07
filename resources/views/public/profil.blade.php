@extends('layouts.mahasiswa')

@section('title', 'Profil Saya - Evaluasi PBM')
@section('page-title', 'Profil Saya')
@section('page-desc', 'Informasi data pribadi Anda.')

@section('content')
    <div class="rounded-2xl border p-6 max-w-xl" style="background: var(--card); border-color: var(--border);">
        <div class="space-y-4 text-sm">
            <div>
                <p style="color: var(--muted-foreground);">Nama Lengkap</p>
                <p class="font-medium">{{ $mahasiswa->nama }}</p>
            </div>
            <div>
                <p style="color: var(--muted-foreground);">NIM</p>
                <p class="font-medium mono">{{ $mahasiswa->nim }}</p>
            </div>
            <div>
                <p style="color: var(--muted-foreground);">Program Studi</p>
                <p class="font-medium">{{ $mahasiswa->prodi->nama ?? '-' }}</p>
            </div>
            <div>
                <p style="color: var(--muted-foreground);">Angkatan</p>
                <p class="font-medium">{{ $mahasiswa->angkatan }}</p>
            </div>
            <div>
                <p style="color: var(--muted-foreground);">Status</p>
                <p class="font-medium">{{ ucfirst($mahasiswa->status) }}</p>
            </div>
        </div>
        <p class="text-xs mt-6" style="color: var(--muted-foreground);">Data ini dikelola oleh Admin/Kaprodi. Hubungi program studi jika ada kesalahan data.</p>
    </div>
@endsection