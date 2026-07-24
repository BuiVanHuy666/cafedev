<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]
class OptionGroup extends Model
{
    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'products_option_groups')
            ->withPivot('is_required', 'is_multiple', 'display_order', 'max_select')
            ->withTimestamps();
    }
}
