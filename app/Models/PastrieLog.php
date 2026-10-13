<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PastrieLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'establishment_id',
        'user_id',
        'schedule',
        'image_url'
    ];

    /* Relación al modelo de usuario */
    public function user() : BelongsTo {
        return $this->BelongsTo(User::class);
    }
}
