<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\AttributeValue;

class AttributeService
{
    public function getAllAttributes()
    {
        return Attribute::withCount('values')->orderBy('created_at', 'desc')->get();
    }

    public function createAttribute(string $name)
    {
        return Attribute::create(['name' => $name]);
    }

    public function deleteAttribute(int $id): void
    {
        Attribute::destroy($id);
    }

    public function getValuesByAttribute(int $attributeId)
    {
        return AttributeValue::where('attribute_id', $attributeId)
            ->orderBy('display_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    public function createAttributeValue(int $attributeId, string $value): AttributeValue
    {
        $maxOrder = AttributeValue::where('attribute_id', $attributeId)->max('display_order') ?? 0;

        return AttributeValue::create([
            'attribute_id' => $attributeId,
            'value' => $value,
            'display_order' => $maxOrder + 1,
        ]);
    }

    public function updateAttributeValue(int $id, string $value): void
    {
        $attributeValue = AttributeValue::find($id);
        if ($attributeValue) {
            $attributeValue->update(['value' => $value]);
        }
    }

    public function updateAttribute(int $id, string $name): void
    {
        $attribute = Attribute::find($id);
        if ($attribute) {
            $attribute->update(['name' => $name]);
        }
    }

    public function deleteAttributeValue(int $id): void
    {
        AttributeValue::destroy($id);
    }

    public function moveValue(int $id, string $direction): void
    {
        $current = AttributeValue::find($id);
        if (! $current) {
            return;
        }

        $operator = $direction === 'up' ? '<' : '>';
        $order = $direction === 'up' ? 'desc' : 'asc';

        $adjacent = AttributeValue::where('attribute_id', $current->attribute_id)
            ->where('display_order', $operator, $current->display_order)
            ->orderBy('display_order', $order)
            ->first();

        if ($adjacent) {
            // Hoán đổi display_order
            $tempOrder = $current->display_order;
            $current->update(['display_order' => $adjacent->display_order]);
            $adjacent->update(['display_order' => $tempOrder]);
        }
    }
}
