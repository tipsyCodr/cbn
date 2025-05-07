<?php

namespace App\Filament\Resources\OneToOneResource\Pages;

use App\Filament\Resources\OneToOneResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOneToOne extends CreateRecord
{
    protected static string $resource = OneToOneResource::class;
} 