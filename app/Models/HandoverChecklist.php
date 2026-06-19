<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HandoverChecklist extends Model
{
    protected $fillable = ['booking_id', 'car_id', 'checklist_data', 'handover_type', 'performed_by', 'notes', 'photos'];

    protected $casts = ['checklist_data' => 'array', 'photos' => 'array'];

    public function booking(): BelongsTo { return $this->belongsTo(Booking::class); }
    public function car(): BelongsTo { return $this->belongsTo(Car::class); }
    public function performer(): BelongsTo { return $this->belongsTo(User::class, 'performed_by'); }
}
