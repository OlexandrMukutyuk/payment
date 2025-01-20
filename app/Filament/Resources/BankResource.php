<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BankResource\Pages;
use App\Filament\Resources\BankResource\RelationManagers;
use App\Models\Bank;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BankResource extends Resource
{
    protected static ?string $model = Bank::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $label= "банк";

    protected static ?string $navigationLabel = "Банки";

    protected static ?string $pluralLabel = "Банки";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Назва банку')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),

                Toggle::make('active')
                    ->label('Відображати'),

                SpatieMediaLibraryFileUpload::make('image')
                    ->label('Зображення')
                    ->image()
                    ->disk('banks')
                    ->conversion('preview')

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Місто')
                    ->searchable(),

                ToggleColumn::make('active')
                    ->label('Відображати'),

                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                SpatieMediaLibraryImageColumn::make('image')
                    ->label('Зображення')

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
            'index' => Pages\ListBanks::route('/'),
            'create' => Pages\CreateBank::route('/create'),
            'edit' => Pages\EditBank::route('/{record}/edit'),
        ];
    }
}
