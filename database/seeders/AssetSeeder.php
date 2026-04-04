<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Category;
use App\Models\Asset;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $elektronik = Category::where('name', 'Elektronik')->first();
        $lab = Category::where('name', 'Alat Lab')->first();

        Asset::create([
            'name' => 'Laptop',
            'category_id' => $elektronik->id,
            'stock' => 10
        ]);

        Asset::create([
            'name' => 'Proyektor',
            'category_id' => $elektronik->id,
            'stock' => 5
        ]);

        Asset::create([
            'name' => 'Mikroskop',
            'category_id' => $lab->id,
            'stock' => 7
        ]);
    }
}
