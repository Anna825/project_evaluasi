@extends('layouts.app')

@section('title', 'Prestasi Dosen - Admin')
@section('page-title', 'Prestasi Dosen')
@section('page-desc', 'Rekap seluruh prestasi dosen.')

@section('content')

    <div class="w-full">

        @if (session('status'))
            <div class="mb-5 px-4 py-3 rounded-xl text-sm"
                 style="background: #eaf7ee; color: #287a42;">
                {{ session('status') }}
            </div>
        @endif

        <div class="rounded-2xl border overflow-hidden"
             style="background: var(--card); border-color: var(--border);">

            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <thead style="background: var(--secondary);">
                        <tr>

                            <th class="p-3 font-medium">
                                Dosen
                            </th>

                            <th class="p-3 font-medium">
                                Nama Kegiatan
                            </th>

                            <th class="p-3 font-medium">
                                Tingkat
                            </th>

                            <th class="p-3 font-medium">
                                Peringkat / Capaian
                            </th>

                            <th class="p-3 font-medium">
                                Tahun
                            </th>

                            <th class="p-3 font-medium text-center">
                                Aksi
                            </th>

                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($prestasiList as $prestasi)

                            @foreach ($prestasi->dosen as $dosen)

                                <tr class="border-t"
                                    style="border-color: var(--border);">

                                    {{-- Dosen --}}
                                    <td class="p-3 font-medium">
                                        {{ $dosen->nama }}
                                    </td>

                                    {{-- Nama Kegiatan --}}
                                    <td class="p-3">
                                        {{ $prestasi->nama_kegiatan }}
                                    </td>

                                    {{-- Tingkat --}}
                                    <td class="p-3">
                                        {{ $prestasi->tingkat ?? '-' }}
                                    </td>

                                    {{-- Peringkat --}}
                                    <td class="p-3">
                                        {{ $prestasi->peringkat ?? '-' }}
                                    </td>

                                    {{-- Tahun --}}
                                    <td class="p-3">
                                        {{ $prestasi->tahunAkademik->label ?? '-' }}
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="p-3 text-center">

                                        <a
                                            href="{{ route('admin.prestasi.dosen.show', $prestasi->id) }}"
                                            class="inline-flex items-center justify-center px-3 py-2 rounded-lg text-xs font-semibold"
                                            style="background: var(--secondary); color: var(--foreground);"
                                        >
                                            Lihat Detail
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="p-8 text-center"
                                    style="color: var(--muted-foreground);"
                                >
                                    Belum ada prestasi dosen yang tercatat.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection