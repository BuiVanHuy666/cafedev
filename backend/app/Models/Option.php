<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['option_group_id', 'name', 'price_adjustment', 'is_active', 'display_order'])]
class Option extends Model
{
    public function optionGroup(): BelongsTo
    {
        return $this->hasMany(Option::class)->orderBy('display_order');
    }
}
