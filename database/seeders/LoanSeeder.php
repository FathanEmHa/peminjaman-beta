<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Loan;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('role', 'peminjam')->first();

        Loan::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'loan_date' => now(),
            'return_date' => now()->addDays(3),
        ]);
    }
}
