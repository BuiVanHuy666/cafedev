<?php

namespace App\Services;

use App\Models\Option;
use App\Models\OptionGroup;
use Illuminate\Database\Eloquent\Collection;

class OptionService
{
    public function getAllGroups(): Collection
    {
        return OptionGroup::withCount('options')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function createGroup(string $name): OptionGroup
    {
        return OptionGroup::create(['name' => $name]);
    }

    public function deleteGroup(int $id): void
    {
        OptionGroup::destroy($id);
    }

    public function updateOption(int $id, string $name, float $priceAdjustment): void
    {
        $option = Option::find($id);
        if ($option) {
            $option->update([
                'name' => $name,
                'price_adjustment' => $priceAdjustment,
            ]);
        }
    }

    public function updateGroup(int $id, string $name): void
    {
        $group = OptionGroup::find($id);
        if ($group) {
            $group->update(['name' => $name]);
        }
    }

    public function getOptionsByGroup(int $groupId): Collection
    {
        return Option::where('option_group_id', $groupId)
            ->orderBy('display_order', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }

    public function createOption(int $groupId, string $name, float $priceAdjustment = 0): Option
    {
        $maxOrder = Option::where('option_group_id', $groupId)->max('display_order') ?? 0;

        return Option::create([
            'option_group_id' => $groupId,
            'name' => $name,
            'price_adjustment' => $priceAdjustment,
            'display_order' => $maxOrder + 1,
        ]);
    }

    public function deleteOption(int $id): void
    {
        Option::destroy($id);
    }

    public function toggleActive(int $id): void
    {
        $option = Option::find($id);
        if ($option) {
            $option->update(['is_active' => !$option->is_active]);
        }
    }

    public function moveOption(int $id, string $direction): void
    {
        $current = Option::find($id);
        if (! $current) {
            return;
        }

        $operator = $direction === 'up' ? '<' : '>';
        $order = $direction === 'up' ? 'desc' : 'asc';

        $adjacent = Option::where('option_group_id', $current->option_group_id)
            ->where('display_order', $operator, $current->display_order)
            ->orderBy('display_order', $order)
            ->first();

        if ($adjacent) {
            $tempOrder = $current->display_order;

            $current->update(['display_order' => $adjacent->display_order]);
            $adjacent->update(['display_order' => $tempOrder]);
        }
    }
}
