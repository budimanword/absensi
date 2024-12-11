<?php

namespace App\Filament\Resources\KelasResource\Pages;

use Filament\Actions;
use Filament\Pages\Actions\ButtonAction;
use App\Filament\Resources\KelasResource;
use Filament\Resources\Pages\ListRecords;


class ListKelas extends ListRecords
{
    protected static string $resource = KelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            
        ];
    }

    
}
