<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    protected $fillable = [
        'order_code',
        'competition_id',
        'email',
        'phone',
        'participant_count',
        'total_amount',
        'status',
        'participants',
        'tickets',
        'paid_at',
    ];

    protected $casts = [
        'participants'     => 'array',
        'tickets'          => 'array',
        'total_amount'     => 'integer',
        'participant_count'=> 'integer',
        'paid_at'          => 'datetime',
    ];

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    /** Sudah lunas */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
