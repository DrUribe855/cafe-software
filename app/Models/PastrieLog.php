<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PastrieLog extends Model
{
    /* Relación al modelo de usuario */
    public function user() : BelongsTo {
        return $this->BelongsTo(User::class);
    }
}
