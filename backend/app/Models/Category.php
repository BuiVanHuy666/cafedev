<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Psy\Util\Str;

#[Fillable(['name', 'slug', 'display_order', 'is_active'])]
class Category extends Model
{
    use SoftDeletes;

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('public_categories'));
        static::deleted(fn () => Cache::forget('public_categories'));
        static::restored(fn () => Cache::forget('public_categories'));
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')->orderBy('id', 'desc');
    }
}
