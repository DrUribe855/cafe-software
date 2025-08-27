<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;



class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];


    /*  Relación del usuario con el establecimiento
        indicando que la tabla de usuarios es la que tiene
        la llave foranea
    */

    public function establishment(): BelongsTo {
        return $this->BelongsTo(Establishment::class);
    }

    /* Relación con modelo de logs de cierre */

    public function closingLog(): HasMany {
        return $this->HasMany(ClosingLog::class);
    }

    /* Relación con modelo de logs de bolleria */

    public function pastrieLogs(): HasMany {
        return $this->HasMany(PastrieLogs::class);
    }




}
