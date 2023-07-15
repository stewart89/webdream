<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Storage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Checks if the product can be stored at all
     *
     * @param  int $quantity
     * @return void
     */
    protected function checkIfEnoughtSpaceInStorage(int $quantity): void
    {
        $totalRemainingSpace = Storage::getTotalRemainingSpace();
        if($totalRemainingSpace < $quantity){
            throw new \Exception('Not enough space');
        }
    }

    /**
     * Check if the product can be stored in a single storage, and store it if it can
     *
     * @param  Product $product
     * @param  int $quantity
     * @return Storage
     */
    protected function checkIfCanBeStoredInASingleStorage(Product $product, int $quantity): bool{

        $storages = Storage::all();
        foreach ($storages as $storage) {
            if ($storage->getRemainingSpace() >= $quantity) {
                $this->storeProduct($product, $storage->id, $quantity);
                return true;
            }
        }

        return false;
    }

    /**
     * Store product in multiple storages
     *
     * @param  Product $product
     * @param  int $quantity
     * @return void
     */
    protected function storePoductIntoMultipleStorages(Product $product, int $quantity)
    {
        $storages = Storage::all();
        foreach ($storages as $storage) {
            if ($storage->getRemainingSpace() > 0) {
                $remainingSpace = $storage->getRemainingSpace();
                if ($remainingSpace >= $quantity) {
                    $this->storeProduct($product, $storage->id, $quantity);
                    return true;
                } else {
                    $this->storeProduct($product, $storage->id, $remainingSpace);
                    $quantity -= $remainingSpace;
                }
            }
        }
    }

    /**
     * Store the product
     *
     * @param  Collection $product
     * @param  int $storageId
     * @param  int $quantity
     * @return void
     */
    protected function storeProduct(Product $product, int $storageId, int $quantity)
    {
        $product = Product::find($product->id);
        $product->storages()->attach($storageId, ['quantity' => $quantity]);
    }

    /**
     * Decrease the quantity of the product
     *
     * @param  mixed $product
     * @param  mixed $quantity
     * @return void
     */
    protected function decreaseQuantity(Product $product, int $quantity)
    {
        $storages = $product->storages;
        foreach ($storages as $storage) {
            $pivot = $storage->pivot;
            $existingQuantity = $pivot->quantity;

            if ($existingQuantity >= $quantity) {

                $storage->pivot->decrement('quantity', $quantity);
                $quantity = 0;
                break;
            } else {

                $storage->pivot->decrement('quantity', $existingQuantity);
                $quantity -= $existingQuantity;
            }
        }

        if ($quantity > 0) {
            throw new \Exception('Not enough quantity');
        }

    }

}
