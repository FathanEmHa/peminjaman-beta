<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asset;
use App\Models\Category;

class AssetSeeder extends Seeder
{
    /**
     * Seed 5 aset dengan foto yang sesuai file yang sudah disiapkan
     * di storage/app/public/assets/.
     *
     * Urutan ID aset yang dijamin (bergantung urutan insert):
     *   1 → Laptop Asus ROG
     *   2 → Proyektor Epson
     *   3 → Printer Canon
     *   4 → Teleskop Bintang
     *   5 → Bor Listrik Bosch
     */
    public function run(): void
    {
        $elektronik  = Category::where('name', 'Elektronik')->first();
        $sains       = Category::where('name', 'Sains & Alat Ukur')->first();
        $perkakas    = Category::where('name', 'Perkakas')->first();

        $assets = [
            [
                'name'        => 'Laptop Asus ROG',
                'photo'       => 'assets/laptop.jpeg',
                'stock'       => 5,
                'category_id' => $elektronik->id,
            ],
            [
                'name'        => 'Proyektor Epson',
                'photo'       => 'assets/proyektor.jpeg',
                'stock'       => 3,
                'category_id' => $elektronik->id,
            ],
            [
                'name'        => 'Printer Canon',
                'photo'       => 'assets/printer.jpeg',
                'stock'       => 4,
                'category_id' => $elektronik->id,
            ],
            [
                'name'        => 'Teleskop Bintang',
                'photo'       => 'assets/teleskop.jpeg',
                'stock'       => 2,
                'category_id' => $sains->id,
            ],
            [
                'name'        => 'Bor Listrik Bosch',
                'photo'       => 'assets/bor-listrik.jpeg',
                'stock'       => 6,
                'category_id' => $perkakas->id,
            ],
        ];

        foreach ($assets as $asset) {
            Asset::updateOrCreate(
                ['name' => $asset['name']],
                $asset
            );
        }
    }
}
