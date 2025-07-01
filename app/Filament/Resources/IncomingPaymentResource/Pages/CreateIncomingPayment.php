<?php

namespace App\Filament\Resources\IncomingPaymentResource\Pages;

use App\Filament\Resources\IncomingPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateIncomingPayment extends CreateRecord
{
    protected static string $resource = IncomingPaymentResource::class;
}
