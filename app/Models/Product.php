<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'image_url',
        'price',
        'sold',
        'is_active',
        'is_preorder',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sold' => 'integer',
        'is_active' => 'boolean',
        'is_preorder' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the cart items for this product.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
} 