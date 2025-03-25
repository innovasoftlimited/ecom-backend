<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size_attribute_id',
        'color_attribute_id',
        'sku',
        'unit_price',
        'quantity',
        'image',
    ];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the size attribute of the product detail.
     *
     * @return BelongsTo
     */
    public function size(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class, 'size_attribute_id', 'id')
            ->where('type', 'Size');
    }

/**
 * Get the color attribute of the product detail.
 *
 * @return BelongsTo
 */
    public function color(): BelongsTo
    {
        return $this->belongsTo(ProductAttribute::class, 'color_attribute_id', 'id')
            ->where('type', 'Color');
    }

}
