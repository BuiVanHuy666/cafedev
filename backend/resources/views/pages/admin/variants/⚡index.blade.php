<?php

use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Services\AttributeService;
use App\Services\OptionService;
use Illuminate\Validation\Rule;

new #[Title('Quản lý Master Data')]
class extends Component {
    public string $currentTab = 'variants';

    public function setTab($tab): void
    {
        $this->currentTab = $tab;
    }
};
?>

<div class="space-y-6 max-w-6xl mx-auto pb-12">
    <div>
        <flux:heading level="1" class="text-2xl">Quản lý Master Data</flux:heading>
        <flux:subheading>Định nghĩa sẵn các Kích thước và Topping để tái sử dụng khi tạo món ăn mới.</flux:subheading>
    </div>

    <div class="flex gap-2 border-b border-zinc-200 dark:border-zinc-800 pb-px">
        <button wire:click="setTab('variants')"
                class="px-4 py-2 font-medium text-sm border-b-2 transition-colors {{ $currentTab === 'variants' ? 'border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300' }}">
            1. Thuộc tính Biến thể (Kích thước)
        </button>
        <button wire:click="setTab('options')"
                class="px-4 py-2 font-medium text-sm border-b-2 transition-colors {{ $currentTab === 'options' ? 'border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 hover:border-zinc-300' }}">
            2. Nhóm Tùy chọn (Topping, Đá)
        </button>
    </div>

    <!-- Gọi các Child Component tương ứng -->
    @if($currentTab === 'variants')
        <livewire:master-data.variants-tab />
    @elseif($currentTab === 'options')
        <livewire:master-data.options-tab />
    @endif
</div>
