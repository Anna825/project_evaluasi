<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\TahunAkademikController;
use App\Http\Controllers\MahasiswaImportController;
use App\Http\Controllers\KurikulumController;
use App\Http\Controllers\CplController;
use App\Http\Controllers\MataKuliahDosenController;
use App\Http\Controllers\CpmkController;
use App\Http\Controllers\RpsController;
use App\Http\Controllers\NilaiCpmkController;
use App\Http\Controllers\LaporanCplController;
use App\Http\Controllers\PenelitianPkmController;
use App\Http\Controllers\LaporanAkhirPkmController;
use App\Http\Controllers\HilirisasiPkmController;
use App\Http\Controllers\PrestasiDosenController;
use App\Http\Controllers\PublicMahasiswaController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    if (Auth::check()) {
        $roleNames = Auth::user()->roles()->pluck('nama_role')->toArray();

        if (in_array('admin', $roleNames)) {
            return redirect()->route('admin.dashboard');
        }
        if (in_array('kaprodi', $roleNames)) {
            return redirect()->route('kaprodi.dashboard');
        }
        if (in_array('dosen', $roleNames)) {
            return redirect()->route('dosen.dashboard');
        }

        Auth::logout();
    }

    return redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
});

// Akses publik untuk mahasiswa (tanpa login, pakai NIM)
Route::get('/cek-nim', [PublicMahasiswaController::class, 'cekNim'])->name('public.cek-nim');
Route::post('/cek-nim', [PublicMahasiswaController::class, 'prosesCekNim'])->name('public.proses-cek-nim');
Route::get('/login-mahasiswa', [PublicMahasiswaController::class, 'cekNim'])->name('mahasiswa.login');
Route::post('/login-mahasiswa', [PublicMahasiswaController::class, 'prosesCekNim']);
Route::get('/mahasiswa-publik/{nim}/menu', [PublicMahasiswaController::class, 'menu'])->name('public.mahasiswa.menu');
Route::get('/mahasiswa-publik/{nim}/prestasi-saya', [PublicMahasiswaController::class, 'prestasiIndex'])->name('public.prestasi.index');
Route::get('/mahasiswa-publik/{nim}/profil', [PublicMahasiswaController::class, 'profil'])->name('public.profil');
Route::get('/mahasiswa-publik/{nim}/prestasi/create', [PublicMahasiswaController::class, 'prestasiCreate'])->name('public.prestasi.create');
Route::post('/mahasiswa-publik/{nim}/prestasi', [PublicMahasiswaController::class, 'prestasiStore'])->name('public.prestasi.store');
Route::get('/mahasiswa-publik/{nim}/tracer/create', [PublicMahasiswaController::class, 'tracerCreate'])->name('public.tracer.create');
Route::post('/mahasiswa-publik/{nim}/tracer', [PublicMahasiswaController::class, 'tracerStore'])->name('public.tracer.store');

Route::middleware(['auth', 'role:admin'])->group(function () {
    // Route::get('/admin/dashboard', function () {
    //     return view('admin.dashboard');
    // })->name('admin.dashboard');

    Route::get('/admin/dashboard', function () {
        $stats = [
            'mahasiswa' => \App\Models\Mahasiswa::count(),
            'dosen' => \App\Models\Dosen::count(),
            'dosen_pending' => \App\Models\User::whereHas('dosen')->where('status', 'pending')->count(),
            'prodi' => \App\Models\Prodi::count(),
        ];

        $activities = collect();

        foreach (\App\Models\Mahasiswa::latest()->take(3)->get() as $m) {
            $activities->push(['label' => "Mahasiswa baru terdaftar: {$m->nama}", 'time' => $m->created_at, 'color' => '#2e7d32']);
        }
        foreach (\App\Models\PenelitianPkm::latest()->take(3)->get() as $p) {
            $activities->push(['label' => "Penelitian/PKM diajukan: {$p->judul}", 'time' => $p->created_at, 'color' => '#1565c0']);
        }
        foreach (\App\Models\Prestasi::latest()->take(3)->get() as $pr) {
            $activities->push(['label' => "Prestasi dicatat: {$pr->nama_kegiatan}", 'time' => $pr->created_at, 'color' => '#b8952a']);
        }

        $activities = $activities->sortByDesc('time')->take(5)->values();

        $prodiRingkasan = \App\Models\Prodi::withCount('mahasiswa')->get();

        return view('admin.dashboard', compact('stats', 'activities', 'prodiRingkasan'));
    })->name('admin.dashboard');

    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/{user}', [UserManagementController::class, 'show'])->name('admin.users.show');
    Route::patch('/admin/users/{user}/activate', [UserManagementController::class, 'activate'])->name('admin.users.activate');
    Route::patch('/admin/users/{user}/deactivate', [UserManagementController::class, 'deactivate'])->name('admin.users.deactivate');
});

