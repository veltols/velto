<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'size',
        'color',
        'sku',
        'price',
        'sale_price',
        'stock_quantity',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getFinalPriceAttribute()
    {
        $basePrice = $this->price ?? $this->product->base_price;
        $salePrice = $this->product->sale_price;

        // Apply product sale price ONLY if it's actually lower than the variant's base price
        if ($salePrice && $salePrice < $basePrice) {
            return $salePrice;
        }

        return $basePrice;
    }

    public function isOnSale()
    {
        $basePrice = $this->price ?? $this->product->base_price;
        $salePrice = $this->product->sale_price;
        
        return !is_null($salePrice) && $salePrice < $basePrice;
    }
}
