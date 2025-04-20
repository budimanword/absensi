<?php

namespace App\Filament\Resources\KehadiranResource\Pages;


use App\Filament\Resources\KehadiranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;


class ListKehadirans extends ListRecords
{
    protected static string $resource = KehadiranResource::class;

    protected function getHeaderActions(): array
    {
        $queryString = request()->getQueryString();
        return [
            

            Actions\Action::make('export') 
            ->url(route('export') . ($queryString ? '?' . $queryString : '')),

            Actions\CreateAction::make(),

        ];
    }
}
