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

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_add_multiple_products(): void{

        /** We make sure there is enough space for the product, so add the storages
         * The brand needs for products
         */
        $this->seed(BrandSeeder::class);
        $this->seed(StorageSeeder::class);

        $data = $this->getProductArray();
        $response = $this->post('/book', $data);

        $response->assertStatus(200)
        ->assertJson([
            'success' => 'Product saved successfully',
        ]);

        $data = $this->getProductArray();
        $response = $this->post('/book', $data);

        $response->assertStatus(200)
        ->assertJson([
            'success' => 'Product saved successfully',
        ]);
    }

    public function test_add_product_has_more_quantity_than_storage(): void{

        $this->seed(BrandSeeder::class);
        $this->seed(StorageSeeder::class);

        $data = $this->getProductArray();
        $data['quantity'] = 10000;
        $response = $this->post('/book', $data);

        $response->assertStatus(400)
        ->assertJson([
            'error' => 'Not enough storage space',
        ]);
    }

    public function test_return_a_products_with_multiple_storage(): void{

        $this->seed(BrandSeeder::class);
        $this->seed(StorageSeeder::class);

        /** We have 20 space in the storages so 30 will be split into 2 storages */
        $data = $this->getProductArray();
        $data['quantity'] = 30;
        $this->post('/book', $data);

        $response = $this->get('/book');

        $response->assertStatus(200)
            ->assertJsonCount(2, '0.storages');
    }

    public function test_decrease_product_quantity_in_one_storage(): void{

        $this->seed(BrandSeeder::class);
        $this->seed(StorageSeeder::class);

        /** 20 is the capacity */
        $data = $this->getProductArray();
        $data['quantity'] = 20;
        $this->post('/book', $data);

        $response = $this->get('/book');
        $response->assertStatus(200);

        $book = $response->json()[0];

        $this->post('/book-decrease', [
            'id' => $book['id'],
            'quantity' => 10
        ]);

        $response = $this->get('/book');
        $response->assertStatus(200);

        /** It should be 10 because we removed 10 */
        $response->assertStatus(200)
        ->assertJsonPath('0.storages.0.pivot.quantity', 10);
    }

    public function test_decrease_product_quantity_in_multiple_storage(): void{

        $this->seed(BrandSeeder::class);
        $this->seed(StorageSeeder::class);

        /** 20 is the capacity */
        $data = $this->getProductArray();
        $data['quantity'] = 30;
        $this->post('/book', $data);

        $response = $this->get('/book');
        $response->assertStatus(200);

        $book = $response->json()[0];

        $this->post('/book-decrease', [
            'id' => $book['id'],
            'quantity' => 25
        ]);

        $response = $this->get('/book');
        $response->assertStatus(200);

        /** It should be 0 in the first storage */
        $response->assertStatus(200)
        ->assertJsonPath('0.storages.0.pivot.quantity', 0);

        /** THe second should be 5 because 30-25 = 5 */
        $response->assertStatus(200)
        ->assertJsonPath('0.storages.1.pivot.quantity', 5);
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
