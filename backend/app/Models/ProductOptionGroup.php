<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['product_id', 'option_group_id', 'is_required', 'is_multiple', 'display_order', 'max_select'])]
class ProductOptionGroup extends Model
{
    protected $table = 'products_option_groups';

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function optionGroup(): BelongsTo
    {
        return $this->belongsTo(OptionGroup::class);
    }
}
