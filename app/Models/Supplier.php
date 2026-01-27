<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'name'
    ];

    /* Relación al modelo de logs de proveedor */
    public function supplierLogs(): HasMany {
        return $this->HasMany(SupplierLog::class);
    }

}
