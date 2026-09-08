<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * Tampilkan daftar user (khusus yang punya role dosen).
     */
    public function index()
    {
        $users = User::whereHas('dosen')
            ->with('dosen.prodi')
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->get();

        return view('admin.users.index', ['users' => $users]);
    }

    public function show(User $user)
    {
        $user->load('dosen.prodi');

        return view('admin.users.show', compact('user'));
    }

    /**
     * Aktifkan akun dosen.
     */
    public function activate(User $user)
    {
        $user->update(['status' => 'aktif']);

        return back()->with('status', "Akun {$user->name} berhasil diaktifkan.");
    }

    /**
     * Nonaktifkan akun dosen.
     */
    public function deactivate(User $user)
    {
        $user->update(['status' => 'nonaktif']);

        return back()->with('status', "Akun {$user->name} telah dinonaktifkan.");
    }
}