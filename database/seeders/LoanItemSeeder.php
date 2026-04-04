<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Loan;
use App\Models\Asset;
use App\Models\LoanItem;

class LoanItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $loan = Loan::first();
        $asset = Asset::first();

        LoanItem::create([
            'loan_id' => $loan->id,
            'asset_id' => $asset->id,
            'quantity' => 2
        ]);
    }
}
