<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'description',
        'category_id',
        'slug',
        'gender',
        'images',
        'is_featured',
        'is_best_seller',
    ];

    protected $casts = [
        'images' => 'array',
        'is_featured' => 'boolean',
        'is_best_seller' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function getMinPriceAttribute()
    {
        return $this->variants->min('price');
    }

    public function getTotalStockAttribute()
    {
        return $this->variants->sum('stock');
    }
}
