<?php

namespace App\Filament\Resources\OutgoingPaymentResource\Pages;

use App\Filament\Resources\OutgoingPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOutgoingPayment extends CreateRecord
{
    protected static string $resource = OutgoingPaymentResource::class;
}
