<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Siswa;
use App\Models\Section;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SiswaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SiswaResource\Pages\EditSiswa;
use App\Filament\Resources\SiswaResource\Pages\ListSiswas;
use App\Filament\Resources\SiswaResource\RelationManagers;
use App\Filament\Resources\SiswaResource\Pages\CreateSiswa;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('barcode')
                ->label('Barcode')
                ->default(function () {
                    return Str::random(18); // Menghasilkan string acak 16 karakter
                })
                ->required()
                ->unique('siswas', 'barcode', ignoreRecord: true) // Validasi unik
                ->maxLength(255), // Maksimal panjang karakter
                
    
            TextInput::make('name')
                ->label('Nama Siswa')
                ->required()
                ->maxLength(255),
    
            Select::make('gender')
                ->label('Jenis Kelamin')
                ->required()
                ->options([
                    'L' => 'Laki-laki',
                    'P' => 'Perempuan',
                ]),
    
            DatePicker::make('birth_date')
                ->label('Tanggal Lahir')
                ->required()
                ->format('Y-m-d'),
    
            Select::make('class_id')
                ->label('Kelas')
                ->required()
                ->relationship('kelas', 'name') // Relasi dengan tabel kelas
                ->placeholder('Pilih Kelas')
                ->reactive(),

            
            Select::make('section_id')
                ->label('Select Section')
                ->options(function (callable $get) {
                    $classId = $get('class_id');

                    if ($classId) {
                        return Section::where('class_id', $classId)->pluck('name', 'id')->toArray();
                    }
                }),
            

            
    
            Forms\Components\Select::make('status')
                ->label('Status')
                ->required()
                ->options([
                    'active' => 'Aktif',
                    'inactive' => 'Tidak Aktif',
                ])
                ->default('active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('barcode')
                    ->label('Barcode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->formatStateUsing(function ($state) {
                        return $state === 'L' ? 'Laki-laki' : 'Perempuan';
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('birth_date')
                    ->label('Tanggal Lahir')
                    ->date('Y-m-d') // Format tanggal
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas.name')
                    ->label('Kelas') // Relasi ke nama kelas
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('section.name')
                    ->label('Section') // Relasi ke nama kelas
                    ->searchable()
                    ->sortable(),
                
                

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(function ($state) {
                        return $state === 'active' ? 'Aktif' : 'Tidak Aktif';
                    })
                    ->sortable(),

                
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
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
        ];
    }
}
