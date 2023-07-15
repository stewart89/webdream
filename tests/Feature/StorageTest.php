<?php

namespace Tests\Feature;

use Faker\Factory;
use Tests\TestCase;
use App\Models\Storage;
use Database\Seeders\BrandSeeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\StorageSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StorageTest extends TestCase
{
    //use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_storage_get_total_capacity(){

        $this->seed(StorageSeeder::class);
        $totalSpace = Storage::getTotalSpace();
        $this->assertEquals(60, $totalSpace);
    }

    public function test_storage_total_remaining_space(){

        $this->seed(StorageSeeder::class);
        $this->seed(BrandSeeder::class);

        $data = $this->getProductArray();
        $data['quantity'] = 55;
        $response = $this->post('/book', $data);

        $response->assertStatus(200);

        $remainingSpace = Storage::getTotalRemainingSpace();
        $this->assertEquals(5, $remainingSpace);
    }

    private function getProductArray(): array{

        $faker = Factory::create();
        $brandIds = DB::table('brands')->pluck('id');
        return [
            'product_number' => $faker->unique()->numberBetween(100000, 999999),
            'name' => $faker->name(10),
            'price' => $faker->numberBetween(10000, 30000),
            'brand_id' => $faker->randomElement($brandIds),
            'isbn' => $faker->unique()->numberBetween(1000000000000, 9999999999999),
            'quantity' => $faker->numberBetween(5, 10)
        ];
    }
}
