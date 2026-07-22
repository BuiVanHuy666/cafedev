<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'slug', 'image', 'description', 'base_price', 'original_price', 'is_active', 'is_featured'])]
class Product extends Model
{
    use SoftDeletes;

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
}
