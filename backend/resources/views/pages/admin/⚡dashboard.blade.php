<?php

use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Title('Tổng quan kinh doanh')] class extends Component {
    public string $revenue = '4,520,000 đ';
    public int $newOrders = 24;
    public int $totalCustomers = 18;
};
?>

<div class="space-y-8">

    <div>
        <flux:heading level="1" class="text-2xl">Tổng quan kinh doanh</flux:heading>
        <flux:subheading>Chào mừng quay trở lại. Chúc một ngày buôn bán đắt khách!</flux:subheading>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <flux:card class="flex items-center gap-4">
            <div class="flex items-center justify-center size-12 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400">
                <flux:icon name="banknotes" class="size-6"/>
            </div>
            <div>
                <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Doanh thu hôm nay</div>
                <div class="text-2xl font-semibold text-zinc-900 dark:text-white">{{ $revenue }}</div>
            </div>
        </flux:card>

        <flux:card class="flex items-center gap-4">
            <div class="flex items-center justify-center size-12 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400">
                <flux:icon name="shopping-bag" class="size-6"/>
            </div>
            <div>
                <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Đơn hàng mới</div>
                <div class="text-2xl font-semibold text-zinc-900 dark:text-white">{{ $newOrders }}
                    <span class="text-sm font-normal text-zinc-500">đơn</span></div>
            </div>
        </flux:card>

        <flux:card class="flex items-center gap-4">
            <div class="flex items-center justify-center size-12 rounded-full bg-orange-100 dark:bg-orange-900/40 text-orange-600 dark:text-orange-400">
                <flux:icon name="users" class="size-6"/>
            </div>
            <div>
                <div class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Khách hàng</div>
                <div class="text-2xl font-semibold text-zinc-900 dark:text-white">{{ $totalCustomers }}
                    <span class="text-sm font-normal text-zinc-500">người</span></div>
            </div>
        </flux:card>

    </div>

    <flux:card>
        <div class="flex justify-between items-center mb-6">
            <flux:heading level="2">Đơn hàng gần đây</flux:heading>
            <flux:button size="sm" variant="subtle">Xem tất cả</flux:button>
        </div>

        <div class="text-center py-12 text-zinc-500 dark:text-zinc-400">
            <flux:icon name="inbox" class="size-12 mx-auto mb-3 opacity-20"/>
            <p>Chưa có dữ liệu đơn hàng.</p>
        </div>
    </flux:card>

</div>
