<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CategoryService
{
    /**
     * Create a new category.
     */
    public function createCategory(array $data): Category
    {
        $maxOrder = Category::max('display_order') ?? 0;

        return Category::create([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'is_active' => $data['is_active'] ?? true,
            'display_order' => $maxOrder + 1,
        ]);
    }

    /**
     * Update an existing category.
     */
    public function updateCategory(Category|Model $category, array $data): bool
    {
        return $category->update([
            'name' => $data['name'],
            'slug' => $data['slug'],
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    /**
     * Soft delete a category.
     */
    public function deleteCategory(Category $category): void
    {
        $category->delete();
    }

    /**
     * Restore a soft-deleted category.
     */
    public function restoreCategory(Category $category): void
    {
        $category->restore();
    }

    /**
     * Permanently delete a category.
     */
    public function forceDeleteCategory(Category $category): void
    {
        $category->forceDelete();
    }
}
