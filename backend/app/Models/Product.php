<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'slug', 'image', 'description', 'base_price', 'original_price', 'is_active', 'is_featured', 'price'])]
class Product extends Model
{
    use SoftDeletes;

    public const IMAGE_PATH = 'products';

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function optionGroups(): BelongsToMany
    {
        return $this
            ->belongsToMany(OptionGroup::class, table: 'products_option_groups')
            ->withPivot('is_required', 'is_multiple', 'display_order', 'max_select')
            ->withTimestamps();
    }

    public function productsOptionGroups(): HasMany
    {
        return $this->hasMany(ProductOptionGroup::class, 'product_id');
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? (str_contains($value, '/') ? $value : "products/{$value}") : null,
            set: fn (?string $value) => $value ? basename($value) : null,
        );
    }
}
