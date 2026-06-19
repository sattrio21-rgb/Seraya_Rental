<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = ['user_id', 'type', 'file_path', 'status', 'verified_by', 'verified_at', 'rejection_reason'];

    protected $casts = ['verified_at' => 'datetime'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function verifier(): BelongsTo { return $this->belongsTo(User::class, 'verified_by'); }
}
