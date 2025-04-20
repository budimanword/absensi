<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\SiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewQrCode extends ViewRecord
{
    protected static string $resource = SiswaResource::class;
    protected static string $view = 'filament.pages.student-resource.pages.view-qr-code';

    protected function getHeaderActions(): array
    {
        return [];
    }
}
