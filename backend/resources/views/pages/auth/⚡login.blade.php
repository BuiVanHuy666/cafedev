<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

new #[Title('Đăng nhập'), Layout('layouts::auth')] class extends Component {
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required')]
    public string $password = '';

    public bool $remember = false;

    public function login()
    {
        $this->validate();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', 'Thông tin tài khoản hoặc mật khẩu không chính xác.');
            return;
        }

        session()->regenerate();

        return redirect()->intended('/admin');
    }
};
?>

<div class="flex flex-col items-center justify-center min-h-screen bg-zinc-50 dark:bg-zinc-950 px-4">
    <div class="w-full max-w-md space-y-8">

        <div class="flex flex-col items-center text-center space-y-4">
            <div class="flex items-center justify-center w-16 h-16 bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 text-zinc-800 dark:text-zinc-200">
                <x-base.logo/>
            </div>

            <div class="space-y-1">
                <flux:heading level="1" class="text-2xl font-bold">Đăng nhập hệ thống</flux:heading>
                <flux:subheading>F&B Management Admin Panel</flux:subheading>
            </div>
        </div>

        <form wire:submit="login" class="bg-white dark:bg-zinc-900 p-8 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 space-y-5">
            <flux:input
                wire:model="email"
                type="email"
                label="Email"
                placeholder="admin@cafe.com"
                autocomplete="email"
                required
            />

            <flux:input
                wire:model="password"
                type="password"
                label="Mật khẩu"
                placeholder="••••••••"
                autocomplete="current-password"
                required
            />

            <div class="flex items-center justify-between">
                <flux:checkbox wire:model="remember" label="Ghi nhớ đăng nhập"/>
            </div>

            <flux:button type="submit" variant="primary" class="w-full">Đăng nhập</flux:button>
        </form>
    </div>
</div>
