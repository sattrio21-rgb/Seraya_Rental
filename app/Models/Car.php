<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Builder;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'brand', 'model', 'year', 'plate_number', 'color',
        'capacity', 'transmission', 'fuel_type', 'price_per_day',
        'price_with_driver', 'image', 'status', 'description',
        'features', 'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'price_per_day' => 'decimal:2',
        'price_with_driver' => 'decimal:2',
        'is_active' => 'boolean',
        'year' => 'integer',
        'capacity' => 'integer',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(CarImage::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function damageRecords(): HasMany
    {
        return $this->hasMany(DamageRecord::class);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', 'available');
    }

    public function scopeByBrand(Builder $query, string $brand): Builder
    {
        return $query->where('brand', $brand);
    }

    public function scopeByTransmission(Builder $query, string $transmission): Builder
    {
        return $query->where('transmission', $transmission);
    }

    public function getAverageRating(): float
    {
        return $this->reviews()->where('is_approved', true)->avg('rating') ?? 0;
    }

    public function getReviewCount(): int
    {
        return $this->reviews()->where('is_approved', true)->count();
    }
}
