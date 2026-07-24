<?php

namespace App\Filament\Resources\Categories\Tables;

use App\Services\CategoryService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Tên')->searchable()->sortable(),
                TextColumn::make('slug')->label('Slug')->searchable()->color('gray'),
                ToggleColumn::make('is_active')
                    ->label('Trạng thái')
                    ->updateStateUsing(function (Model $record, $state) {
                        app(CategoryService::class)->updateCategory($record, [
                            'name' => $record->name,
                            'slug' => $record->slug,
                            'is_active' => $state,
                        ]);
                    }),

                TextColumn::make('display_order')->label('Thứ tự')->sortable(),
            ])
            ->defaultSort('display_order')
            ->reorderable('display_order')
            ->filters([
                TrashedFilter::make()->label('Thùng rác'),
            ])
            ->actions([
                ViewAction::make()->modalWidth('md'),

                EditAction::make()
                    ->modalWidth('md')
                    ->using(function (Model $record, array $data): Model {
                        app(CategoryService::class)->updateCategory($record, $data);

                        return $record;
                    }),

                DeleteAction::make()
                    ->using(fn (Model $record) => app(CategoryService::class)->deleteCategory($record)),

                RestoreAction::make()
                    ->using(fn (Model $record) => app(CategoryService::class)->restoreCategory($record)),

                ForceDeleteAction::make()
                    ->using(fn (Model $record) => app(CategoryService::class)->forceDeleteCategory($record)),
            ]);
    }
}
