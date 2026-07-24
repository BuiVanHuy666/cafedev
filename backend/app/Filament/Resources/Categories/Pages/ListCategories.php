<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use App\Services\CategoryService;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modalWidth('md')
                ->using(function (array $data, string $model): Model {
                    return app(CategoryService::class)->createCategory($data);
                }),
        ];
    }

    protected function afterReorderTable(): void
    {
        Cache::forget('public_categories');
    }
}
