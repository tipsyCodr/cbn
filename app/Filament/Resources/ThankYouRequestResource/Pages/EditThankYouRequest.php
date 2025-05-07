<?php

namespace App\Filament\Resources\ThankYouRequestResource\Pages;

use App\Filament\Resources\ThankYouRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditThankYouRequest extends EditRecord
{
    protected static string $resource = ThankYouRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
