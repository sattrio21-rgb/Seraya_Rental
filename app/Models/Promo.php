<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promo extends Model
{
    protected $fillable = [
        'code', 'name', 'description', 'discount_type', 'discount_value',
        'min_booking_amount', 'max_discount_amount', 'max_uses', 'used_count',
        'valid_from', 'valid_until', 'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_booking_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
    ];

    public function usages(): HasMany { return $this->hasMany(PromoUsage::class); }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeValid(Builder $query): Builder
    {
        return $query->where('valid_from', '<=', now())->where('valid_until', '>=', now());
    }

    public function calculateDiscount(float $amount): float
    {
        if ($this->discount_type === 'percentage') {
            $discount = $amount * ($this->discount_value / 100);
            return $this->max_discount_amount ? min($discount, $this->max_discount_amount) : $discount;
        }
        return min($this->discount_value, $amount);
    }
}
