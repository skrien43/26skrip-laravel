<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $passwordMhs = [
            'uname1' => 'pass',
            'uname2' => 'pass'
        ];

        $mahasiswa = Mahasiswa::all();
        foreach ($mahasiswa as $mhs) {
            $user = User::create([
                'username' => $mhs->npm,
                'password' => Hash::make($passwordMhs[$mhs->npm]),
                'us_role' => 'mhs',
            ]);

            $mhs->update([
                'id_user' => $user->id_user
            ]);
        }
    }
}
