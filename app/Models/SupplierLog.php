<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'establishment_id',
        'supplier_id',
        'user_id',
        'image_url'
    ];

    /* Relación al modelo de proveedor */
    public function supplier() : BelongsTo {
        return $this->BelongsTo(Supplier::class);
    }

    public function user() : BelongsTo {
        return $this->BelongsTo(User::class);
    }
}
