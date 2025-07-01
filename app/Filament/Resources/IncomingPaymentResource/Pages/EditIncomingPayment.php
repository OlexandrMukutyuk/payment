<?php

namespace App\Filament\Resources\IncomingPaymentResource\Pages;

use App\Filament\Resources\IncomingPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIncomingPayment extends EditRecord
{
    protected static string $resource = IncomingPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
