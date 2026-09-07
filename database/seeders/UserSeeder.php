<?php

namespace Database\Seeders;

use App\Models\Prodi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat akun Admin default
        $admin = User::firstOrCreate(
            ['email' => 'admin@evaluasipbm.test'],
            [
                'name' => 'Admin Sistem',
                'password' => Hash::make('admin123'),
                'status' => 'aktif',
            ]
        );
        $adminRole = Role::where('nama_role', 'admin')->first();
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        // Buat akun Kaprodi default
        $prodi = Prodi::first();

        $kaprodi = User::firstOrCreate(
            ['email' => 'kaprodi@evaluasipbm.test'],
            [
                'name' => 'Siti Aminah',
                'password' => Hash::make('kaprodi123'),
                'status' => 'aktif',
            ]
        );
        $kaprodiRole = Role::where('nama_role', 'kaprodi')->first();
        $kaprodi->roles()->syncWithoutDetaching([
            $kaprodiRole->id => ['prodi_id' => $prodi?->id],
        ]);
    }
}