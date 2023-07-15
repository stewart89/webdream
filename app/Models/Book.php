<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Product
{

    public static function boot(){
        parent::boot();
        static::creating(function($book){
            $book->forceFill(['isbn' => $book->isbn]);
        });
    }
}
