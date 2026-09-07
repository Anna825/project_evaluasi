<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function create()
    {
        $stats = [
            'mahasiswa' => \App\Models\Mahasiswa::count(),
            'dosen' => \App\Models\Dosen::count(),
            'akreditasi' => 'A',
        ];

        return response()
        ->view('auth.login', compact('stats'))
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache');
    }

    /**
     * Proses login.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        $user = Auth::user();

        // Cek status akun (khusus dosen yang masih pending)
        if ($user->status === 'pending') {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Akun Anda masih menunggu aktivasi dari Admin.',
            ]);
        }

        if ($user->status === 'nonaktif') {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Akun Anda telah dinonaktifkan. Hubungi Admin.',
            ]);
        }

        $request->session()->regenerate();

        return $this->redirectBasedOnRole($user);
    }

    /**
     * Proses logout.
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Arahkan user ke dashboard sesuai role setelah login.
     */
    protected function redirectBasedOnRole($user)
    {
        $roleNames = $user->roles()->pluck('nama_role')->toArray();

        if (in_array('admin', $roleNames)) {
            return redirect()->route('admin.dashboard');
        }

        if (in_array('kaprodi', $roleNames)) {
            return redirect()->route('kaprodi.dashboard');
        }

        if (in_array('dosen', $roleNames)) {
            return redirect()->route('dosen.dashboard');
        }

        // Fallback kalau user tidak punya role sama sekali
        Auth::logout();
        throw ValidationException::withMessages([
            'email' => 'Akun Anda belum memiliki role. Hubungi Admin.',
        ]);
    }
}