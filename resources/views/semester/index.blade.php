@extends('layouts.app')

@section('title', 'Semester - Evaluasi PBM')

@section('page-title', 'Semester')

@section('page-desc', 'Kelola semester akademik yang digunakan dalam proses pembelajaran.')

@section('content')

<div class="w-full">

    {{-- Pesan sukses --}}
    @if (session('status'))
        <div
            class="mb-5 rounded-xl border px-4 py-3 text-sm"
            style="background: rgba(46, 125, 50, 0.08); border-color: rgba(46, 125, 50, 0.25); color: var(--foreground);"
        >
            {{ session('status') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-lg font-semibold">
                Daftar Semester
            </h2>

            <p
                class="text-sm mt-1"
                style="color: var(--muted-foreground);"
            >
                Semester yang tersedia berdasarkan tahun akademik.
            </p>
        </div>

        <a
            href="{{ route('semester.create') }}"
            class="inline-flex items-center gap-2 rounded-xl px-5 py-3 text-sm font-medium text-white transition hover:opacity-90"
            style="background: var(--primary);"
        >
            + Tambah Semester
        </a>
    </div>


    {{-- Tabel Semester --}}
    <div
        class="rounded-2xl border overflow-hidden"
        style="background: var(--card); border-color: var(--border);"
    >

        @if ($semesterList->count())

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead
                        style="background: var(--secondary);"
                    >
                        <tr>
                            <th class="px-5 py-4 text-left font-semibold">
                                No
                            </th>

                            <th class="px-5 py-4 text-left font-semibold">
                                Tahun Akademik
                            </th>

                            <th class="px-5 py-4 text-left font-semibold">
                                Jenis Semester
                            </th>

                            <th class="px-5 py-4 text-left font-semibold">
                                Tanggal Mulai
                            </th>

                            <th class="px-5 py-4 text-left font-semibold">
                                Tanggal Selesai
                            </th>

                            <th class="px-5 py-4 text-right font-semibold">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($semesterList as $index => $semester)

                            <tr
                                class="border-t"
                                style="border-color: var(--border);"
                            >

                                <td class="px-5 py-4">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-5 py-4 font-medium">
                                    {{ $semester->tahunAkademik->label ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ ucfirst($semester->jenis) }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ \Carbon\Carbon::parse($semester->tanggal_mulai)->format('d/m/Y') }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ \Carbon\Carbon::parse($semester->tanggal_selesai)->format('d/m/Y') }}
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end items-center gap-2">

                                        <a
                                            href="{{ route('semester.edit', $semester) }}"
                                            class="rounded-lg border px-3 py-2 text-xs font-medium transition hover:opacity-70"
                                            style="border-color: var(--border);"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('semester.destroy', $semester) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus semester ini?');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="rounded-lg border px-3 py-2 text-xs font-medium transition hover:opacity-70"
                                                style="border-color: #ef4444; color: #ef4444;"
                                            >
                                                Hapus
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            {{-- Empty state --}}
            <div class="px-6 py-14 text-center">

                <div
                    class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl"
                    style="background: var(--secondary);"
                >
                    <span class="text-2xl">📅</span>
                </div>

                <h3 class="text-lg font-semibold mb-2">
                    Belum Ada Semester
                </h3>

                <p
                    class="text-sm mb-6"
                    style="color: var(--muted-foreground);"
                >
                    Belum ada semester akademik yang dibuat.
                    Silakan tambahkan semester terlebih dahulu.
                </p>

                <a
                    href="{{ route('semester.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl px-5 py-3 text-sm font-medium text-white transition hover:opacity-90"
                    style="background: var(--primary);"
                >
                    + Tambah Semester
                </a>

            </div>

        @endif

    </div>

</div>

@endsection