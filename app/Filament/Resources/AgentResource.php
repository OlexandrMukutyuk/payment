<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgentResource\Pages;
use App\Filament\Resources\AgentResource\RelationManagers;
use App\Models\Agent;
use Filament\Forms;
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

        return $form
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
