<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DamageRecord extends Model
{
    protected $fillable = ['booking_id', 'car_id', 'description', 'photos', 'severity', 'estimated_cost', 'recorded_by'];

    protected $casts = ['photos' => 'array', 'estimated_cost' => 'decimal:2'];

    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
    public function car(): BelongsTo { return $this->belongsTo(Car::class); }
    public function recorder(): BelongsTo { return $this->belongsTo(User::class, 'recorded_by'); }
}