Route::middleware(['auth', 'role:kaprodi'])->group(function () {
    Route::get('/kaprodi/dashboard', function () {
        $user = auth()->user();
        $kaprodiPivot = $user->roles()->where('nama_role', 'kaprodi')->first()?->pivot;
        $prodiId = $kaprodiPivot?->prodi_id;
        $prodi = \App\Models\Prodi::find($prodiId);

        $stats = [
            'mahasiswa' => \App\Models\Mahasiswa::where('prodi_id', $prodiId)->where('status', 'aktif')->count(),
            'dosen' => \App\Models\Dosen::where('prodi_id', $prodiId)->count(),
            'kurikulum_aktif' => \App\Models\Kurikulum::where('prodi_id', $prodiId)->where('status', 'aktif')->count(),
            'penelitian_pending' => \App\Models\PenelitianPkm::where('status', 'diajukan')->count(),
        ];

        $activities = collect();

        foreach (\App\Models\Mahasiswa::where('prodi_id', $prodiId)->latest()->take(3)->get() as $m) {
            $activities->push(['label' => "Mahasiswa baru terdaftar: {$m->nama}", 'time' => $m->created_at, 'color' => '#2e7d32']);
        }
        foreach (\App\Models\PenelitianPkm::whereHas('dosen', fn ($q) => $q->where('prodi_id', $prodiId))->latest()->take(3)->get() as $p) {
            $activities->push(['label' => "Penelitian/PKM diajukan: {$p->judul}", 'time' => $p->created_at, 'color' => '#1565c0']);
        }
        foreach (\App\Models\Prestasi::whereHas('mahasiswa', fn ($q) => $q->where('prodi_id', $prodiId))->latest()->take(3)->get() as $pr) {
            $activities->push(['label' => "Prestasi dicatat: {$pr->nama_kegiatan}", 'time' => $pr->created_at, 'color' => '#b8952a']);
        }

        $activities = $activities->sortByDesc('time')->take(5)->values();

        $kurikulumList = \App\Models\Kurikulum::where('prodi_id', $prodiId)
            ->withCount('mataKuliah', 'cpl')
            ->latest('tahun_berlaku_mulai')
            ->take(5)
            ->get();

        return view('kaprodi.dashboard', compact('stats', 'activities', 'prodi', 'kurikulumList'));
    })->name('kaprodi.dashboard');
});

Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::get('/dosen/dashboard', function () {
        $dosen = Auth::user()->dosen;

        if (! $dosen) {
            abort(403, 'Akun Anda tidak terhubung ke data dosen.');
        }

        $kelasList = $dosen->kelas()->with('mataKuliah')->get();
        $mataKuliahList = $kelasList->pluck('mataKuliah')->unique('id')->values();

        $stats = [
            'mata_kuliah' => $mataKuliahList->count(),
            'kelas' => $kelasList->count(),
            'penelitian_aktif' => $dosen->penelitianPkm()->whereIn('status', ['disetujui', 'berjalan'])->count(),
            'prestasi' => $dosen->prestasi()->count(),
        ];

        $penelitianTerbaru = $dosen->penelitianPkm()->latest()->take(5)->get();

        return view('dosen.dashboard', compact('stats', 'mataKuliahList', 'kelasList', 'penelitianTerbaru'));
    })->name('dosen.dashboard');

    Route::get('/mata-kuliah', [MataKuliahDosenController::class, 'index'])->name('mata-kuliah.index');
    Route::get('/mata-kuliah/create', [MataKuliahDosenController::class, 'create'])->name('mata-kuliah.create');
    Route::post('/mata-kuliah', [MataKuliahDosenController::class, 'store'])->name('mata-kuliah.store');
    Route::get('/mata-kuliah/{mataKuliah}', [MataKuliahDosenController::class, 'show'])->name('mata-kuliah.show');

    Route::get('/mata-kuliah/{mataKuliah}/cpmk/create', [CpmkController::class, 'create'])->name('cpmk.create');
    Route::post('/cpmk', [CpmkController::class, 'store'])->name('cpmk.store');
    Route::get('/cpmk/{cpmk}/edit', [CpmkController::class, 'edit'])->name('cpmk.edit');
    Route::put('/cpmk/{cpmk}', [CpmkController::class, 'update'])->name('cpmk.update');
    Route::delete('/cpmk/{cpmk}', [CpmkController::class, 'destroy'])->name('cpmk.destroy');

    Route::get('/mata-kuliah/{mataKuliah}/rps/create', [RpsController::class, 'create'])->name('rps.create');
    Route::post('/rps', [RpsController::class, 'store'])->name('rps.store');
    Route::get('/rps/{rps}/edit', [RpsController::class, 'edit'])->name('rps.edit');
    Route::put('/rps/{rps}', [RpsController::class, 'update'])->name('rps.update');
    Route::delete('/rps/{rps}', [RpsController::class, 'destroy'])->name('rps.destroy');

    Route::get('/kelas/{kelas}/nilai-cpmk', [NilaiCpmkController::class, 'create'])->name('nilai-cpmk.create');
    Route::post('/nilai-cpmk', [NilaiCpmkController::class, 'store'])->name('nilai-cpmk.store');

    Route::resource('penelitian-pkm', PenelitianPkmController::class);
    Route::post('/penelitian-pkm/{penelitianPkm}/laporan-akhir', [LaporanAkhirPkmController::class, 'store'])->name('laporan-akhir.store');
    Route::post('/penelitian-pkm/{penelitianPkm}/hilirisasi', [HilirisasiPkmController::class, 'store'])->name('hilirisasi.store');

    Route::get('/prestasi-dosen', [PrestasiDosenController::class, 'index'])->name('prestasi-dosen.index');
    Route::get('/prestasi-dosen/create', [PrestasiDosenController::class, 'create'])->name('prestasi-dosen.create');
    Route::post('/prestasi-dosen', [PrestasiDosenController::class, 'store'])->name('prestasi-dosen.store');

    Route::get('/mata-kuliah/create', [MataKuliahDosenController::class, 'create'])->name('mata-kuliah.create');
    Route::post('/mata-kuliah', [MataKuliahDosenController::class, 'store'])->name('mata-kuliah.store');
    Route::get('/mata-kuliah/{mataKuliah}', [MataKuliahDosenController::class, 'show'])->name('mata-kuliah.show');
});

