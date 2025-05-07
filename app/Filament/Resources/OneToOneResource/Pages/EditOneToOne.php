<?php

namespace App\Filament\Resources\OneToOneResource\Pages;

use App\Filament\Resources\OneToOneResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOneToOne extends EditRecord
{
    protected static string $resource = OneToOneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
} 