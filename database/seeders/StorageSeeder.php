<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\Storage;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StorageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $faker = Factory::create();
        $storages = [
            [
                'name' => $faker->name(10) . ' Storage',
                'address' => $faker->address(),
                'capacity' => 20
            ],
            [
                'name' => $faker->name(10) . ' Storage',
                'address' => $faker->address(),
                'capacity' => 20
            ],
            [
                'name' => $faker->name(10) . ' Storage',
                'address' => $faker->address(),
                'capacity' => 20
            ],
        ];

        foreach ($storages as $storage) {
            Storage::create($storage);
        }
    }
}
