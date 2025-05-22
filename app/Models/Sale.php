<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
   use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'sale_date',
        'subtotal',
        'discount_amount',
        'total_amount',
        
    ];

    protected $casts = [
        'sale_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

   
   public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

 
    public function notes()
    {
        return $this->morphOne(Note::class, 'notable');
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_amount, 2) . ' BDT';
    }


 
    public function scopeFilterByCustomer($query, $customerName)
    {
        return $query->whereHas('user', function ($q) use ($customerName) {
            $q->where('name', 'like', '%' . $customerName . '%');
        });
    }

    public function scopeFilterByProduct($query, $productName)
    {
        return $query->whereHas('saleItems.product', function ($q) use ($productName) {
            $q->where('name', 'like', '%' . $productName . '%');
        });
    }

    public function scopeFilterByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('sale_date', [$startDate, $endDate]);
    }
}


















