<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::admin.dashboard')->name('admin.dashboard');
Route::livewire('/options', 'pages::admin.variants')->name('admin.variants');

Route::livewire('/login', 'pages::auth.login')->name('admin.login');

