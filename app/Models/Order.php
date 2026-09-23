<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_number',
        'customer_name',
        'email',
        'phone',
        'shipping_address',
        'city',
        'postal_code',
        'subtotal',
        'shipping_cost',
        'total',
        'status',
        'payment_method',
        'payment_status',
        'advance_amount',
        'admin_notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'advance_amount' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::updating(function ($order) {
            if ($order->isDirty('status')) {
                $oldStatus = $order->getOriginal('status');
                $newStatus = $order->status;

                // When order is cancelled, restock variant quantities
                if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled') {
                    $order->restockItems();
                }

                // If a cancelled order is reopened, deduct stock again
                if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                    $order->reduceItemsStock();
                }
            }
        });

        static::deleted(function ($order) {
            // If deleted while not cancelled, restock items
            if ($order->status !== 'cancelled') {
                $order->restockItems();
            }
        });
    }

    public function restockItems()
    {
        $this->loadMissing('items.variant');
        foreach ($this->items as $item) {
            if ($item->variant) {
                $item->variant->increment('stock_quantity', $item->quantity);
            }
        }
    }

    public function reduceItemsStock()
    {
        $this->loadMissing('items.variant');
        foreach ($this->items as $item) {
            if ($item->variant) {
                $item->variant->decrement('stock_quantity', $item->quantity);
            }
        }
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
