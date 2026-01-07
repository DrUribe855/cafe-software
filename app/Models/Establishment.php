<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Establishment extends Model
{
    use HasFactory;

    /* Obtiene los usuarios asociados al establecimiento */
    public function user(): HasMany{
        return $this->HasMany(User::class);
    }

    public function refrigerator(): HasOne{
        return $this->HasOne(Refrigerator::class);
    }

}
