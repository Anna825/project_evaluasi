<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Tampilkan form register dosen.
     */
    public function create()
    {
        $prodiList = Prodi::all();

        return response()
            ->view('auth.register', ['prodiList' => $prodiList])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    /**
     * Proses register dosen baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nidn' => ['required', 'string', 'unique:dosen,nidn'],
            'prodi_id' => ['required', 'exists:prodi,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => 'pending',
        ]);

        Dosen::create([
            'user_id' => $user->id,
            'prodi_id' => $validated['prodi_id'],
            'nidn' => $validated['nidn'],
            'nama' => $validated['name'],
        ]);

        $dosenRole = Role::where('nama_role', 'dosen')->first();
        $user->roles()->attach($dosenRole->id);

        return redirect('/login')->with('status', 'Pendaftaran berhasil! Akun Anda menunggu aktivasi dari Admin.');
    }
}