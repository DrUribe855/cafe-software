<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Refrigerator extends Model
{
    protected $fillable = [
        'name',
        'establishment_id',
    ];

    public function establishment(): BelongsTo
    {
        return $this->belongsTo(Establishment::class);
    }

    public function closingLogs(): HasMany
    {
        return $this->hasMany(ClosingLog::class);
    }
}
