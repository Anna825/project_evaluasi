<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProdiRequest;
use App\Models\Jurusan;
use App\Models\Prodi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdiController extends Controller
{
    private function pastikanAdmin(): void
    {
        if (! auth()->user()->roles()->where('nama_role', 'admin')->exists()) {
            abort(403, 'Hanya Admin yang dapat mengelola Program Studi.');
        }
    }
    public function index()
    {
        $this->pastikanAdmin();
        $prodi = Prodi::with([
            'jurusan',
            'dosen.user',
        ])->latest()->get();

        $kaprodiRole = Role::where('nama_role', 'kaprodi')->first();

        $kaprodiUsers = collect();

        if ($kaprodiRole) {
            $kaprodiUsers = User::with('dosen')
                ->whereHas('roles', function ($query) use ($kaprodiRole) {
                    $query->where('roles.id', $kaprodiRole->id);
                })
                ->get();
        }

        // Ambil hanya Dosen yang akun User-nya aktif,
        // lalu kelompokkan berdasarkan Prodi.
        $dosenAktifPerProdi = \App\Models\Dosen::with('user')
            ->whereHas('user', function ($query) {
                $query->where('status', 'aktif');
            })
            ->get()
            ->groupBy('prodi_id');

        return view('prodi.index', compact(
            'prodi',
            'kaprodiUsers',
            'dosenAktifPerProdi'
        ));
    }    
    public function create()
    {
        $this->pastikanAdmin();
        $jurusanList = Jurusan::all();

        return view('prodi.create', compact('jurusanList'));
    }

    public function store(StoreProdiRequest $request)
    {
        $this->pastikanAdmin();
        Prodi::create($request->validated());

        return redirect()->route('prodi.index')->with('status', 'Prodi berhasil ditambahkan.');
    }

    public function edit(Prodi $prodi)
    {
        $this->pastikanAdmin();
        $jurusanList = Jurusan::all();

        return view('prodi.edit', compact('prodi', 'jurusanList'));
    }

    public function update(StoreProdiRequest $request, Prodi $prodi)
    {
        $this->pastikanAdmin();
        $prodi->update($request->validated());

        return redirect()->route('prodi.index')->with('status', 'Prodi berhasil diperbarui.');
    }

    public function setKaprodi(Request $request, Prodi $prodi)
    {
        $this->pastikanAdmin();
        $validated = $request->validate([
            'dosen_id' => ['required', 'exists:dosen,id'],
        ]);

        $dosen = \App\Models\Dosen::with('user')->findOrFail($validated['dosen_id']);

        // Pastikan Dosen memiliki akun User
        if (! $dosen->user) {
            return back()
                ->withErrors([
                    'dosen_id' => 'Dosen yang dipilih belum terhubung dengan akun pengguna.',
                ])
                ->withInput();
        }

        // Pastikan Dosen berasal dari Prodi yang sedang diatur
        if ((int) $dosen->prodi_id !== (int) $prodi->id) {
            return back()
                ->withErrors([
                    'dosen_id' => 'Dosen yang dipilih tidak berasal dari Program Studi ini.',
                ])
                ->withInput();
        }

        // Pastikan akun Dosen aktif
        if ($dosen->user->status !== 'aktif') {
            return back()
                ->withErrors([
                    'dosen_id' => 'Hanya Dosen dengan akun aktif yang dapat ditetapkan sebagai Kaprodi.',
                ])
                ->withInput();
        }

        // Cari role Kaprodi
        $kaprodiRole = Role::where('nama_role', 'kaprodi')->first();

        if (! $kaprodiRole) {
            return back()->withErrors([
                'dosen_id' => 'Role Kaprodi belum tersedia di sistem.',
            ]);
        }

        DB::transaction(function () use ($prodi, $dosen, $kaprodiRole) {

            /*
            * Hapus penugasan Kaprodi lama
            * yang berada pada Prodi ini.
            */
            DB::table('user_roles')
                ->where('role_id', $kaprodiRole->id)
                ->where('prodi_id', $prodi->id)
                ->delete();

            /*
            * Cek apakah Dosen yang dipilih
            * sudah menjadi Kaprodi di Prodi lain.
            */
            $kaprodiDiProdiLain = DB::table('user_roles')
                ->where('user_id', $dosen->user->id)
                ->where('role_id', $kaprodiRole->id)
                ->whereNotNull('prodi_id')
                ->where('prodi_id', '!=', $prodi->id)
                ->exists();

            if ($kaprodiDiProdiLain) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'dosen_id' => 'Dosen tersebut sudah ditetapkan sebagai Kaprodi di Program Studi lain.',
                ]);
            }

            /*
            * Cek apakah Dosen sudah memiliki role Kaprodi.
            */
            $existingRole = DB::table('user_roles')
                ->where('user_id', $dosen->user->id)
                ->where('role_id', $kaprodiRole->id)
                ->first();

            if ($existingRole) {

                // Jika sudah punya role Kaprodi,
                // hubungkan role tersebut dengan Prodi ini.
                DB::table('user_roles')
                    ->where('id', $existingRole->id)
                    ->update([
                        'prodi_id' => $prodi->id,
                        'updated_at' => now(),
                    ]);

            } else {

                // Jika belum punya role Kaprodi,
                // tambahkan role Kaprodi.
                DB::table('user_roles')->insert([
                    'user_id' => $dosen->user->id,
                    'role_id' => $kaprodiRole->id,
                    'prodi_id' => $prodi->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        return back()->with(
            'status',
            "Sdr/i {$dosen->nama} berhasil ditetapkan sebagai Kaprodi {$prodi->nama}."
        );
    }

    public function destroy(Prodi $prodi)
    {
        $this->pastikanAdmin();
        $prodi->delete();

        return redirect()->route('prodi.index')->with('status', 'Prodi berhasil dihapus.');
    }
}