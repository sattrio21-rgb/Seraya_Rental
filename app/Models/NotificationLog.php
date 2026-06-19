<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationLog extends Model
{
    protected $fillable = ['user_id', 'type', 'title', 'message', 'data', 'is_read', 'read_at'];

    protected $casts = ['data' => 'array', 'is_read' => 'boolean', 'read_at' => 'datetime'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }
}
