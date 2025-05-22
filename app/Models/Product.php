<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'stock_quantity'];



    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function notes()
    {
        return $this->morphOne(Note::class, 'notable');
    }


    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' BDT';
    }
}
