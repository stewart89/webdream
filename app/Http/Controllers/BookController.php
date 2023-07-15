<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use Faker\Factory;
use App\Models\Car;
use App\Models\Book;
use App\Models\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends ProductController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Book::with('storages')->with('brand')->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {

        try {
            $quantity = $request->quantity;
            $this->checkIfEnoughtSpaceInStorage($quantity);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Not enough storage space'], 400);
        }

        $book = Book::create([
            'product_number' => $request->product_number,
            'name' => $request->name,
            'price' => $request->price,
            'brand_id' => $request->brand_id,
            'isbn' => $request->isbn,
        ]);

        $storage = $this->checkIfCanBeStoredInASingleStorage($book, $quantity);
        if(!$storage){
            $this->storePoductIntoMultipleStorages($book, $quantity);
        }

        return response()->json(['success' => 'Product saved successfully'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function decreaseProductQuantity(Request $request)
    {

        $book = Book::find($request->id);
        if(!$book){
            return response()->json(['error' => 'Product not found'], 404);
        }

        if($book->getSum() < $request->quantity){
            return response()->json(['error' => 'Not enough quantity in the storage'], 400);
        }

        $this->decreaseQuantity($book, $request->quantity);
        return response()->json(['success' => 'Product quantity decreased successfully'], 200);
    }
}
