<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Section;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SectionResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SectionResource\RelationManagers;

class SectionResource extends Resource
{
    protected static ?string $model = Section::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';
    protected static ?string $navigationGroup = 'Master';
    protected static ?int $navigationSort = 2 ;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
            ->label('Section Name')
            ->required()
            ->maxLength(255),

        // Dropdown Tahun Ajaran
        Select::make('tahun_ajaran')
            ->label('Tahun Ajaran')
            ->options(
                \App\Models\Kelas::query()
                    ->distinct()
                    ->pluck('tahun_ajaran', 'tahun_ajaran')
            ) // Menampilkan semua tahun ajaran unik dari tabel kelas
            ->required()
            ->reactive(), // Membuat nilai ini bisa digunakan untuk memfilter dropdown lain

        // Dropdown Kelas
        Select::make('class_id')
            ->label('Class')
            ->options(function (callable $get) {
                $tahunAjaran = $get('tahun_ajaran'); // Ambil nilai dari field `tahun_ajaran`
                return $tahunAjaran
                    ? \App\Models\Kelas::where('tahun_ajaran', $tahunAjaran)->pluck('name', 'id')
                    : [];
            })
            ->required()
            ->reactive() // Membuat dropdown ini bereaksi terhadap perubahan
            ->disabled(fn (callable $get) => !$get('tahun_ajaran')), // Nonaktifkan jika `tahun_ajaran` belum dipilih

        // Dropdown Wali Kelas
        Select::make('user_id')
            ->label('Wali Kelas')
            ->relationship('user', 'name')
            ->searchable()
            ->required(),

                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('kelas.name')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListSections::route('/'),
            'create' => Pages\CreateSection::route('/create'),
            'edit' => Pages\EditSection::route('/{record}/edit'),
        ];
    }
}