Route::middleware(['auth', 'role:admin,kaprodi'])->group(function () {
    Route::resource('mahasiswa', MahasiswaController::class);

    Route::get('/mahasiswa-import', [MahasiswaImportController::class, 'create'])->name('mahasiswa.import.create');
    Route::post('/mahasiswa-import', [MahasiswaImportController::class, 'store'])->name('mahasiswa.import.store');
    Route::get('/mahasiswa-import/template', [MahasiswaImportController::class, 'template'])->name('mahasiswa.import.template');

    Route::resource('prodi', ProdiController::class)->except('show');
    Route::resource('tahun-akademik', TahunAkademikController::class)->except('show');

    Route::resource('kurikulum', KurikulumController::class);

    Route::get('/kurikulum/{kurikulum}/cpl/create', [CplController::class, 'create'])->name('cpl.create');
    Route::post('/cpl', [CplController::class, 'store'])->name('cpl.store');
    Route::get('/cpl/{cpl}/edit', [CplController::class, 'edit'])->name('cpl.edit');
    Route::put('/cpl/{cpl}', [CplController::class, 'update'])->name('cpl.update');
    Route::delete('/cpl/{cpl}', [CplController::class, 'destroy'])->name('cpl.destroy');

    Route::get('/kurikulum/{kurikulum}/laporan-cpl', [LaporanCplController::class, 'index'])->name('kurikulum.laporan-cpl');

    Route::get('/verifikasi-penelitian', [PenelitianPkmController::class, 'verifikasiIndex'])->name('penelitian-pkm.verifikasi');
    Route::get('/verifikasi-penelitian/{penelitianPkm}', [PenelitianPkmController::class, 'verifikasiShow'])->name('penelitian-pkm.verifikasi.show');
    Route::patch('/verifikasi-penelitian/{penelitianPkm}/approve', [PenelitianPkmController::class, 'verifikasiApprove'])->name('penelitian-pkm.approve');
    Route::patch('/verifikasi-penelitian/{penelitianPkm}/reject', [PenelitianPkmController::class, 'verifikasiReject'])->name('penelitian-pkm.reject');

    Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    Route::get('/report/mahasiswa/excel', [ReportController::class, 'mahasiswaExcel'])->name('report.mahasiswa.excel');
    Route::get('/report/prestasi/excel', [ReportController::class, 'prestasiExcel'])->name('report.prestasi.excel');
    Route::get('/report/penelitian/excel', [ReportController::class, 'penelitianExcel'])->name('report.penelitian.excel');
    Route::get('/report/laporan-cpl/{kurikulum}/pdf', [ReportController::class, 'laporanCplPdf'])->name('report.laporan-cpl.pdf');
});