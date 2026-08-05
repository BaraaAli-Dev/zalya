<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_name',
        'price',
        'size',
        'description',
        'category_id',
        'stock',
        'slug',
        'gender',
        'images',
        'is_best_seller',
        'is_featured',
    ];

    protected $casts = [
        'images' => 'array',
        'is_best_seller' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
