@extends('layouts.app')

@section('title', 'Data Mahasiswa - ' . $kelas->nama_kelas)

@section('content')
<div class="w-full py-5">

    {{-- Breadcrumb / Kembali --}}
    <div class="flex items-center justify-between mb-5">

        <div>
            <p class="text-sm" style="color: var(--muted-foreground);">
                Data Mahasiswa
                <span class="mx-2">›</span>
                {{ $kelas->nama_kelas }}
            </p>

            <!-- <h1 class="text-2xl font-semibold mt-1"
                style="color: var(--foreground);">
                {{ $kelas->nama_kelas }}
            </h1> -->

            <!-- <p class="text-sm mt-1"
               style="color: var(--muted-foreground);">
                Daftar mahasiswa pada kelas ini
            </p> -->
        </div>

        <a href="{{ route('mahasiswa.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border text-sm font-medium transition hover:opacity-80"
           style="border-color: var(--border);
                  background: var(--card);
                  color: var(--foreground);">
            ← Kembali
        </a>

    </div>


    {{-- Informasi Kelas --}}
    <div class="rounded-2xl border p-5 mb-5"
         style="background: var(--card);
                border-color: var(--border);">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

            {{-- Identitas Kelas --}}
            <div class="flex items-center gap-4">

                <div class="w-14 h-14 rounded-xl flex items-center justify-center text-2xl"
                     style="background: var(--secondary);">
                    📁
                </div>

                <div>
                    <p class="text-sm"
                       style="color: var(--muted-foreground);">
                        Kelas
                    </p>

                    <h2 class="text-xl font-semibold"
                        style="color: var(--foreground);">
                        {{ $kelas->nama_kelas }}
                    </h2>

                    <p class="text-sm mt-1"
                       style="color: var(--muted-foreground);">
                        {{ $kelas->prodi->nama ?? '-' }}
                    </p>
                </div>

            </div>


            {{-- Statistik --}}
            <div class="flex items-center gap-3">

                <div class="min-w-[150px] rounded-xl border px-5 py-3"
                     style="background: var(--secondary);
                            border-color: var(--border);">

                    <p class="text-xs"
                       style="color: var(--muted-foreground);">
                        Angkatan
                    </p>

                    <p class="text-lg font-semibold mt-1"
                       style="color: var(--foreground);">
                        {{ $kelas->angkatan }}
                    </p>

                </div>


                <div class="min-w-[170px] rounded-xl border px-5 py-3"
                     style="background: var(--secondary);
                            border-color: var(--border);">

                    <p class="text-xs"
                       style="color: var(--muted-foreground);">
                        Jumlah Mahasiswa
                    </p>

                    <p class="text-lg font-semibold mt-1"
                       style="color: var(--foreground);">
                        {{ $mahasiswa->total() }}
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- Daftar Mahasiswa --}}
    <div class="rounded-2xl border overflow-hidden"
         style="background: var(--card);
                border-color: var(--border);">

        {{-- Header tabel --}}
        <div class="px-5 py-4 border-b"
             style="border-color: var(--border);">

            <h2 class="text-lg font-semibold"
                style="color: var(--foreground);">
                Daftar Mahasiswa
            </h2>

            <p class="text-sm mt-1"
               style="color: var(--muted-foreground);">
                Mahasiswa yang terdaftar pada kelas
                <span class="font-medium">
                    {{ $kelas->nama_kelas }}
                </span>.
            </p>

        </div>


        @if($mahasiswa->count() > 0)

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead>
                        <tr style="background: var(--secondary);">

                            <th class="px-5 py-3 text-left font-medium"
                                style="color: var(--foreground);">
                                No
                            </th>

                            <th class="px-5 py-3 text-left font-medium"
                                style="color: var(--foreground);">
                                NIM
                            </th>

                            <th class="px-5 py-3 text-left font-medium"
                                style="color: var(--foreground);">
                                Nama Mahasiswa
                            </th>

                            <th class="px-5 py-3 text-left font-medium"
                                style="color: var(--foreground);">
                                Status
                            </th>

                            <th class="px-5 py-3 text-left font-medium"
                                style="color: var(--foreground);">
                                Aksi
                            </th>

                        </tr>
                    </thead>


                    <tbody>

                        @foreach($mahasiswa as $index => $mhs)

                            <tr class="border-t transition hover:opacity-80"
                                style="border-color: var(--border);">

                                <td class="px-5 py-3.5"
                                    style="color: var(--muted-foreground);">
                                    {{ $mahasiswa->firstItem() + $index }}
                                </td>


                                <td class="px-5 py-3.5 font-medium"
                                    style="color: var(--foreground);">
                                    {{ $mhs->nim }}
                                </td>


                                <td class="px-5 py-3.5"
                                    style="color: var(--foreground);">
                                    {{ $mhs->nama }}
                                </td>


                                <td class="px-5 py-3.5">

                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                          style="background: var(--secondary);
                                                 color: var(--foreground);">
                                        {{ ucfirst($mhs->status) }}
                                    </span>

                                </td>


                                <td class="px-5 py-3.5">

                                    <a href="{{ route('mahasiswa.show', $mhs) }}"
                                       class="inline-flex items-center text-sm font-medium underline hover:opacity-70"
                                       style="color: var(--foreground);">
                                        Detail →
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Footer / Pagination --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 py-4 border-t"
                 style="border-color: var(--border);">

                <p class="text-sm"
                   style="color: var(--muted-foreground);">

                    Menampilkan
                    <span class="font-medium"
                          style="color: var(--foreground);">
                        {{ $mahasiswa->firstItem() }}
                    </span>

                    -
                    <span class="font-medium"
                          style="color: var(--foreground);">
                        {{ $mahasiswa->lastItem() }}
                    </span>

                    dari
                    <span class="font-medium"
                          style="color: var(--foreground);">
                        {{ $mahasiswa->total() }}
                    </span>
                    mahasiswa

                </p>

                @if($mahasiswa->hasPages())
                    <div>
                        {{ $mahasiswa->links() }}
                    </div>
                @endif

            </div>

        @else

            <div class="px-6 py-12 text-center">

                <div class="text-4xl mb-3">
                    📁
                </div>

                <h3 class="font-medium"
                    style="color: var(--foreground);">
                    Belum ada mahasiswa
                </h3>

                <p class="text-sm mt-1"
                   style="color: var(--muted-foreground);">
                    Belum ada mahasiswa yang terdaftar pada kelas ini.
                </p>

            </div>

        @endif

    </div>

</div>
@endsection