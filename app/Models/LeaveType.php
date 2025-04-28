<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    protected $fillable = [
        'name',
        'max_days',
    ];

    public function leaves():HasMany
    {
        return $this->hasMany(Leave::class);
    }
}
