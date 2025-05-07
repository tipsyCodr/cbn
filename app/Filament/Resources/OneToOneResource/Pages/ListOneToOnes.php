<?php

namespace App\Filament\Resources\OneToOneResource\Pages;

use App\Filament\Resources\OneToOneResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOneToOnes extends ListRecords
{
    protected static string $resource = OneToOneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
} 