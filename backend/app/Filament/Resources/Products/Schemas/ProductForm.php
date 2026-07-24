<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Attribute;
use App\Models\OptionGroup;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // =========================================================
                // SECTION 1: THÔNG TIN CHI TIẾT
                // =========================================================
                Section::make('1. Thông tin chi tiết món ăn')
                       ->description('Cấu hình các thông tin cơ bản, danh mục, giá bán và hình ảnh đại diện.')
                       ->schema([
                           Grid::make(2)->schema([
                               TextInput::make('name')
                                        ->label('Tên món ăn')
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                               TextInput::make('slug')
                                        ->label('Slug')
                                        ->required()
                                        ->maxLength(255)
                                        ->unique(ignoreRecord: true),
                           ]),

                           Grid::make(3)->schema([
                               Select::make('categories')
                                     ->label('Danh mục')
                                     ->relationship('categories', 'name')
                                     ->multiple()
                                     ->preload()
                                     ->searchable(),

                               TextInput::make('base_price')
                                        ->label('Giá bán chuẩn')
                                        ->numeric()
                                        ->required()
                                        ->prefix('VNĐ'),

                               TextInput::make('original_price')
                                        ->label('Giá gốc (chưa giảm)')
                                        ->numeric()
                                        ->prefix('VNĐ'),
                           ]),

                           Textarea::make('description')
                                   ->label('Mô tả món ăn')
                                   ->columnSpan('full'),

                           // XỬ LÝ UPLOAD ẢNH VÀO PUBLIC & CHỈ LƯU TÊN FILE VÀO DB
                           FileUpload::make('image')
                                     ->label('Hình ảnh món ăn')
                                     ->image()
                                     ->disk('public')               // 1. Lưu vào disk 'public' (storage/app/public)
                                     ->directory(Product::IMAGE_PATH)          // File vật lý nằm trong storage/app/public/products
                                     ->imageEditor()
                               // Khi load dữ liệu từ DB lên Form: Nối thêm 'products/' để Filament tìm thấy file hiển thị
                                     ->formatStateUsing(fn ($state) => $state ? (str_contains($state, '/') ? $state : "products/{$state}") : null)
                               // Khi lưu vào DB: Dùng basename() để loại bỏ 'products/' chỉ giữ lại 'ten_file.jpg'
                                     ->dehydrateStateUsing(fn ($state) => is_string($state) ? basename($state) : $state)
                                     ->columnSpan('full'),
                       ])
                       ->columnSpan('full'),

                // =========================================================
                // SECTION 2: TẠO BIẾN THỂ (VARIANTS)
                // =========================================================
                Section::make('2. Tạo biến thể (Variants)')
                       ->description('Quản lý các biến thể món ăn hoặc tự động sinh biến thể từ các Thuộc tính.')
                       ->schema([
                           Repeater::make('variants')
                                   ->relationship('variants')
                                   ->schema([
                                       Grid::make(4)->schema([
                                           TextInput::make('name')
                                                    ->label('Tên biến thể')
                                                    ->placeholder('VD: L / Nóng')
                                                    ->required(),

                                           TextInput::make('sku')
                                                    ->label('Mã SKU')
                                                    ->placeholder('VD: TS-L-HOT'),

                                           TextInput::make('price')
                                                    ->label('Giá bán riêng')
                                                    ->numeric()
                                                    ->required()
                                                    ->prefix('VNĐ'),

                                           Toggle::make('is_available')
                                                 ->label('Kinh doanh')
                                                 ->default(true),
                                       ]),
                                   ])
                                   ->addAction(fn (Action $action) => $action
                                       ->label('Sinh biến thể tự động')
                                       ->icon('heroicon-m-sparkles')
                                       ->color('success')
                                       ->modalHeading('Chọn Thuộc tính & Giá trị để sinh biến thể')
                                       ->modalDescription('Tích chọn thuộc tính để hiển thị danh sách giá trị. Hệ thống sẽ tự động nhân các kết hợp lại thành biến thể.')
                                       ->modalSubmitActionLabel('Tạo danh sách biến thể')
                                       ->form(function () {
                                           $attributes = Attribute::with('values')->get();
                                           $fields = [];

                                           foreach ($attributes as $attribute) {
                                               $valueOptions = $attribute->values->mapWithKeys(function ($val) {
                                                   $label = $val->name ?? $val->value ?? "Giá trị #{$val->id}";

                                                   return [(string) $val->id => (string) $label];
                                               })->toArray();

                                               $allValueIds = array_keys($valueOptions);

                                               $fields[] = Group::make([
                                                   Checkbox::make("attr_enabled_{$attribute->id}")
                                                           ->label($attribute->name)
                                                           ->live()
                                                           ->afterStateUpdated(function (Set $set, $state) use (
                                                               $attribute,
                                                               $allValueIds) {
                                                               if ($state) {
                                                                   $set("attr_values_{$attribute->id}", $allValueIds);
                                                               } else {
                                                                   $set("attr_values_{$attribute->id}", []);
                                                               }
                                                           }),

                                                   CheckboxList::make("attr_values_{$attribute->id}")
                                                               ->hiddenLabel()
                                                               ->options($valueOptions)
                                                               ->columns(4)
                                                               ->visible(fn (Get $get) => (bool) $get("attr_enabled_{$attribute->id}")),
                                               ])->columns(1);
                                           }

                                           return $fields;
                                       })
                                       ->action(function (array $data, Set $set, Get $get) {
                                           $attributes = Attribute::with('values')->get();
                                           $selectedSets = [];

                                           foreach ($attributes as $attribute) {
                                               if (! empty($data["attr_enabled_{$attribute->id}"])) {
                                                   $selectedValues = $data["attr_values_{$attribute->id}"] ?? [];

                                                   if (! empty($selectedValues)) {
                                                       $valueNames = $attribute->values
                                                           ->whereIn('id', $selectedValues)
                                                           ->map(fn ($val) => $val->name ?? $val->value)
                                                           ->filter()
                                                           ->toArray();

                                                       if (! empty($valueNames)) {
                                                           $selectedSets[] = $valueNames;
                                                       }
                                                   }
                                               }
                                           }

                                           if (empty($selectedSets)) {
                                               return;
                                           }

                                           $combinations = [[]];
                                           foreach ($selectedSets as $propertyValues) {
                                               $append = [];
                                               foreach ($combinations as $product) {
                                                   foreach ($propertyValues as $item) {
                                                       $append[] = array_merge($product, [$item]);
                                                   }
                                               }
                                               $combinations = $append;
                                           }

                                           $currentVariants = $get('variants') ?? [];
                                           $productName = $get('name') ?? 'Món';
                                           $basePrice = $get('base_price') ?? 0;

                                           foreach ($combinations as $combination) {
                                               $variantName = implode(' - ', $combination);
                                               $sku = Str::slug($productName.'-'.$variantName);

                                               $currentVariants[] = [
                                                   'name' => $variantName,
                                                   'sku' => strtoupper($sku),
                                                   'price' => $basePrice,
                                                   'is_available' => true,
                                               ];
                                           }

                                           $set('variants', $currentVariants);
                                       })
                                   )
                                   ->collapsible()
                                   ->defaultItems(0),
                       ])
                       ->columnSpan('full'),

                // =========================================================
                // SECTION 3: TẠO TÙY CHỌN (OPTION GROUPS)
                // =========================================================
                Section::make('3. Tạo tùy chọn (Option Groups)')
                       ->description('Cấu hình các nhóm tùy chọn đi kèm (Topping, Lượng đá, Lượng đường...).')
                       ->schema([
                           Repeater::make('productsOptionGroups')
                                   ->relationship('productsOptionGroups')
                                   ->schema([
                                       Select::make('option_group_id')
                                             ->label('Nhóm tùy chọn')
                                             ->options(OptionGroup::pluck('name', 'id'))
                                             ->required()
                                             ->live()
                                             ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                             ->columnSpan('full'),

                                       Placeholder::make('options_preview')
                                                  ->label('Các lựa chọn thuộc nhóm này (Xem trước)')
                                                  ->content(function (Get $get) {
                                                      $groupId = $get('option_group_id');
                                                      if (! $groupId) {
                                                          return new HtmlString('<em class="text-gray-400">Chưa chọn nhóm tùy chọn.</em>');
                                                      }

                                                      $group = OptionGroup::with('options')->find($groupId);
                                                      if (! $group || $group->options->isEmpty()) {
                                                          return new HtmlString('<em class="text-gray-400">Nhóm này chưa có lựa chọn nào.</em>');
                                                      }

                                                      $badges = $group->options->map(function ($opt) {
                                                          $priceFormatted = $opt->price_adjustment != 0
                                                              ? ($opt->price_adjustment > 0 ? ' (+' . number_format($opt->price_adjustment) . ' VNĐ)' : ' (' . number_format($opt->price_adjustment) . ' VNĐ)')
                                                              : ' (+0 VNĐ)';

                                                          return "<span class='inline-flex items-center rounded-md bg-gray-100 dark:bg-gray-800 px-2.5 py-1 text-xs font-medium text-gray-800 dark:text-gray-200 ring-1 ring-inset ring-gray-500/10 mr-1.5 mb-1.5'>
                                                        <strong>{$opt->name}</strong>&nbsp;{$priceFormatted}
                                                    </span>";
                                                      })->implode('');

                                                      return new HtmlString("<div class='flex flex-wrap items-center mt-1'>{$badges}</div>");
                                                  })
                                                  ->columnSpan('full'),

                                       Grid::make(4)->schema([
                                           Toggle::make('is_required')
                                                 ->label('Bắt buộc chọn')
                                                 ->default(false),

                                           Toggle::make('is_multiple')
                                                 ->label('Chọn nhiều')
                                                 ->default(false)
                                                 ->live(),

                                           TextInput::make('max_select')
                                                    ->label('Số lượng tối đa')
                                                    ->numeric()
                                                    ->placeholder('VD: 2')
                                                    ->hidden(fn (Get $get) => ! $get('is_multiple')),

                                           TextInput::make('display_order')
                                                    ->label('Thứ tự hiển thị')
                                                    ->numeric()
                                                    ->default(0),
                                       ]),
                                   ])
                                   ->addAction(fn (Action $action) => $action
                                       ->label('Thêm nhóm tùy chọn')
                                       ->icon('heroicon-m-plus-circle')
                                       ->color('primary')
                                       ->modalHeading('Chọn Nhóm tùy chọn')
                                       ->modalDescription('Tích chọn Nhóm tùy chọn muốn áp dụng cho món ăn. Các lựa chọn con bên trong được hiển thị để tham khảo (không thể tích bỏ từng mục do cấu trúc DB).')
                                       ->modalSubmitActionLabel('Thêm nhóm đã chọn')
                                       ->form(function () {
                                           $groups = OptionGroup::with('options')->get();
                                           $fields = [];

                                           foreach ($groups as $group) {
                                               $optionMap = $group->options->mapWithKeys(function ($opt) {
                                                   $priceFormatted = $opt->price_adjustment != 0
                                                       ? ($opt->price_adjustment > 0 ? ' (+' . number_format($opt->price_adjustment) . ' VNĐ)' : ' (' . number_format($opt->price_adjustment) . ' VNĐ)')
                                                       : ' (+0 VNĐ)';

                                                   return [(string) $opt->id => (string) ($opt->name . $priceFormatted)];
                                               })->toArray();

                                               $allOptIds = array_keys($optionMap);

                                               $fields[] = Group::make([
                                                   Checkbox::make("group_enabled_{$group->id}")
                                                           ->label($group->name)
                                                           ->live(),

                                                   CheckboxList::make("group_options_preview_{$group->id}")
                                                               ->hiddenLabel()
                                                               ->options($optionMap)
                                                               ->default($allOptIds)
                                                               ->disabled()
                                                               ->columns(3)
                                                               ->visible(fn (Get $get) => (bool) $get("group_enabled_{$group->id}")),
                                               ])->columns(1);
                                           }

                                           return $fields;
                                       })
                                       ->action(function (array $data, Set $set, Get $get) {
                                           $groups = OptionGroup::get();
                                           $currentProductsOptionGroups = $get('productsOptionGroups') ?? [];

                                           foreach ($groups as $group) {
                                               if (! empty($data["group_enabled_{$group->id}"])) {
                                                   $exists = collect($currentProductsOptionGroups)
                                                       ->contains('option_group_id', $group->id);

                                                   if (! $exists) {
                                                       $currentProductsOptionGroups[] = [
                                                           'option_group_id' => $group->id,
                                                           'is_required' => false,
                                                           'is_multiple' => false,
                                                           'max_select' => null,
                                                           'display_order' => 0,
                                                       ];
                                                   }
                                               }
                                           }

                                           $set('productsOptionGroups', $currentProductsOptionGroups);
                                       })
                                   )
                                   ->collapsible()
                                   ->defaultItems(0),
                       ])
                       ->columnSpan('full'),
            ]);
    }
}
