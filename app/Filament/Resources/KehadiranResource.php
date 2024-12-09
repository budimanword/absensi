<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KehadiranResource\Pages;
use App\Filament\Resources\KehadiranResource\RelationManagers;
use App\Models\Kehadiran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Radio;



class KehadiranResource extends Resource
{
    protected static ?string $model = Kehadiran::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('siswa_id')
                    ->label('Siswa')
                    ->relationship('siswa', 'name')
                     // Menghubungkan ke model `Siswa` untuk memilih siswa berdasarkan nama
                    ->required()
                    ->searchable(), // Menambahkan fitur pencarian untuk memudahkan pemilihan siswa

                DatePicker::make('tanggal')
                    ->label('Tanggal Kehadiran')
                    ->default(now()->toDateString()) // Tanggal default adalah hari ini
                    ->required(),

                TimePicker::make('check_in')
                    ->label('Waktu Check-in')
                    ->default(now()->toTimeString()) // Waktu default adalah sekarang
                    ->required(),

                Radio::make('status')
                    ->label('Status Kehadiran')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                        'alpa' => 'Alpa',
                    ])
                    ->default('hadir') // Default status
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('siswa.name'),
                Tables\Columns\TextColumn::make('siswa.barcode'),
                Tables\Columns\TextColumn::make('kelas.name'),
                Tables\Columns\TextColumn::make('section.name'),
                Tables\Columns\TextColumn::make('status') ,
                Tables\Columns\TextColumn::make('check_in') 
                
                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKehadirans::route('/'),
            'create' => Pages\CreateKehadiran::route('/create'),
            'edit' => Pages\EditKehadiran::route('/{record}/edit'),
        ];
    }
}
