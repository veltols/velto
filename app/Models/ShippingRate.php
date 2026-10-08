<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    use HasFactory;

    protected $table = 'shipping_rates';

    protected $fillable = [
        'name',
        'rate',
        'min_order_amount',
        'is_active',
        'is_default',
        'cities',
        'notes',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function getFormattedRateAttribute(): string
    {
        if ((float) $this->rate <= 0) {
            return 'FREE';
        }
        return 'Rs. ' . number_format($this->rate, 0);
    }

    public function getCitiesArrayAttribute(): array
    {
        if (empty($this->cities)) {
            return [];
        }
        return array_values(array_filter(array_map('trim', explode(',', $this->cities))));
    }

    public function matchesCity(?string $city): bool
    {
        if (empty($this->cities) || empty(trim($this->cities))) {
            return true; // Applies nationwide
        }

        if (empty($city)) {
            return false;
        }

        $cityClean = strtolower(trim($city));
        foreach ($this->cities_array as $targetCity) {
            if (strtolower($targetCity) === $cityClean || str_contains($cityClean, strtolower($targetCity))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Resolve effective cost for this rate given a subtotal.
     */
    public function getEffectiveRate(float $subtotal = 0): float
    {
        if ($this->min_order_amount !== null && (float) $this->min_order_amount > 0 && $subtotal >= (float) $this->min_order_amount) {
            return 0.0;
        }
        return (float) $this->rate;
    }

    /**
     * Find best matching shipping rate and cost for a given city and subtotal.
     */
    public static function resolveRate(?string $city = null, float $subtotal = 0): array
    {
        $activeRates = self::active()->get();

        if ($activeRates->isEmpty()) {
            return [
                'rate_id' => null,
                'name' => 'Standard Delivery',
                'cost' => 200.0,
                'is_free' => false,
                'notes' => 'Flat rate standard delivery',
            ];
        }

        // 1. Check if any active rate matches city specifically (city not empty)
        $cityMatch = null;
        if (!empty($city)) {
            $cityMatch = $activeRates->first(function ($rate) use ($city) {
                return !empty($rate->cities) && $rate->matchesCity($city);
            });
        }

        // 2. Fallback to default rate or nationwide rate
        $selectedRate = $cityMatch 
            ?: $activeRates->firstWhere('is_default', true) 
            ?: $activeRates->first(fn($rate) => empty($rate->cities))
            ?: $activeRates->first();

        $cost = $selectedRate->getEffectiveRate($subtotal);

        return [
            'rate_id' => $selectedRate->id,
            'name' => $selectedRate->name,
            'cost' => $cost,
            'is_free' => $cost <= 0,
            'notes' => $selectedRate->notes,
            'rate_model' => $selectedRate,
        ];
    }
}
