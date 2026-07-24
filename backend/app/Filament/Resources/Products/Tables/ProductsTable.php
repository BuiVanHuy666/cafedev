<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Hình ảnh')
                    ->disk('public')
                    ->square()
                    ->defaultImageUrl(url('/images/placeholder.png')),

                TextColumn::make('name')
                    ->label('Tên món ăn')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->slug)
                    ->action(
                        ViewAction::make('viewProductDetails')
                            ->modalHeading(fn ($record) => "Chi tiết món ăn: {$record->name}")
                            ->modalWidth('4xl')
                            ->infolist([
                                Section::make('Thông tin chung')->schema([
                                    Grid::make(3)->schema([
                                        ImageEntry::make('image')
                                            ->label('Hình ảnh')
                                            ->defaultImageUrl(url('/images/placeholder.png')),

                                        Grid::make(1)->schema([
                                            TextEntry::make('name')->label('Tên món ăn')->weight('bold'),
                                            TextEntry::make('slug')->label('Slug'),
                                            TextEntry::make('categories.name')
                                                ->label('Danh mục')
                                                ->badge()
                                                ->color('primary'),
                                        ])->columnSpan(2),
                                    ]),

                                    Grid::make(2)->schema([
                                        TextEntry::make('base_price')
                                            ->label('Giá bán chuẩn')
                                            ->numeric(0)
                                            ->suffix(' VNĐ'),

                                        TextEntry::make('original_price')
                                            ->label('Giá gốc')
                                            ->numeric(0)
                                            ->suffix(' VNĐ')
                                            ->placeholder('Không có'),
                                    ]),

                                    TextEntry::make('description')
                                        ->label('Mô tả')
                                        ->placeholder('Không có mô tả')
                                        ->columnSpanFull(),
                                ]),

                                Section::make('Danh sách biến thể (Variants)')->schema([
                                    RepeatableEntry::make('variants')
                                        ->hiddenLabel()
                                        ->schema([
                                            TextEntry::make('name')->label('Tên biến thể'),
                                            TextEntry::make('sku')->label('Mã SKU')->placeholder('-'),
                                            TextEntry::make('price')
                                                ->label('Giá riêng')
                                                ->numeric(0)
                                                ->suffix(' VNĐ'),
                                            IconEntry::make('is_available')
                                                ->label('Đang kinh doanh')
                                                ->boolean(),
                                        ])
                                        ->columns(4)
                                        ->placeholder('Món này chưa thiết lập biến thể.'),
                                ]),

                                Section::make('Cấu hình Tùy chọn (Option Groups)')->schema([
                                    RepeatableEntry::make('productsOptionGroups')
                                        ->hiddenLabel()
                                        ->schema([
                                            TextEntry::make('optionGroup.name')
                                                ->label('Nhóm tùy chọn')
                                                ->weight('bold'),
                                            IconEntry::make('is_required')
                                                ->label('Bắt buộc chọn')
                                                ->boolean(),
                                            IconEntry::make('is_multiple')
                                                ->label('Chọn nhiều')
                                                ->boolean(),
                                            TextEntry::make('max_select')
                                                ->label('Chọn tối đa')
                                                ->placeholder('Không giới hạn'),
                                        ])
                                        ->columns(4)
                                        ->placeholder('Món này chưa gắn nhóm tùy chọn nào.'),
                                ]),
                            ])
                    ),

                TextColumn::make('categories.name')
                    ->label('Danh mục')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                TextColumn::make('base_price')
                    ->label('Giá bán chuẩn')
                    ->numeric(decimalPlaces: 0, decimalSeparator: '.', thousandsSeparator: ',')
                    ->suffix(' VNĐ')
                    ->sortable(),

                TextColumn::make('original_price')
                    ->label('Giá gốc')
                    ->numeric(decimalPlaces: 0, decimalSeparator: '.', thousandsSeparator: ',')
                    ->suffix(' VNĐ')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('variants_count')
                    ->label('Biến thể')
                    ->counts('variants')
                    ->badge()
                    ->color('info')
                    ->alignCenter(),

                TextColumn::make('products_option_groups_count')
                    ->label('Nhóm tùy chọn')
                    ->counts('productsOptionGroups')
                    ->badge()
                    ->color('warning')
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('categories')
                    ->label('Danh mục')
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->preload(),

                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
