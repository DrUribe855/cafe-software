<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;



class User extends Authenticatable
{
    use HasFactory, HasApiTokens, HasRoles;

    protected $fillable = [
        'document',
        'name',
        'email',
        'password',
        'establishment_id',
        'status'
    ];

    protected $hidden = [
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
        return $this->HasMany(PastrieLog::class);
    }

    /* Relación con modelo de solicitudes de permiso */

    public function leaveRequests(): HasMany {
        return $this->HasMany(LeaveRequest::class);
    }

    /* Relación con modelo de logs de proveedor */

    public function supplierLogs(): HasMany {
        return $this->HasMany(SupplierLog::class);
    }

    /* ==============================
       Autorización de 3 niveles (S1)
       ============================== */

    public function isSuperAdmin(): bool {
        return $this->hasRole('super_admin');
    }

    /* Un super_admin puede acceder a cualquier establecimiento; el resto
       (admin/employee) queda limitado a su propio establecimiento. */

    public function canAccessEstablishment($establishmentId): bool {
        return $this->isSuperAdmin() || (int) $this->establishment_id === (int) $establishmentId;
    }

}
