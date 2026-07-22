<?php

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use App\Services\OptionService;
use Illuminate\Validation\Rule;

new class extends Component {
    public ?Collection $optionGroups;
    public ?int $activeGroupId = null;
    public ?Collection $options;

    public string $newGroupName = '';
    public string $newOptionName = '';
    public string $newOptionPrice = '';

    public ?int $editingGroupId = null;
    public string $editGroupName = '';

    public ?int $editingOptionId = null;
    public string $editOptionName = '';
    public string $editOptionPrice = '';

    public function mount(): void
    {
        $this->loadOptions();
    }

    public function loadOptions(): void
    {
        $this->optionGroups = app(OptionService::class)->getAllGroups();
        if (!$this->activeGroupId && $this->optionGroups->count() > 0) {
            $this->selectGroup($this->optionGroups->first()->id);
        } elseif ($this->activeGroupId) {
            $this->loadOptionDetails();
        }
    }

    public function selectGroup($id): void
    {
        $this->activeGroupId = $id;
        $this->editingOptionId = null;
        $this->editingGroupId = null;
        $this->loadOptionDetails();
    }

    public function loadOptionDetails(): void
    {
        if ($this->activeGroupId) {
            $this->options = app(OptionService::class)->getOptionsByGroup($this->activeGroupId);
        }
    }

    public function createGroup(): void
    {
        $this->validate(['newGroupName' => 'required|string|max:255']);
        $group = app(OptionService::class)->createGroup($this->newGroupName);
        $this->newGroupName = '';
        $this->activeGroupId = $group->id;
        $this->loadOptions();
    }

    public function updateGroup(): void
    {
        $this->validate(['editGroupName' => 'required|string|max:255']);
        app(OptionService::class)->updateGroup($this->editingGroupId, $this->editGroupName);
        $this->editingGroupId = null;
        $this->loadOptions();
    }

    public function deleteGroup($id): void
    {
        app(OptionService::class)->deleteGroup($id);
        if ($this->activeGroupId === $id) {
            $this->activeGroupId = null;
            $this->options = null;
        }
        $this->loadOptions();
    }

    public function createOption(): void
    {
        $this->validate([
            'newOptionName' => [
                'required', 'string', 'max:255',
                Rule::unique('options', 'name')->where('option_group_id', $this->activeGroupId)
            ],
            'newOptionPrice' => 'nullable|numeric|min:0'
        ], [
            'newOptionName.unique' => 'Tùy chọn này đã tồn tại trong nhóm này.'
        ]);

        if ($this->activeGroupId) {
            app(OptionService::class)->createOption($this->activeGroupId, $this->newOptionName, (float)$this->newOptionPrice ?: 0);
            $this->newOptionName = '';
            $this->newOptionPrice = '';
            $this->loadOptionDetails();
            $this->loadOptions();
        }
    }

    public function updateOption(): void
    {
        $this->validate([
            'editOptionName' => [
                'required', 'string', 'max:255',
                Rule::unique('options', 'name')
                    ->where('option_group_id', $this->activeGroupId)
                    ->ignore($this->editingOptionId)
            ],
            'editOptionPrice' => 'nullable|numeric|min:0'
        ], [
            'editOptionName.unique' => 'Tùy chọn này đã tồn tại trong nhóm này.'
        ]);

        if ($this->editingOptionId) {
            app(OptionService::class)->updateOption($this->editingOptionId, $this->editOptionName, (float)$this->editOptionPrice ?: 0);
            $this->editingOptionId = null;
            $this->loadOptionDetails();
        }
    }

    public function deleteOption($id): void
    {
        app(OptionService::class)->deleteOption($id);
        $this->loadOptionDetails();
        $this->loadOptions();
    }

    public function toggleOptionActive($id): void
    {
        app(OptionService::class)->toggleActive($id);
        $this->loadOptionDetails();
    }

    public function moveOption($id, $direction): void
    {
        app(OptionService::class)->moveOption($id, $direction);
        $this->loadOptionDetails();
    }
};
?>

