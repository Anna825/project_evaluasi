<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AlumniRegisterController extends Controller
{
    /**
     * Tampilkan halaman registrasi Alumni.
     */
    public function create()
    {
        return view('auth.alumni-register');
    }

        /**
     * Mengecek NIM calon Alumni.
     */
    public function checkNim(Request $request)
    {
        $request->validate([
            'nim' => ['required', 'string'],
        ]);

        $mahasiswa = Mahasiswa::with('prodi')
            ->where('nim', $request->nim)
            ->first();

        if (! $mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'NIM tidak ditemukan dalam data mahasiswa.',
            ], 404);
        }

        if ($mahasiswa->status !== 'lulus') {
            return response()->json([
                'success' => false,
                'message' => 'NIM tersebut belum berstatus lulus. Hanya mahasiswa yang sudah lulus yang dapat mendaftar sebagai Alumni.',
            ], 422);
        }

        // Pastikan NIM tersebut belum memiliki akun Alumni.
        $alumniRole = Role::where('nama_role', 'alumni')->first();

        if ($alumniRole) {
            $sudahTerdaftar = DB::table('user_roles')
                ->where('mahasiswa_id', $mahasiswa->id)
                ->where('role_id', $alumniRole->id)
                ->exists();

            if ($sudahTerdaftar) {
                return response()->json([
                    'success' => false,
                    'message' => 'NIM tersebut sudah memiliki akun Alumni.',
                ], 422);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'NIM ditemukan dan terverifikasi sebagai Alumni.',
            'data' => [
                'nama' => $mahasiswa->nama,
                'prodi' => $mahasiswa->prodi?->nama ?? '-',
            ],
        ]);
    }

    /**
     * Proses registrasi Alumni.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Cari data mahasiswa berdasarkan NIM.
        $mahasiswa = Mahasiswa::where('nim', $validated['nim'])->first();

        if (! $mahasiswa) {
            return back()
                ->withInput()
                ->withErrors([
                    'nim' => 'NIM tidak ditemukan dalam data mahasiswa.',
                ]);
        }

        // Hanya mahasiswa yang sudah lulus yang boleh mendaftar sebagai Alumni.
        if ($mahasiswa->status !== 'lulus') {
            return back()
                ->withInput()
                ->withErrors([
                    'nim' => 'NIM tersebut belum berstatus lulus. Registrasi Alumni hanya dapat dilakukan oleh mahasiswa yang sudah lulus.',
                ]);
        }

        // Pastikan mahasiswa tersebut belum memiliki akun Alumni.
        $alumniRole = Role::where('nama_role', 'alumni')->first();

        if (! $alumniRole) {
            return back()
                ->withInput()
                ->withErrors([
                    'nim' => 'Role Alumni belum tersedia di sistem.',
                ]);
        }

        $sudahTerdaftar = DB::table('user_roles')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('role_id', $alumniRole->id)
            ->exists();

        if ($sudahTerdaftar) {
            return back()
                ->withInput()
                ->withErrors([
                    'nim' => 'NIM tersebut sudah memiliki akun Alumni.',
                ]);
        }

        DB::transaction(function () use ($validated, $mahasiswa, $alumniRole) {
            // Buat akun User dalam status pending.
            $user = User::create([
                'name' => $mahasiswa->nama,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'status' => 'pending',
            ]);

            // Hubungkan akun User dengan role Alumni
            // sekaligus dengan data Mahasiswa.
            $user->roles()->attach($alumniRole->id, [
                'mahasiswa_id' => $mahasiswa->id,
                'prodi_id' => $mahasiswa->prodi_id,
            ]);
        });

        return redirect()
            ->route('alumni.login')
            ->with(
                'status',
                'Pendaftaran Alumni berhasil! Akun Anda menunggu aktivasi dari Admin.'
            );
    }
}