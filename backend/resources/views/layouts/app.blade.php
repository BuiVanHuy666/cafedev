<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
        @fluxAppearance
    </head>

    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 antialiased">

    <flux:sidebar sticky stashable class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark"/>
        <flux:brand href="/admin" name="CafeDev" class="px-2">
            <x-base.logo class="w-8 h-8 text-zinc-900 dark:text-white" />
        </flux:brand>

        <flux:navlist class="mt-4">
            <flux:navlist.item wire:navigate icon="home" href="{{ route('admin.dashboard') }}" :current="request()->routeIs('admin.dashboard')">Tổng quan</flux:navlist.item>
            <flux:navlist.item wire:navigate icon="cube" href="" :current="request()->routeIs('admin.products*')">Quản lý Món ăn</flux:navlist.item>
            <flux:navlist.item wire:navigate icon="rectangle-stack" href="{{ route('admin.variants') }}" :current="request()->routeIs('admin.variants*')">Biến thể & Tùy chọn</flux:navlist.item>
        </flux:navlist>

        <flux:spacer/>

        <flux:dropdown position="top" align="start">
            <flux:profile name="Chủ Quán"/>
            <flux:menu>
                <flux:menu.item icon="arrow-right-start-on-rectangle">Đăng xuất</flux:menu.item>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <flux:header class="border-b border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-3"/>
        <flux:heading level="1">Hệ thống quản trị</flux:heading>
    </flux:header>

    <flux:main>
        {{ $slot }}
    </flux:main>

    @livewireScripts
    @fluxScripts
    </body>
</html>
