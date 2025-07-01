<?php

namespace App\Filament\Resources\OutgoingPaymentResource\Pages;

use App\Filament\Resources\OutgoingPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOutgoingPayment extends EditRecord
{
    protected static string $resource = OutgoingPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
