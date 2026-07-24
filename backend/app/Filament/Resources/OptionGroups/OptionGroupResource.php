<?php

namespace App\Filament\Resources\OptionGroups;

use App\Models\OptionGroup;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class OptionGroupResource extends Resource
{
    protected static ?string $model = OptionGroup::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-sparkles';

    protected static string|null|\UnitEnum $navigationGroup = 'Biến thể & Tùy chọn';

    protected static ?string $modelLabel = 'Nhóm tùy chọn';

    protected static ?string $pluralModelLabel = 'Nhóm tùy chọn';

    public static function form(Form|Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Thông tin nhóm')
                    ->description('Ví dụ: Topping, Lượng đá, Lượng đường')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Tên nhóm')
                            ->required()
                            ->maxLength(255),
                    ]),

                Section::make('Danh sách tùy chọn')
                    ->description('Thêm các lựa chọn chi tiết và giá phụ thu kèm theo (Ví dụ: Trân châu trắng +5000).')
                    ->schema([
                        Forms\Components\Repeater::make('options')
                            ->relationship('options')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Tên tùy chọn')
                                    ->required()
                                    ->columnSpan(2), // Chiếm 2/6 cột

                                Forms\Components\TextInput::make('price_adjustment')
                                    ->label('Giá phụ thu')
                                    ->numeric()
                                    ->default(0)
                                    ->prefix('VNĐ')
                                    ->columnSpan(3), // Tăng lên 3/6 cột để rộng rãi, không bị che số tiền

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Trạng thái')
                                    ->default(true)
                                    ->inline(false)
                                    ->columnSpan(1), // Chiếm 1/6 cột cho nút gạt
                            ])
                            ->columns(6) // Nâng tổng số cột từ 5 lên 6 để phân phối không gian tốt hơn
                            ->orderColumn('display_order')
                            ->addActionLabel('Thêm tùy chọn mới')
                            ->defaultItems(1)
                            ->deleteAction(
                                fn (Action $action) => $action->requiresConfirmation()
                                    ->modalHeading('Xóa Tùy chọn')
                                    ->modalDescription('Tùy chọn này sẽ bị gỡ bỏ. Bạn có chắc chắn không?')
                            ),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên nhóm tùy chọn')
                    ->searchable(),

                Tables\Columns\TextColumn::make('options_count')
                    ->label('Số lượng tùy chọn')
                    ->counts('options')
                    ->badge()
                    ->color('info'),
            ])
            ->actions([
                EditAction::make()->modalWidth('4xl'),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Xóa Nhóm Tùy chọn')
                    ->modalDescription('Bạn có chắc chắn muốn xóa nhóm này không? Toàn bộ tùy chọn con bên trong cũng sẽ bị xóa.')
                    ->modalSubmitActionLabel('Có, xóa ngay'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageOptionGroups::route('/'),
        ];
    }
}
