<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Option;
use App\Models\OptionGroup;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $sizeAttr = Attribute::create(['name' => 'Kích thước']);

        $sizes = ['Size S', 'Size M', 'Size L'];
        foreach ($sizes as $index => $size) {
            AttributeValue::create([
                'attribute_id' => $sizeAttr->id,
                'value' => $size,
                'display_order' => $index + 1,
            ]);
        }

        $kemGroup = OptionGroup::create(['name' => 'Vị Kem']);
        $kemOptions = ['Dâu', 'Dừa', 'Chocolate', 'Vanila', 'Matcha', 'Sầu Riêng'];

        foreach ($kemOptions as $index => $kem) {
            Option::create([
                'option_group_id' => $kemGroup->id,
                'name' => $kem,
                'price_adjustment' => 0,
                'display_order' => $index + 1,
                'is_active' => true,
            ]);
        }

        $hinhThucGroup = OptionGroup::create(['name' => 'Hình thức']);
        $hinhThucOptions = ['Nóng', 'Đá'];

        foreach ($hinhThucOptions as $index => $ht) {
            Option::create([
                'option_group_id' => $hinhThucGroup->id,
                'name' => $ht,
                'price_adjustment' => 0,
                'display_order' => $index + 1,
                'is_active' => true,
            ]);
        }

        $toppingGroup = OptionGroup::create(['name' => 'Topping']);
        $toppings = [
            ['name' => 'Trân châu đen', 'price' => 5000],
            ['name' => 'Trân châu trắng', 'price' => 7000],
            ['name' => 'Thạch phô mai', 'price' => 10000],
            ['name' => 'Pudding trứng', 'price' => 10000],
        ];

        foreach ($toppings as $index => $top) {
            Option::create([
                'option_group_id' => $toppingGroup->id,
                'name' => $top['name'],
                'price_adjustment' => $top['price'],
                'display_order' => $index + 1,
                'is_active' => true,
            ]);
        }

        $sugarGroup = OptionGroup::create(['name' => 'Lượng Đường']);
        $sugars = ['100% Đường', '70% Đường', '50% Đường', '30% Đường', 'Không đường'];

        foreach ($sugars as $index => $sugar) {
            Option::create([
                'option_group_id' => $sugarGroup->id,
                'name' => $sugar,
                'price_adjustment' => 0,
                'display_order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }
}
