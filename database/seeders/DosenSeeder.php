<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        $passwordDsn = [
            'uname1' => 'pass',
            'uname2' => 'pass'
        ];

        $dosen = Dosen::all();
        foreach ($dosen as $dsn) {
            $user = User::create([
                'username' => $dsn->kode_dsn,
                'password' => Hash::make($passwordDsn[$dsn->kode_dsn]),
                'us_role' => 'dsn',
            ]);
            
            $dsn->update([
                'id_user' => $user->id_user
            ]);
        }
    }
}
