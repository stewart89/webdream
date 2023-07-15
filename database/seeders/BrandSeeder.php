<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['name' => 'Brand 1', 'quality' => 10],
            ['name' => 'Brand 2', 'quality' => 5],
            ['name' => 'Brand 3', 'quality' => 7],
            ['name' => 'Brand 4', 'quality' => 8],
            ['name' => 'Brand 5', 'quality' => 2],
        ];

        foreach ($brands as $brandData) {
            Brand::create($brandData);
        }
    }
}
