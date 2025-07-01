<?php

namespace App\Filament\Resources\IncomingPaymentResource\Pages;

use App\Filament\Resources\IncomingPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIncomingPayments extends ListRecords
{
    protected static string $resource = IncomingPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
