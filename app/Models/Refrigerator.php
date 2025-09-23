<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Refrigerator extends Model
{
    protected $fillable = [
        'name',
        'establishment_id',
    ];

    public function establishment() : BelongsTo {
        return $this->BelongsTo(Establishment::class);
    }

    public function closingLogs() : HasMany {
        return $this->HasMany(ClosingLog::class);
    }

}
