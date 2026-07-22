<?php

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use App\Services\AttributeService;
use Illuminate\Validation\Rule;

new class extends Component {
    public ?Collection $attributeGroups;
    public ?int $activeAttributeId = null;
    public ?Collection $attributeValues;

    public string $newAttributeName = '';
    public string $newAttributeValue = '';

    public ?int $editingAttributeId = null;
    public string $editAttributeName = '';

    public ?int $editingAttributeValueId = null;
    public string $editAttributeValueName = '';

    public function mount(): void
    {
        $this->loadAttributes();
    }

    public function loadAttributes(): void
    {
        $this->attributeGroups = app(AttributeService::class)->getAllAttributes();
        if (!$this->activeAttributeId && $this->attributeGroups->count() > 0) {
            $this->selectAttribute($this->attributeGroups->first()->id);
        } elseif ($this->activeAttributeId) {
            $this->loadAttributeValues();
        }
    }

    public function selectAttribute($id): void
    {
        $this->activeAttributeId = $id;
        $this->editingAttributeValueId = null;
        $this->editingAttributeId = null;
        $this->loadAttributeValues();
    }

    public function loadAttributeValues(): void
    {
        if ($this->activeAttributeId) {
            $this->attributeValues = app(AttributeService::class)->getValuesByAttribute($this->activeAttributeId);
        }
    }

    public function createAttribute(): void
    {
        $this->validate(['newAttributeName' => 'required|string|max:255']);
        $attribute = app(AttributeService::class)->createAttribute($this->newAttributeName);
        $this->newAttributeName = '';
        $this->activeAttributeId = $attribute->id;
        $this->loadAttributes();
    }

    public function updateAttribute(): void
    {
        $this->validate(['editAttributeName' => 'required|string|max:255']);
        app(AttributeService::class)->updateAttribute($this->editingAttributeId, $this->editAttributeName);
        $this->editingAttributeId = null;
        $this->loadAttributes();
    }

    public function deleteAttribute($id): void
    {
        app(AttributeService::class)->deleteAttribute($id);
        if ($this->activeAttributeId === $id) {
            $this->activeAttributeId = null;
            $this->attributeValues = null;
        }
        $this->loadAttributes();
    }

    public function createAttributeValue(): void
    {
        $this->validate([
            'newAttributeValue' => [
                'required', 'string', 'max:255',
                Rule::unique('attribute_values', 'value')->where('attribute_id', $this->activeAttributeId)
            ]
        ], [
            'newAttributeValue.unique' => 'Giá trị này đã tồn tại trong nhóm này.'
        ]);

        if ($this->activeAttributeId) {
            app(AttributeService::class)->createAttributeValue($this->activeAttributeId, $this->newAttributeValue);
            $this->newAttributeValue = '';
            $this->loadAttributeValues();
            $this->loadAttributes();
        }
    }

    public function updateAttributeValue(): void
    {
        $this->validate([
            'editAttributeValueName' => [
                'required', 'string', 'max:255',
                Rule::unique('attribute_values', 'value')
                    ->where('attribute_id', $this->activeAttributeId)
                    ->ignore($this->editingAttributeValueId)
            ]
        ], [
            'editAttributeValueName.unique' => 'Giá trị này đã tồn tại trong nhóm này.'
        ]);

        if ($this->editingAttributeValueId) {
            app(AttributeService::class)->updateAttributeValue($this->editingAttributeValueId, $this->editAttributeValueName);
            $this->editingAttributeValueId = null;
            $this->loadAttributeValues();
        }
    }

    public function deleteAttributeValue($id): void
    {
        app(AttributeService::class)->deleteAttributeValue($id);
        $this->loadAttributeValues();
        $this->loadAttributes();
    }

    public function moveAttributeValue($id, $direction): void
    {
        app(AttributeService::class)->moveValue($id, $direction);
        $this->loadAttributeValues();
    }
};
?>

