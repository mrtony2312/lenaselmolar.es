<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id', 'title', 'slug', 'category', 'ref', 'gtin', 'mpn', 'brand', 'google_product_category',
        'price', 'old_price', 'in_stock', 'is_active', 'merchant_excluded', 'merchant_exclusion_reason',
        'color', 'hover_image', 'images',
        'short_description', 'description',
        'unit_measure_value', 'unit_measure_unit', 'eprel_code',
    ];

    protected $casts = [
        'images' => 'array',
        'in_stock' => 'boolean',
        'is_active' => 'boolean',
        'merchant_excluded' => 'boolean',
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'unit_measure_value' => 'decimal:3',
    ];

    public function categoryModel()
    {
        return $this->belongsTo(Category::class, 'category', 'slug');
    }

    /**
     * Same shape as the legacy config/loja_products.php entries, so code that
     * still expects that array structure keeps working unchanged.
     */
    public function toCatalogArray(): array
    {
        return [
            'id' => (int) $this->id,
            'title' => $this->title,
            'hover_image' => $this->hover_image ?? '',
            'old_price' => $this->old_price !== null ? (string) $this->old_price : null,
            'price' => (string) $this->price,
            'category' => $this->category,
            'images' => $this->images ?? [],
            'in_stock' => (bool) $this->in_stock,
            'color' => $this->color ?? '',
            'short_description' => $this->short_description ?? '',
            'description' => $this->description ?? '',
            'ref' => $this->ref ?? '',
            'gtin' => $this->gtin ?? null,
            'mpn' => $this->mpn ?? null,
            'is_active' => $this->is_active ?? true,
            'merchant_excluded' => (bool) ($this->merchant_excluded ?? false),
            'merchant_exclusion_reason' => $this->merchant_exclusion_reason ?? null,
            'brand' => $this->brand ?? null,
            'google_product_category' => $this->google_product_category ?? null,
            'slug' => $this->slug,
            'unit_measure_value' => $this->unit_measure_value,
            'unit_measure_unit' => $this->unit_measure_unit,
            'eprel_code' => $this->eprel_code,
        ];
    }
}
