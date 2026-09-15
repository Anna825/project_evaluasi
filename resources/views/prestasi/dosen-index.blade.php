@extends('layouts.app')

@section('title', 'Prestasi Saya - Evaluasi PBM')
@section('page-title', 'Prestasi Saya')
@section('page-desc', 'Catat dan lihat prestasi yang Anda raih.')

@section('content')

    <div class="flex justify-end mb-4">
        <a
            href="{{ route('prestasi-dosen.create') }}"
            class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-medium text-white hover:opacity-95 transition"
            style="background: var(--primary);"
        >
            + Tambah Prestasi
        </a>
    </div>

    <div
        class="rounded-2xl border overflow-hidden"
        style="background: var(--card); border-color: var(--border);"
    >
        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm">

                <thead style="background: var(--secondary);">
                    <tr>
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

                    @forelse ($prestasiList as $p)

                        <tr
                            class="border-t"
                            style="border-color: var(--border);"
                        >

                            <td class="p-3 font-medium">
                                {{ $p->nama_kegiatan }}
                            </td>

                            <td class="p-3">
                                {{ $p->tingkat ?? '-' }}
                            </td>

                            <td class="p-3">
                                {{ $p->peringkat ?? '-' }}
                            </td>

                            <td class="p-3">
                                {{ $p->tahunAkademik->label ?? '-' }}
                            </td>

                            <td class="p-3 text-center">

                                <a
                                    href="{{ route('prestasi-dosen.show', $p->id) }}"
                                    class="inline-flex items-center justify-center px-3 py-2 rounded-lg text-xs font-semibold"
                                    style="background: var(--secondary); color: var(--foreground);"
                                >
                                    Lihat Detail
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="5"
                                class="p-8 text-center"
                                style="color: var(--muted-foreground);"
                            >
                                Belum ada prestasi.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

@endsection