<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'long_description',
        'category_id',
        'base_price',
        'sale_price',
        'sku',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'base_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('display_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getPriceAttribute()
    {
        return $this->sale_price ?? $this->base_price;
    }

    public function isOnSale()
    {
        return !is_null($this->sale_price) && $this->sale_price < $this->base_price;
    }

    public function discountPercentage()
    {
        if (!$this->isOnSale()) {
            return 0;
        }
        return round((($this->base_price - $this->sale_price) / $this->base_price) * 100);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved')->latest();
    }

    public function averageRating(): float
    {
        $avg = $this->approvedReviews()->avg('rating');
        return $avg ? round((float)$avg, 1) : 5.0;
    }

    public function reviewsCount(): int
    {
        return $this->approvedReviews()->count();
    }

    public function ratingBreakdown(): array
    {
        $total = $this->reviewsCount();
        $counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];

        if ($total > 0) {
            $ratings = $this->approvedReviews()->selectRaw('rating, count(*) as count')->groupBy('rating')->pluck('count', 'rating')->toArray();
            foreach ($ratings as $star => $count) {
                if (isset($counts[$star])) {
                    $counts[$star] = $count;
                }
            }
        }

        $percentages = [];
        foreach ($counts as $star => $count) {
            $percentages[$star] = [
                'count' => $count,
                'percentage' => $total > 0 ? round(($count / $total) * 100) : 0,
            ];
        }

        return $percentages;
    }
}
