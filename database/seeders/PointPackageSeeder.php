<?php

namespace Database\Seeders;

use App\Models\PointPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PointPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //onst pointPackages: PointPackage[] = [
        //     { key: 'basic', points: 100, price: 100000 },
        //     { key: 'standard', points: 300, price: 270000, discount: 10, popular: true },
        //     { key: 'premium', points: 500, price: 400000, discount: 20 },
        //     { key: 'ultimate', points: 1000, price: 700000, discount: 30 },
        // ];

        $pointPackages = [
            [
                'key' => 'basic',
                'points' => 100,
                'price' => 100000,
            ],
            [
                'key' => 'standard',
                'points' => 300,
                'price' => 270000,
                'discount' => 10,
                'popular' => true,
            ],
            [
                'key' => 'premium',
                'points' => 500,
                'price' => 400000,
                'discount' => 20,
            ],
            [
                'key' => 'ultimate',
                'points' => 1000,
                'price' => 700000,
                'discount' => 30,
            ],
        ];
        foreach ($pointPackages as $pointPackage) {
            PointPackage::updateOrCreate(['key' => $pointPackage['key']], $pointPackage);
        }
    }
}
