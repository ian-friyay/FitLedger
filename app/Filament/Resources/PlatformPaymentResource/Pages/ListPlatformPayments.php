<?php

namespace App\Filament\Resources\PlatformPaymentResource\Pages;

use App\Filament\Resources\PlatformPaymentResource;
use Filament\Resources\Pages\ListRecords;

class ListPlatformPayments extends ListRecords
{
    protected static string $resource = PlatformPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
