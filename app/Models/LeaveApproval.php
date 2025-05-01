<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveApproval extends Model
{
    protected $fillable = [
        'leave_id',
        'user_id',
        'notes',
        'status',
    ];

    Public function leave(): BelongsTo
    {
        return $this->belongsTo(Leave::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
