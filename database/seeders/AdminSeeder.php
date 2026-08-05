<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $passwordAdm = [
            'uname1' => 'pass',
            'xxx1' => 'pass123'
        ];

        $admin = Admin::all();
        foreach ($admin as $adm) {
            // $plainPassword = $passwordAdm[$adm->kode_peg] ?? 'pass123';
            // $user = User::create([
            //     'username' => $adm->kode_peg,
            //     'password' => Hash::make($plainPassword),
            //     'us_role'  => 'adm',
            // ]);

            $user = User::create([
                'username' => $adm->kode_peg,
                'password' => Hash::make($passwordAdm[$adm->kode_peg]),
                'us_role' => 'adm',
            ]);

            $adm->update([
                'id_user' => $user->id_user
            ]);
        }
    }
}
