<?php

namespace App\Filament\Resources\OutgoingPaymentResource\Pages;

use App\Filament\Resources\OutgoingPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOutgoingPayments extends ListRecords
{
    protected static string $resource = OutgoingPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
