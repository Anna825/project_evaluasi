<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlumniLoginController extends Controller
{
    public function create()
    {
        $stats = [
            'mahasiswa' => \App\Models\Mahasiswa::where('status', 'aktif')->count(),
            'dosen' => \App\Models\Dosen::count(),
            'akreditasi' => 'A',
        ];

        return view('auth.alumni-login', compact('stats'));
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        // Pastikan akun memiliki role Alumni
        if (! $user || ! $user->roles()->where('nama_role', 'alumni')->exists()) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Akun Alumni dengan email tersebut tidak ditemukan.',
                ]);
        }

        // Akun masih menunggu aktivasi Admin
        if ($user->status === 'pending') {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Akun Alumni Anda masih menunggu aktivasi dari Admin.',
                ]);
        }

        // Akun dinonaktifkan
        if ($user->status !== 'aktif') {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Akun Alumni Anda tidak aktif. Silakan hubungi Admin.',
                ]);
        }

        // Cek email + password
        if (! Auth::attempt($credentials)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Email atau password yang Anda masukkan salah.',
                ]);
        }

        $request->session()->regenerate();

        return redirect()->route('alumni.dashboard');
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('alumni.login');
    }
}