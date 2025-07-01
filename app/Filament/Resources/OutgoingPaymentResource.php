<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OutgoingPaymentResource\Pages;
use App\Filament\Resources\OutgoingPaymentResource\RelationManagers;
use App\Models\Agent;
use App\Models\Bank;
use App\Models\OutgoingPayment;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\SelectAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OutgoingPaymentResource extends Resource
{
    protected static ?string $model = OutgoingPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-circle';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $label= "вихідну заявку";

    protected static ?string $navigationLabel = "Вихідні заявки";

    protected static ?string $pluralLabel = "Вихідні заявки";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Дані отримувача')
                            ->schema([
                                Select::make('status')
                                    ->required()
                                    ->label('Статус')
                                    ->default('new')
                                    ->options(OutgoingPayment::getStatuses()),

                                Select::make('recipient_bank')
                                    ->required()
                                    ->label('Банк')
                                    ->options(Bank::active()->pluck('name', 'name')),

                                TextInput::make('recipient_card')
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

                                TextInput::make('recipient_iban')
                                    ->rule('iban')
                                    ->label('IBAN'),


                                TextInput::make('recipient_name')
                                    ->required()
                                    ->label('Імʼя'),

                            ])->columns(2),
                        Tab::make('Дані відправника')
                            ->schema([
                                Select::make('agent_id')
                                    ->options(Agent::active()->pluck('name', 'id'))
                                    ->label('Агент'),

                                // TextInput::make('card_user_name')
                                //     ->readOnly()
                                //     ->label('Імʼя власника картки'),

                                TextInput::make('bank')
                                    ->readOnly()
                                    ->label('Банк'),

                                TextInput::make('card')
                                    ->readOnly()
                                    ->required()
                                    ->rule('creditcard')
                                    // ->mask('9999 9999 9999 9999')
                                    ->label('Номер карти'),

                                TextInput::make('fee')
                                    ->readOnly()
                                    ->label('Комісія')
                                    ->numeric()
                                    ->minValue('0')
                                    ->step(0.01),

                                TextInput::make('incoming_sum')
                                    ->readOnly()
                                    ->label('Сума')
                                    ->numeric()
                                    ->minValue('0')
                                    ->step(0.01),

                                SpatieMediaLibraryFileUpload::make('outgoing_payment_files')
                                    ->label('Файли')
                                    ->collection('outgoing_payment_files')
                                    ->disk('outgoing_payment_files')
                                    ->openable()
                                    ->previewable(false)
                                    ->downloadable(),
                            ])
                            ->columns(2),
                        ])->columnSpanFull()
                    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('agent.name')
                    ->label('Агент')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('recipient_bank')
                    ->label('Банк'),

                TextColumn::make('sum')
                    ->label('Сума'),

                SelectColumn::make('status')
                    ->label('Статус')
                    ->options(OutgoingPayment::getStatuses())
                    ->extraAttributes(['style' => 'width: 120px; min-width: 120px; max-width: 120px']),

                TextColumn::make('created_at')
                    ->label('Дата створення')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Дата редагування')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options(OutgoingPayment::getStatuses()),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')->label('Від'),
                        DatePicker::make('created_until')->label('До'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
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
            'index' => Pages\ListOutgoingPayments::route('/'),
            'create' => Pages\CreateOutgoingPayment::route('/create'),
            'edit' => Pages\EditOutgoingPayment::route('/{record}/edit'),
        ];
    }
}
