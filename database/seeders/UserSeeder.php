<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Seed 3 akun utama untuk keperluan demo SIPA.
     */
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Administrator',
                'email'    => 'admin@mail.com',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ],
            [
                'name'     => 'Budi Santoso',
                'email'    => 'petugas@mail.com',
                'password' => Hash::make('password'),
                'role'     => 'petugas',
            ],
            [
                'name'     => 'Andi Pratama',
                'email'    => 'peminjam@mail.com',
                'password' => Hash::make('password'),
                'role'     => 'peminjam',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}
