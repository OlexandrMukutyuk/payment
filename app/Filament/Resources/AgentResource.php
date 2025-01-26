<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgentResource\Pages;
use App\Filament\Resources\AgentResource\RelationManagers;
use App\Models\Agent;
use App\Models\Bank;
use App\Models\Card;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AgentResource extends Resource
{
    protected static ?string $model = Agent::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $label= "агент";

    protected static ?string $navigationLabel = "Агенти";

    protected static ?string $pluralLabel = "Агенти";

    public static function form(Form $form): Form
    {
        // dd($form->model->cards);
        return $form
            ->schema([
                Tabs::make('Tabs')
                ->columnSpanFull()
                ->tabs([
                    Tab::make('Агент')
                        ->schema([
                            TextInput::make('group_id')
                                ->label('ID Групи')
                                ->required()
                                ->readOnly(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                                ->maxLength(100),

                            TextInput::make('chat_id')
                                ->label('ID Чату')
                                ->required()
                                ->readOnly(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                                ->maxLength(100),

                            TextInput::make('chat_name')
                                ->label('Назва чату')
                                ->maxLength(255),

                            TextInput::make('phone')
                                ->label('Телефон')
                                ->tel()
                                ->required()
                                ->maxLength(40),

                            TextInput::make('name')
                                ->label('ПІБ')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('inn')
                                ->label('ІПН')
                                ->hint('Індивідуальний податковий номер')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('schedule')
                                ->label('Графік роботи')
                                ->maxLength(255),

                            Toggle::make('is_one_day')
                                ->columnSpanFull()
                                ->label('Агент одноденка')
                                ->required(),

                            Toggle::make('active')
                                ->label('Активний')
                                ->columnSpanFull()
                                ->required(),
                        ])->columns(2),
                    Tab::make('Картки')
                        ->schema([
                            Repeater::make('cards')
                                ->relationship('cards')
                                ->label('')
                                ->schema([
                                    Select::make('bank_id')
                                        ->label('Банк')
                                        ->searchable()
                                        ->options(Bank::active()->pluck('name', 'id'))
                                        ->required(),

                                    Select::make('status')
                                        ->label('Статус карти')
                                        ->searchable()
                                        ->options(Card::getStatuses())
                                        ->required(),

                                    TextInput::make('limit')
                                        ->label('Ліміт')
                                        ->maxLength(255)
                                        ->suffix('UAH')
                                        ->required(),

                                    TextInput::make('date_end')
                                        ->label('Дата завершення')
                                        ->maxLength(10)
                                        ->suffix('ММ/РР')
                                        ->required(),

                                    TextInput::make('iban')
                                        ->label('IBAN')
                                        ->maxLength(255)
                                        ->required()
                                        ->columnSpan(2),

                                    TextInput::make('number')
                                        ->label('Номер')
                                        ->maxLength(50)
                                        ->required(),

                                    SpatieMediaLibraryFileUpload::make('files')
                                        ->label('Виписка')
                                        ->collection('files')
                                        ->disk('cards')
                                        ->openable()
                                        ->previewable(false)
                                        ->downloadable(),


                                    DateTimePicker::make('created_at')
                                        ->label('Дата додавання карти')
                                        ->readOnly(),

                                    DateTimePicker::make('updated_at')
                                        ->label('Останнє редагування карти')
                                        ->readOnly(),

                                    Toggle::make('active')
                                        ->label('Активна')
                                        ->required()
                                        ->columnSpanFull(),

                                ])
                                ->columns(4)
                                ->addable(false)
                        ]),
                    ])
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('chat_id')
                    ->label('ID Чату')
                    ->searchable(),

                TextColumn::make('chat_name')
                    ->label('Назва чату')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('phone')
                    ->label('Телефон')
                    ->searchable(),

                TextColumn::make('name')
                    ->label('ПІБ')
                    ->searchable(),

                ToggleColumn::make('is_one_day')
                    ->label('Агент одноденка'),

                ToggleColumn::make('active')
                    ->label('Активний'),

                TextColumn::make('schedule')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('inn')
                    ->label('ІПН')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Створено')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Оновлено')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgents::route('/'),
            'create' => Pages\CreateAgent::route('/create'),
            'edit' => Pages\EditAgent::route('/{record}/edit'),
        ];
    }
}
