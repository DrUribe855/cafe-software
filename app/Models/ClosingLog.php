<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Refrigerator; 

class ClosingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'establishment_id',
        'user_id',
        'refrigerator_id',
        'schedule',   
        'image_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   // public function refrigerator()
    //{
   //     return $this->belongsTo(Refrigerator::class);
   // }
}