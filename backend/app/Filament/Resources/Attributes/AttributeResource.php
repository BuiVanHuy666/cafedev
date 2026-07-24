<?php

namespace App\Filament\Resources\Attributes;

use App\Models\Attribute;
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

class AttributeResource extends Resource
{
    protected static ?string $model = Attribute::class;

    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-swatch';

    protected static string|null|\UnitEnum $navigationGroup = 'Biến thể & Tùy chọn';

    protected static ?string $modelLabel = 'Biến thể';

    protected static ?string $pluralModelLabel = 'Biến thể';

    public static function form(Form|Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Thông tin biến thể')
                    ->description('Ví dụ: Kích thước, Màu sắc, Dung tích')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Tên biến thể')
                            ->required()
                            ->maxLength(255),
                    ]),

                Section::make('Danh sách giá trị')
                    ->description('Định nghĩa các giá trị cụ thể cho biến thể này (Ví dụ: S, M, L). Kéo thả để sắp xếp lại.')
                    ->schema([
                        Forms\Components\Repeater::make('values')
                            ->relationship('values')
                            ->schema([
                                Forms\Components\TextInput::make('value')
                                    ->label('Tên giá trị')
                                    ->placeholder('Ví dụ: Size L')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->orderColumn('display_order')
                            ->addActionLabel('Thêm giá trị mới')
                            ->defaultItems(1)
                            ->deleteAction(
                                fn (Action $action) => $action->requiresConfirmation()
                                    ->modalHeading('Xóa giá trị')
                                    ->modalDescription('Giá trị này sẽ bị gỡ bỏ. Bạn có chắc chắn không?')
                            ),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên biến thể')
                    ->searchable(),

                Tables\Columns\TextColumn::make('values_count')
                    ->label('Số lượng giá trị')
                    ->counts('values')
                    ->badge(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Xóa Biến thể')
                    ->modalDescription('Bạn có chắc chắn muốn xóa biến thể này? Dữ liệu không thể khôi phục sau khi xóa.')
                    ->modalSubmitActionLabel('Đồng ý xóa'),
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
            'index' => Pages\ManageAttributes::route('/'),
        ];
    }
}
