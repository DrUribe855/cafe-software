<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Refrigerator;

class ClosingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'establishment_id',
        'user_id',
        'refrigerator_id',
        'temperature',
        'image_url', 
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function refrigerator(): BelongsTo
    {
        return $this->belongsTo(Refrigerator::class);
    }   
}