<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Bán chạy',
            'Cà phê',
            'Nước ép',
            'Sinh tố',
            'Cooktails',
            'Nhâm nhi',
            'Cơm trưa',
            'Pizza',
            'Soup',
        ];

        foreach ($categories as $index => $categoryName) {
            Category::updateOrCreate(
                ['slug' => Str::slug($categoryName)],

                [
                    'name' => $categoryName,
                    'display_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
