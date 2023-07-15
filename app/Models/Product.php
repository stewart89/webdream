<?php

namespace App\Models;

use App\Models\Brand;
use App\Models\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_number',
        'name',
        'price',
        'brand_id',
        'type'
    ];

    protected $table = 'products';

    public static function boot(){
        parent::boot();
        static::creating(function($model){
            $model->forceFill(['type' => static::class]);
        });
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function storages()
    {
        return $this->belongsToMany(Storage::class, 'product_storage', 'product_id', 'storage_id')->withPivot('quantity');
    }

    public function getSum()
    {
        return $this->storages()->sum('product_storage.quantity');
    }
}
