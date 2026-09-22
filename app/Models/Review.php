<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'customer_name',
        'customer_email',
        'rating',
        'title',
        'description',
        'status',
        'is_featured',
        'verified_purchase',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_featured' => 'boolean',
        'verified_purchase' => 'boolean',
    ];

    protected $appends = [
        'initials',
        'formatted_date',
    ];

    /**
     * Relationship to Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope for approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for pending reviews
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for featured reviews
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get customer initials for avatar display
     */
    public function getInitialsAttribute(): string
    {
        $words = preg_split('/\s+/', trim($this->customer_name));
        $initials = '';
        foreach ($words as $w) {
            if (!empty($w)) {
                $initials .= mb_strtoupper(mb_substr($w, 0, 1));
            }
            if (mb_strlen($initials) >= 2) break;
        }
        return $initials ?: 'C';
    }

    /**
     * Get formatted human date
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('M d, Y') : 'Recent';
    }

    /**
     * Status badge styling for admin
     */
    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'approved' => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20',
            'pending' => 'bg-amber-50 text-amber-800 ring-1 ring-inset ring-amber-600/20',
            'rejected' => 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-600/10',
            default => 'bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-500/10',
        };
    }
}
