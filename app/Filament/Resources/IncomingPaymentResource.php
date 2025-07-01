<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncomingPaymentResource\Pages;
use App\Filament\Resources\IncomingPaymentResource\RelationManagers;
use App\Models\Agent;
use App\Models\Bank;
use App\Models\IncomingPayment;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IncomingPaymentResource extends Resource
{
    protected static ?string $model = IncomingPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-circle';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $label= "вхідну заявку";

    protected static ?string $navigationLabel = "Вхідні заявки";

    protected static ?string $pluralLabel = "Вхідні заявки";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Дані відправника')
                            ->schema([
                                Select::make('status')
                                    ->required()
                                    ->label('Статус')
                                    ->default('new')
                                    ->options(IncomingPayment::getStatuses()),

                                Select::make('sender_bank')
                                    ->required()
                                    ->label('Банк')
                                    ->options(Bank::active()->pluck('name', 'name')),

                                TextInput::make('sender_card')
                                    ->required()
                                    ->rule('creditcard')
                                    // ->mask('9999 9999 9999 9999')
                                    ->label('Номер карти'),

                                TextInput::make('sum')
                                    ->numeric()
                                    ->required()
                                    ->minValue('0')
                                    ->step(0.01)
                                    ->label('Сума'),

                                TextInput::make('sender_name')
                                    ->required()
                                    ->label('Імʼя'),

                            ])->columns(2),
                        Tab::make('Дані отримувача')
                            ->schema([
                                Select::make('agent_id')
                                    ->options(Agent::active()->pluck('name', 'id'))
                                    ->label('Агент'),

                                TextInput::make('recipient_name')
                                    ->readOnly()
                                    ->label('Імʼя власника картки'),

                                TextInput::make('recipient_bank')
                                    ->readOnly()
                                    ->label('Банк'),

                                TextInput::make('recipient_iban')
                                    ->rule('iban')
                                    ->label('IBAN')
                                    ->readOnly(),

                                TextInput::make('recipient_card')
                                    ->readOnly()
                                    ->rule('creditcard')
                                    // ->mask('9999 9999 9999 9999')
                                    ->label('Номер карти'),

                                TextInput::make('incoming_sum')
                                    ->readOnly()
                                    ->label('Сума')
                                    ->numeric()
                                    ->minValue('0')
                                    ->step(0.01),

                            ])
                            ->columns(2),
                        ])->columnSpanFull()
                    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListIncomingPayments::route('/'),
            'create' => Pages\CreateIncomingPayment::route('/create'),
            'edit' => Pages\EditIncomingPayment::route('/{record}/edit'),
        ];
    }
}
