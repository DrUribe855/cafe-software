<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'tag',
        'custom_tags',
        'image_url',
        'recommended'
    ];

    protected $appends = ['image_full_url'];

    protected $casts = [
        'recommended' => 'boolean',
    ];

    public function getImageFullUrlAttribute()
    {
        if (!$this->image_url) {
            return null;
        }

        return asset('storage/' . $this->image_url);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
