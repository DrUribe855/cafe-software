<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClosingLog extends Model
{
    /* Relación al modelo de usuario */
    public function user() : BelongsTo {
        return $this->BelongsTo(User::class);
    }

    public function refrigerator() : BelongsTo {
        return $this->BelongsTo(Refrigerator::class);
    }
}
