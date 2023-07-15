<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Storage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'capacity',
    ];

    public function getRemainingSpace()
    {
        $usedSpace = $this->products()->sum('product_storage.quantity');
        $remainingSpace = $this->capacity - $usedSpace;

        return $remainingSpace >= 0 ? $remainingSpace : 0;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    public static function getTotalSpace()
    {
        return DB::table('storages')->sum('capacity');
    }

    public static function getTotalRemainingSpace()
    {
        $totalRemainingSpace = DB::table('storages')
            ->selectRaw('SUM(capacity - COALESCE((SELECT SUM(quantity) FROM product_storage WHERE product_storage.storage_id = storages.id), 0)) as total_remaining_space')
            ->value('total_remaining_space');

        return $totalRemainingSpace;
    }


}