<div class="space-y-6 animate-in fade-in slide-in-from-bottom-2 duration-300">
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 flex gap-4">
        <flux:icon name="information-circle" class="size-6 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5"/>
        <div>
            <h3 class="font-medium text-blue-900 dark:text-blue-300">Bản chất của Biến thể (Variant Attributes)</h3>
            <p class="text-sm text-blue-700 dark:text-blue-400 mt-1">
                Dùng để phân loại bản thân món ăn. Khách hàng <strong>chỉ được chọn 1</strong>
                (Ví dụ: Đã chọn Size L thì không thể chọn Size M).
                <br><em>Lưu ý: Tại đây bạn chỉ cần tạo Tên (VD: Size M). Giá tiền sẽ được
                    nhập khi bạn gắn Size này vào từng món ăn cụ thể.</em>
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
        <div class="md:col-span-4 space-y-4">
            <form wire:submit="createAttribute" class="flex gap-2">
                <flux:input wire:model="newAttributeName" placeholder="Tên thuộc tính (VD: Kích thước)" class="flex-1" required/>
                <flux:button type="submit" variant="primary" icon="plus" class="px-3"/>
            </form>

            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden shadow-sm">
                @forelse($attributeGroups as $attr)
                    @if($editingAttributeId === $attr->id)
                        <div class="p-3 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50">
                            <flux:input wire:model="editAttributeName" size="sm" required wire:keydown.enter="updateAttribute"/>
                            <div class="flex justify-end gap-2 mt-2">
                                <flux:button wire:click="updateAttribute" variant="primary" size="sm" icon="check"/>
                                <flux:button wire:click="$set('editingAttributeId', null)" variant="subtle" size="sm" icon="x-mark"/>
                            </div>
                        </div>
                    @else
                        <div wire:click="selectAttribute({{ $attr->id }})"
                             class="flex justify-between items-center px-4 py-3 cursor-pointer border-b border-zinc-100 dark:border-zinc-800 {{ $activeAttributeId === $attr->id ? 'bg-blue-50 dark:bg-blue-900/20 border-l-4 border-l-blue-500' : 'border-l-4 border-l-transparent hover:bg-zinc-50 dark:hover:bg-zinc-800' }}">
                            <div>
                                <div class="font-medium {{ $activeAttributeId === $attr->id ? 'text-blue-600' : 'text-zinc-900 dark:text-white' }}">{{ $attr->name }}</div>
                                <div class="text-xs text-zinc-500 mt-0.5">{{ $attr->values_count ?? $attr->values()->count() ?? 0 }} giá trị</div>
                            </div>
                            <div class="flex items-center gap-1">
                                <flux:button wire:click.stop="$set('editingAttributeId', {{ $attr->id }}); $set('editAttributeName', '{{ addslashes($attr->name) }}')" variant="subtle" size="sm" icon="pencil-square" class="text-zinc-400 hover:text-blue-500"/>
                                <flux:button wire:click.stop="deleteAttribute({{ $attr->id }})" wire:confirm="Xóa thuộc tính này sẽ xóa toàn bộ giá trị. Bạn chắc chắn chứ?" variant="subtle" size="sm" icon="trash" class="text-zinc-400 hover:text-red-500"/>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="p-6 text-center text-zinc-500 text-sm">Chưa có thuộc tính nào.</div>
                @endforelse
            </div>
        </div>

        <div class="md:col-span-8">
            @if($activeAttributeId && $attributeGroups->firstWhere('id', $activeAttributeId))
                <flux:card class="space-y-6">
                    <flux:heading level="2" class="text-xl text-blue-600 dark:text-blue-400">
                        {{ $attributeGroups->firstWhere('id', $activeAttributeId)->name }}
                    </flux:heading>

                    <form wire:submit="createAttributeValue" class="flex gap-4 items-start bg-zinc-50 dark:bg-zinc-900/50 p-4 rounded-xl border border-zinc-100 dark:border-zinc-800">
                        <div class="flex-1">
                            <flux:input wire:model="newAttributeValue" label="Tên giá trị" placeholder="VD: Size L" required/>
                            @error('newAttributeValue') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <flux:button type="submit" variant="primary" class="mt-6">Thêm giá trị</flux:button>
                    </form>

                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>Thứ tự</flux:table.column>
                            <flux:table.column>Tên giá trị</flux:table.column>
                            <flux:table.column>Thao tác</flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @forelse($attributeValues as $index => $val)
                                @if($editingAttributeValueId === $val->id)
                                    <flux:table.row>
                                        <flux:table.cell></flux:table.cell>
                                        <flux:table.cell>
                                            <flux:input wire:model="editAttributeValueName" size="sm" class="w-full min-w-50" required wire:keydown.enter="updateAttributeValue"/>
                                            @error('editAttributeValueName') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                        </flux:table.cell>
                                        <flux:table.cell>
                                            <div class="flex gap-2">
                                                <flux:button wire:click="updateAttributeValue" variant="primary" size="sm" icon="check"/>
                                                <flux:button wire:click="$set('editingAttributeValueId', null)" variant="subtle" size="sm" icon="x-mark"/>
                                            </div>
                                        </flux:table.cell>
                                    </flux:table.row>
                                @else
                                    <flux:table.row>
                                        <flux:table.cell>
                                            <div class="flex flex-col gap-1 w-8">
                                                <button wire:click="moveAttributeValue({{ $val->id }}, 'up')" class="text-zinc-400 hover:text-blue-600 disabled:opacity-30" {{ $index === 0 ? 'disabled' : '' }}>
                                                    <flux:icon name="chevron-up" class="size-4"/>
                                                </button>
                                                <button wire:click="moveAttributeValue({{ $val->id }}, 'down')" class="text-zinc-400 hover:text-blue-600 disabled:opacity-30" {{ $index === count($attributeValues) - 1 ? 'disabled' : '' }}>
                                                    <flux:icon name="chevron-down" class="size-4"/>
                                                </button>
                                            </div>
                                        </flux:table.cell>
                                        <flux:table.cell class="font-medium">{{ $val->value }}</flux:table.cell>
                                        <flux:table.cell>
                                            <div class="flex items-center gap-2">
                                                <flux:button wire:click="$set('editingAttributeValueId', {{ $val->id }}); $set('editAttributeValueName', '{{ addslashes($val->value) }}')" variant="subtle" size="sm" icon="pencil-square"/>
                                                <flux:button wire:click="deleteAttributeValue({{ $val->id }})" variant="danger" size="sm" icon="trash"/>
                                            </div>
                                        </flux:table.cell>
                                    </flux:table.row>
                                @endif
                            @empty
                                <flux:table.row>
                                    <flux:table.cell colspan="3" class="text-center text-zinc-500">Chưa có giá trị nào.</flux:table.cell>
                                </flux:table.row>
                            @endforelse
                        </flux:table.rows>
                    </flux:table>
                </flux:card>
            @endif
        </div>
    </div>
</div>
