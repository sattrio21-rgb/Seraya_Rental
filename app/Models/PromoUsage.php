<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromoUsage extends Model
{
    protected $fillable = ['promo_id', 'user_id', 'booking_id'];

    public function promo(): BelongsTo { return $this->belongsTo(Promo::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
}
