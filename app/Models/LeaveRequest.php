<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class LeaveRequest extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'start_date',
        'end_date',
        'reason',
        'status',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo{
        return $this->belongsTo(User::class, 'approved_by');
    }
}
