<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'car_id', 'booking_code', 'start_date', 'end_date',
        'pickup_location', 'return_location', 'rental_type', 'total_price',
        'deposit_amount', 'discount_amount', 'promo_id', 'status',
        'notes', 'cancelled_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_price' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function promo(): BelongsTo
    {
        return $this->belongsTo(Promo::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function deposit(): HasOne
    {
        return $this->hasOne(Deposit::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function damageRecords(): HasMany
    {
        return $this->hasMany(DamageRecord::class);
    }

    public function handoverChecklists(): HasMany
    {
        return $this->hasMany(HandoverChecklist::class);
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('start_date', '>=', now())->where('status', '!=', 'cancelled');
    }

    public function getDurationInDays(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'active' => 'success',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'info',
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'active' => 'Aktif',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }
}