<div class="space-y-6 animate-in fade-in slide-in-from-bottom-2 duration-300">
    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4 flex gap-4">
        <flux:icon name="sparkles" class="size-6 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5"/>
        <div>
            <h3 class="font-medium text-amber-900 dark:text-amber-300">Bản chất của Tùy chọn (Add-ons / Options)</h3>
            <p class="text-sm text-amber-700 dark:text-amber-400 mt-1">
                Dùng để bán kèm hoặc điều chỉnh khẩu vị. Khách hàng <strong>có thể chọn nhiều</strong> (Ví dụ: Vừa thêm Trân châu, vừa thêm Thạch).
                <br><em>Lưu ý: Bạn CẦN nhập giá phụ thu tại đây (Ví dụ: Thêm Trân châu +5.000đ). Giá này sẽ áp dụng chung cho mọi món ăn gắn topping này.</em>
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
        <div class="md:col-span-4 space-y-4">
            <form wire:submit="createGroup" class="flex gap-2">
                <flux:input wire:model="newGroupName" placeholder="Tên nhóm (VD: Topping)" class="flex-1" required/>
                <flux:button type="submit" variant="primary" icon="plus" class="px-3"/>
            </form>

            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden shadow-sm">
                @forelse($optionGroups as $group)
                    @if($editingGroupId === $group->id)
                        <div class="p-3 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50">
                            <flux:input wire:model="editGroupName" size="sm" required wire:keydown.enter="updateGroup"/>
                            <div class="flex justify-end gap-2 mt-2">
                                <flux:button wire:click="updateGroup" variant="primary" size="sm" icon="check"/>
                                <flux:button wire:click="$set('editingGroupId', null)" variant="subtle" size="sm" icon="x-mark"/>
                            </div>
                        </div>
                    @else
                        <div wire:click="selectGroup({{ $group->id }})"
                             class="flex justify-between items-center px-4 py-3 cursor-pointer border-b border-zinc-100 dark:border-zinc-800 {{ $activeGroupId === $group->id ? 'bg-amber-50 dark:bg-amber-900/20 border-l-4 border-l-amber-500' : 'border-l-4 border-l-transparent hover:bg-zinc-50 dark:hover:bg-zinc-800' }}">
                            <div>
                                <div class="font-medium {{ $activeGroupId === $group->id ? 'text-amber-600' : 'text-zinc-900 dark:text-white' }}">{{ $group->name }}</div>
                                <div class="text-xs text-zinc-500 mt-0.5">{{ $group->options_count ?? $group->options()->count() ?? 0 }} tùy chọn</div>
                            </div>
                            <div class="flex items-center gap-1">
                                <flux:button wire:click.stop="$set('editingGroupId', {{ $group->id }}); $set('editGroupName', '{{ addslashes($group->name) }}')" variant="subtle" size="sm" icon="pencil-square" class="text-zinc-400 hover:text-amber-500"/>
                                <flux:button wire:click.stop="deleteGroup({{ $group->id }})" wire:confirm="Xóa nhóm này sẽ xóa toàn bộ tùy chọn. Bạn chắc chắn chứ?" variant="subtle" size="sm" icon="trash" class="text-zinc-400 hover:text-red-500"/>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="p-6 text-center text-zinc-500 text-sm">Chưa có nhóm nào.</div>
                @endforelse
            </div>
        </div>

        <div class="md:col-span-8">
            @if($activeGroupId && $optionGroups->firstWhere('id', $activeGroupId))
                <flux:card class="space-y-6">
                    <flux:heading level="2" class="text-xl text-amber-600 dark:text-amber-400">
                        {{ $optionGroups->firstWhere('id', $activeGroupId)->name }}
                    </flux:heading>

                    <form wire:submit="createOption" class="flex gap-4 items-start bg-zinc-50 dark:bg-zinc-900/50 p-4 rounded-xl border border-zinc-100 dark:border-zinc-800">
                        <div class="flex-1">
                            <flux:input wire:model="newOptionName" label="Tên lựa chọn" placeholder="VD: Trân châu trắng" required/>
                            @error('newOptionName') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex-1">
                            <flux:input wire:model="newOptionPrice" type="number" label="Giá cộng thêm (VNĐ)" placeholder="VD: 5000"/>
                        </div>
                        <flux:button type="submit" variant="primary" class="mt-6">Thêm tùy chọn</flux:button>
                    </form>

                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>Thứ tự</flux:table.column>
                            <flux:table.column>Tên lựa chọn</flux:table.column>
                            <flux:table.column>Phụ thu</flux:table.column>
                            <flux:table.column>Trạng thái</flux:table.column>
                            <flux:table.column>Thao tác</flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @forelse($options as $index => $opt)
                                @if($editingOptionId === $opt->id)
                                    <flux:table.row>
                                        <flux:table.cell></flux:table.cell>
                                        <flux:table.cell>
                                            <flux:input wire:model="editOptionName" size="sm" class="w-full min-w-37.5" required/>
                                            @error('editOptionName') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                        </flux:table.cell>
                                        <flux:table.cell>
                                            <flux:input wire:model="editOptionPrice" type="number" size="sm" class="w-full" wire:keydown.enter="updateOption"/>
                                        </flux:table.cell>
                                        <flux:table.cell></flux:table.cell>
                                        <flux:table.cell>
                                            <div class="flex gap-2">
                                                <flux:button wire:click="updateOption" variant="primary" size="sm" icon="check"/>
                                                <flux:button wire:click="$set('editingOptionId', null)" variant="subtle" size="sm" icon="x-mark"/>
                                            </div>
                                        </flux:table.cell>
                                    </flux:table.row>
                                @else
                                    <flux:table.row>
                                        <flux:table.cell>
                                            <div class="flex flex-col gap-1 w-8">
                                                <button wire:click="moveOption({{ $opt->id }}, 'up')" class="text-zinc-400 hover:text-amber-600 disabled:opacity-30" {{ $index === 0 ? 'disabled' : '' }}>
                                                    <flux:icon name="chevron-up" class="size-4"/>
                                                </button>
                                                <button wire:click="moveOption({{ $opt->id }}, 'down')" class="text-zinc-400 hover:text-amber-600 disabled:opacity-30" {{ $index === count($options) - 1 ? 'disabled' : '' }}>
                                                    <flux:icon name="chevron-down" class="size-4"/>
                                                </button>
                                            </div>
                                        </flux:table.cell>
                                        <flux:table.cell class="font-medium {{ !$opt->is_active ? 'opacity-50 line-through text-zinc-400' : '' }}">
                                            {{ $opt->name }}
                                        </flux:table.cell>
                                        <flux:table.cell>
                                            <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 {{ !$opt->is_active ? 'opacity-50' : '' }}">
                                                +{{ number_format($opt->price_adjustment) }}đ
                                            </span>
                                        </flux:table.cell>
                                        <flux:table.cell>
                                            <div class="flex items-center gap-2">
                                                <flux:switch wire:click="toggleOptionActive({{ $opt->id }})" :checked="$opt->is_active" />
                                                <span class="text-xs font-medium {{ $opt->is_active ? 'text-green-600 dark:text-green-400' : 'text-zinc-400' }}">
                                                    {{ $opt->is_active ? 'Còn hàng' : 'Hết hàng' }}
                                                </span>
                                            </div>
                                        </flux:table.cell>
                                        <flux:table.cell>
                                            <div class="flex items-center gap-2">
                                                <flux:button wire:click="$set('editingOptionId', {{ $opt->id }}); $set('editOptionName', '{{ addslashes($opt->name) }}'); $set('editOptionPrice', {{ $opt->price_adjustment }})" variant="subtle" size="sm" icon="pencil-square"/>
                                                <flux:button wire:click="deleteOption({{ $opt->id }})" variant="danger" size="sm" icon="trash"/>
                                            </div>
                                        </flux:table.cell>
                                    </flux:table.row>
                                @endif
                            @empty
                                <flux:table.row>
                                    <flux:table.cell colspan="5" class="text-center text-zinc-500">Chưa có tùy chọn nào.</flux:table.cell>
                                </flux:table.row>
                            @endforelse
                        </flux:table.rows>
                    </flux:table>
                </flux:card>
            @endif
        </div>
    </div>
</div>
