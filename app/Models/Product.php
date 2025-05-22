<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'stock_quantity'];



    public function notes()
    {
        return $this->morphOne(Note::class, 'notable');
    }


}
