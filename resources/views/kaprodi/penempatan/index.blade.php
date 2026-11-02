
@extends('layouts.app')

@section('content')
<div class="w-full space-y-6">

    {{-- Header halaman --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">
            Penempatan Mahasiswa
        </h1>
        <p class="mt-1 text-sm text-slate-500">
            Kelola penempatan dan perkembangan akademik mahasiswa.
        </p>
        <div class="mt-3 inline-flex items-center rounded-lg bg-blue-50 px-3 py-2 text-sm font-medium text-blue-700">
            Program Studi: {{ $prodi->nama_prodi ?? $prodi->nama }}
        </div>
    </div>

    {{-- Filter mahasiswa --}}
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="mb-4">
            <h2 class="font-semibold text-slate-800">Filter Mahasiswa</h2>
            <p class="mt-1 text-sm text-slate-500">
                Cari mahasiswa berdasarkan NIM, nama, atau status.
            </p>
        </div>

        <form method="GET"
              action="{{ route('kaprodi.penempatan.index') }}"
              class="grid grid-cols-1 gap-4 md:grid-cols-12">

            <div class="md:col-span-6">
                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Nama atau NIM Mahasiswa
                </label>
                <input
                    type="text"
                    name="keyword"
                    value="{{ request('keyword') }}"
                    placeholder="Masukkan NIM atau nama mahasiswa..."
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
            </div>

            <div class="md:col-span-3">
                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Status Mahasiswa
                </label>
                <select
                    name="status"
                    class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >
                    <option value="">Semua Status</option>
                    @foreach(['aktif', 'cuti', 'lulus', 'DO'] as $status)
                        <option value="{{ $status }}"
                            {{ request('status') === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2 md:col-span-3">
                <button
                    type="submit"
                    class="flex-1 rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-800"
                >
                    Cari
                </button>

                <a
                    href="{{ route('kaprodi.penempatan.index') }}"
                    class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
                >
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Tabel mahasiswa --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="flex flex-col gap-2 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-slate-800">
                    Daftar Mahasiswa
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Data mahasiswa pada program studi yang Anda kelola.
                </p>
            </div>

            <span class="text-sm font-medium text-slate-500">
                Total: {{ $mahasiswa->total() }} mahasiswa
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-4 font-semibold">No.</th>
                        <th class="px-5 py-4 font-semibold">NIM</th>
                        <th class="px-5 py-4 font-semibold">Nama Mahasiswa</th>
                        <th class="px-5 py-4 font-semibold">Angkatan Masuk</th>
                        <th class="px-5 py-4 font-semibold">Kelas Saat Ini</th>
                        <th class="px-5 py-4 font-semibold">Status</th>
                        <th class="px-5 py-4 font-semibold">Semester Berikutnya</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($mahasiswa as $index => $item)
                        <tr class="transition hover:bg-slate-50">
                            <td class="whitespace-nowrap px-5 py-4 text-slate-500">
                                {{ $mahasiswa->firstItem() + $index }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-700">
                                {{ $item->nim }}
                            </td>

                            <td class="px-5 py-4 font-medium text-slate-800">
                                {{ $item->nama }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                {{ $item->angkatan_masuk ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                {{ $item->kelasMahasiswa->nama_kelas ?? '-' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                @if($item->status === 'aktif')
                                    <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                        Aktif
                                    </span>
                                @elseif($item->status === 'cuti')
                                    <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                        Cuti
                                    </span>
                                @elseif($item->status === 'lulus')
                                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                        Lulus
                                    </span>
                                @else
                                    <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                        {{ $item->status }}
                                    </span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                @if($item->progresStudi)
                                    Semester {{ $item->progresStudi->semester_berikutnya }}
                                @else
                                    <span class="text-slate-400">Belum diatur</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <p class="font-medium text-slate-700">
                                    Data mahasiswa tidak ditemukan.
                                </p>
                                <p class="mt-1 text-sm text-slate-500">
                                    Coba ubah kata kunci atau filter pencarian.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex flex-col gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-500">
                Menampilkan
                <span class="font-medium text-slate-700">{{ $mahasiswa->firstItem() ?? 0 }}</span>
                sampai
                <span class="font-medium text-slate-700">{{ $mahasiswa->lastItem() ?? 0 }}</span>
                dari
                <span class="font-medium text-slate-700">{{ $mahasiswa->total() }}</span>
                mahasiswa
            </p>

            <div>
                {{ $mahasiswa->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
