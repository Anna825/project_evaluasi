@extends('layouts.app')

@section('title', $mahasiswa->nama . ' - Evaluasi PBM')
@section('page-title', $mahasiswa->nama)
@section('page-desc', 'Detail data mahasiswa')

@section('content')
    <div class="mb-5">
        <a href="{{ route('mahasiswa.kelas.show', $mahasiswa->kelasMahasiswa) }}"
        class="inline-flex items-center gap-2 text-sm font-medium transition hover:opacity-70"
        style="color: var(--muted-foreground);">
            ← Kembali ke Daftar Mahasiswa
        </a>
    </div>
    @php
        $statusBadge = [
            'aktif' => ['bg' => '#eff6ff', 'text' => '#1e40af'],
            'cuti' => ['bg' => '#fffbeb', 'text' => '#92400e'],
            'lulus' => ['bg' => '#f0fdf4', 'text' => '#166534'],
            'DO' => ['bg' => '#fef2f2', 'text' => '#991b1b'],
        ];
        $badge = $statusBadge[$mahasiswa->status] ?? ['bg' => '#eee', 'text' => '#333'];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 rounded-2xl border p-6" style="background: var(--card); border-color: var(--border);">
            <div class="w-14 h-14 rounded-full flex items-center justify-center font-bold text-xl text-white mb-4" style="background: var(--primary);">
                {{ strtoupper(substr($mahasiswa->nama, 0, 1)) }}
            </div>
            <h2 class="font-serif-display text-xl mb-1">{{ $mahasiswa->nama }}</h2>
            <span class="text-xs px-2.5 py-1 rounded-full font-semibold inline-block mb-5" style="background: {{ $badge['bg'] }}; color: {{ $badge['text'] }};">
                {{ ucfirst($mahasiswa->status) }}
            </span>

            <dl class="space-y-3 text-sm">
                <div class="flex justify-between gap-4">
                    <dt style="color: var(--muted-foreground);">NIM</dt>
                    <dd class="mono font-semibold text-right">{{ $mahasiswa->nim }}</dd>
                </div>

                <div class="flex justify-between gap-4">
                    <dt style="color: var(--muted-foreground);">Program Studi</dt>
                    <dd class="font-medium text-right">
                        {{ $mahasiswa->prodi->nama ?? '-' }}
                    </dd>
                </div>

                <div class="flex justify-between gap-4">
                    <dt style="color: var(--muted-foreground);">Kelas</dt>
                    <dd class="font-medium text-right">
                        {{ $mahasiswa->kelasMahasiswa->nama_kelas ?? '-' }}
                    </dd>
                </div>

                <div class="flex justify-between gap-4">
                    <dt style="color: var(--muted-foreground);">Angkatan</dt>
                    <dd class="font-medium text-right">
                        {{ $mahasiswa->kelasMahasiswa->angkatan ?? '-' }}
                    </dd>
                </div>
            </dl>

            <div class="flex gap-2 mt-6">
                <button type="button" onclick="document.getElementById('modalEditMahasiswa').showModal()" class="flex-1 text-center px-4 py-2.5 rounded-lg text-sm font-medium text-white" style="background: var(--primary);">Edit</button>
                <a href="{{ route('mahasiswa.index') }}" class="flex-1 text-center px-4 py-2.5 rounded-lg text-sm font-medium border" style="border-color: var(--border); color: var(--muted-foreground);">
                    Kembali
                </a>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
                <div class="px-6 py-4 border-b" style="border-color: var(--border);">
                    <h2 class="font-serif-display text-lg">Riwayat Prestasi</h2>
                </div>
                <div class="p-4 space-y-2">
                    @forelse ($mahasiswa->prestasi as $p)
                        <div class="flex items-center justify-between px-4 py-3 rounded-xl" style="background: var(--secondary);">
                            <div>
                                <p class="text-sm font-medium">{{ $p->nama_kegiatan }}</p>
                                <p class="text-xs" style="color: var(--muted-foreground);">{{ $p->tingkat ?? '-' }}</p>
                            </div>
                            <span class="text-xs font-semibold" style="color: var(--accent);">{{ $p->peringkat ?? '-' }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-center py-6" style="color: var(--muted-foreground);">Belum ada data prestasi.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
                <div class="px-6 py-4 border-b" style="border-color: var(--border);">
                    <h2 class="font-serif-display text-lg">Riwayat Akademik</h2>
                </div>
                <div class="p-4 space-y-3">
                    @forelse ($mahasiswa->riwayatAksiAkademik as $riwayat)
                        <div class="rounded-xl px-4 py-3" style="background: var(--secondary);">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <p class="text-sm font-semibold">{{ ucwords(str_replace('_', ' ', $riwayat->jenis_aksi)) }}</p>
                                <span class="text-xs" style="color: var(--muted-foreground);">{{ $riwayat->created_at?->format('d M Y H:i') }}</span>
                            </div>
                            @if($riwayat->alasan)<p class="mt-1 text-xs" style="color: var(--muted-foreground);">{{ $riwayat->alasan }}</p>@endif
                            <p class="mt-1 text-xs" style="color: var(--muted-foreground);">Dicatat oleh: {{ $riwayat->pelaksana->name ?? 'Sistem' }}</p>
                        </div>
                    @empty
                        <p class="py-5 text-center text-sm" style="color: var(--muted-foreground);">Belum ada riwayat perubahan akademik.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border overflow-hidden" style="background: var(--card); border-color: var(--border);">
                <div class="px-6 py-4 border-b" style="border-color: var(--border);">
                    <h2 class="font-serif-display text-lg">Data Tracer Study</h2>
                </div>
                <div class="p-4 space-y-2">
                    @forelse ($mahasiswa->tracerStudy as $t)
                        <div class="flex items-center justify-between px-4 py-3 rounded-xl" style="background: var(--secondary);">
                            <div>
                                <p class="text-sm font-medium">Periode {{ $t->periode_pelacakan }}</p>
                                <p class="text-xs" style="color: var(--muted-foreground);">{{ $t->status_utama ?? '-' }} di {{ $t->nama_instansi ?? '-' }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-center py-6" style="color: var(--muted-foreground);">Belum ada data tracer study.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @if($errors->any())<div class="mb-4 rounded-xl border px-4 py-3 text-sm" style="background:#fef2f2;border-color:#fecaca;color:#991b1b"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <dialog id="modalEditMahasiswa" class="ui-modal">
        <form method="POST" action="{{ route('mahasiswa.update',$mahasiswa) }}" class="ui-modal__panel">
            @csrf @method('PUT') <input type="hidden" name="form_modal" value="mahasiswa-edit">
            <div class="ui-modal__header"><div><h2 class="text-lg font-semibold">Edit Data Mahasiswa</h2><p class="text-sm" style="color:var(--ui-muted)">{{ $mahasiswa->nama }} — {{ $mahasiswa->nim }}</p></div><button type="button" class="ui-modal__close" onclick="this.closest('dialog').close()">×</button></div>
            <div class="ui-modal__body space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium mb-1">NIM *</label><input name="nim" value="{{ old('nim',$mahasiswa->nim) }}" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                    <div><label class="block text-sm font-medium mb-1">Nama Lengkap *</label><input name="nama" value="{{ old('nama',$mahasiswa->nama) }}" required maxlength="255" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                </div>
                <div><label class="block text-sm font-medium mb-1">Program Studi *</label><select name="prodi_id" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)">@foreach(\App\Models\Prodi::orderBy('nama')->get() as $prodi)<option value="{{ $prodi->id }}" @selected(old('prodi_id',$mahasiswa->prodi_id)==$prodi->id)>{{ $prodi->nama }}</option>@endforeach</select></div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div><label class="block text-sm font-medium mb-1">Angkatan Masuk *</label><input type="number" name="angkatan" min="2000" max="{{ date('Y')+1 }}" value="{{ old('angkatan',$mahasiswa->angkatan_masuk) }}" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                    <div><label class="block text-sm font-medium mb-1">IPK Terakhir</label><input type="number" name="ipk_terakhir" min="0" max="4" step="0.01" value="{{ old('ipk_terakhir',$mahasiswa->ipk_terakhir) }}" class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"></div>
                </div>
                <div><label class="block text-sm font-medium mb-1">Status Mahasiswa *</label><select name="status" required class="w-full rounded-xl border px-3 py-2.5" style="border-color:var(--ui-border)"><option value="aktif" @selected(old('status',$mahasiswa->status)==='aktif')>Aktif</option><option value="cuti" @selected(old('status',$mahasiswa->status)==='cuti')>Cuti</option><option value="DO" @selected(old('status',$mahasiswa->status)==='DO')>DO</option>@if($mahasiswa->status==='lulus')<option value="lulus" selected>Lulus</option>@endif</select><p class="text-xs mt-1" style="color:var(--ui-muted)">Perubahan menjadi Lulus dilakukan melalui menu Penetapan Kelulusan Kaprodi. Mahasiswa Cuti yang kembali aktif harus melalui menu Penempatan.</p></div>
            </div>
            <div class="ui-modal__footer"><button type="button" class="px-4 py-2 rounded-xl border" onclick="this.closest('dialog').close()">Batal</button><button type="submit" class="px-4 py-2 rounded-xl text-white" style="background:var(--ui-primary)">Simpan Perubahan</button></div>
        </form>
    </dialog>
    <script>const editMhsDialog=document.getElementById('modalEditMahasiswa');editMhsDialog?.addEventListener('click',e=>{if(e.target===editMhsDialog)editMhsDialog.close()});@if($errors->any() && old('form_modal')==='mahasiswa-edit')document.addEventListener('DOMContentLoaded',()=>editMhsDialog?.showModal());@endif</script>

@endsection