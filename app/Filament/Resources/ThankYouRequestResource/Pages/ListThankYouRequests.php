<?php

namespace App\Filament\Resources\ThankYouRequestResource\Pages;

use App\Filament\Resources\ThankYouRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListThankYouRequests extends ListRecords
{
    protected static string $resource = ThankYouRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
