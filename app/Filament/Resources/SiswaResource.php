<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Section;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use LaraZeus\Qr\Components\Qr;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextEntry;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\PopoverEntry;
use App\Filament\Resources\SiswaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SiswaResource\Pages\EditSiswa;
use App\Filament\Resources\SiswaResource\Pages\ListSiswas;
use App\Filament\Resources\SiswaResource\RelationManagers;
use App\Filament\Resources\SiswaResource\Pages\CreateSiswa;






class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Master Siswa';
    protected static ?string $pluralModelLabel = 'Master Siswa';
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $slug = 'master-siswa';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('')->schema([
                TextInput::make('nisn')
                    ->label('NISN')
                    ->required()
                    ->unique('siswas', 'nisn', ignoreRecord: true) // Validasi unik
                    ->maxLength(255), 
                   


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

                DatePicker::make('birth_date')->label('Tanggal Lahir')->required()->format('Y-m-d'),

                Select::make('tahun_ajaran')
                    ->label('Tahun Ajaran')
                    ->options(
                        Kelas::query()
                            ->distinct()
                            ->pluck('tahun_ajaran', 'tahun_ajaran') // Menampilkan daftar tahun ajaran unik
                    )
                    ->placeholder('Pilih Tahun Ajaran')
                    ->required()
                    ->reactive(), // Membuat nilai ini bisa digunakan untuk memfilter dropdown lain

                Select::make('class_id')
                    ->label('Kelas')
                    ->required()
                    ->relationship('kelas', 'name', function ($query, callable $get) {
                        $tahunAjaran = $get('tahun_ajaran');
                        if ($tahunAjaran) {
                            $query->where('tahun_ajaran', $tahunAjaran); // Filter kelas berdasarkan tahun ajaran
                        }
                    })
                    ->placeholder('Pilih Kelas')
                    ->reactive()
                    ->disabled(fn (callable $get) => !$get('tahun_ajaran')), // Nonaktifkan jika tahun ajaran belum dipilih

                Select::make('section_id')
                    ->label('Select Section')
                    ->options(function (callable $get) {
                        $classId = $get('class_id');
                        if ($classId) {
                            return Section::where('class_id', $classId)->pluck('name', 'id')->toArray(); // Filter section berdasarkan kelas
                        }
                    })
                    ->placeholder('Pilih Section')
                    ->required()
                    ->disabled(fn (callable $get) => !$get('class_id'))// Nonaktifkan jika kelas belum dipilih
                    ->reactive(),

                  Select::make('status')
                         ->label('Status')
                         ->required()
                         ->options(['active' => 'Aktif','inactive' => 'Tidak Aktif',])
                         ->default('active'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nisn')->label('NISN')->searchable()->sortable()->visible(auth()->user()->email == 'admin2@gmail.com'),

                Tables\Columns\TextColumn::make('name')->label('Nama Siswa')->searchable()->sortable(),

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
            ])

            ->actions([
                
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),


                Action::make('Qr Code')
                    ->icon('heroicon-o-qr-code')
                    ->url(fn(Siswa $record): string => static::getUrl('qr-code', ['record' => $record->id])),

                    



               
                
            
            
            ]);
    }

    public static function getRelations(): array
    {
        return [
                //
            ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
            'qr-code' => Pages\ViewQrCode::route('/{record}/qr-code'),
        ];
    }
}
