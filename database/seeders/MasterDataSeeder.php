<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\Prodi;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $jurusan = Jurusan::firstOrCreate(
            ['nama' => 'Teknik Otomasi Manufaktur dan Mekatronika'],
            ['kajur' => null]
        );

        Prodi::firstOrCreate(
            [
                'jurusan_id' => $jurusan->id,
                'nama' => 'Teknologi Rekayasa Informatika Industri',
            ]
        );
    }
}