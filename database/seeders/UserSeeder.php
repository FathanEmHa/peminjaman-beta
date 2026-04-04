<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Petugas',
            'email' => 'petugas@mail.com',
            'password' => Hash::make('password'),
            'role' => 'petugas'
        ]);

        User::create([
            'name' => 'Peminjam 1',
            'email' => 'user1@mail.com',
            'password' => Hash::make('password'),
            'role' => 'peminjam'
        ]);

        User::create([
            'name' => 'Peminjam 2',
            'email' => 'user2@mail.com',
            'password' => Hash::make('password'),
            'role' => 'peminjam'
        ]);
    }
}
