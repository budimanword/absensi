<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Section;
use Filament\Forms\Form;
use App\Models\Kehadiran;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\KehadiranResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KehadiranResource\RelationManagers;

class KehadiranResource extends Resource
{
    protected static ?string $model = Kehadiran::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Kehadiran';
    protected static ?string $pluralModelLabel = 'Kehadiran';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('siswa_id')
                ->label('Siswa')
                ->relationship('siswa', 'nisn')
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

            Select::make('created_by')
                ->label('User')
                ->options(User::all()->pluck('name', 'id'))
                ->default(auth()->id()) // Menampilkan pengguna yang sedang login
                ->disabled() // Menonaktifkan pilihan, hanya menampilkan pengguna yang sedang login
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('siswa.name')->searchable(),
                Tables\Columns\TextColumn::make('siswa.nisn')->searchable()->label('NISN'),
                Tables\Columns\TextColumn::make('siswa.kelas.name')
    ->label('Kelas')
    ->sortable(), 
                Tables\Columns\TextColumn::make('siswa.section.name')
    ->label('Section')
    ->sortable(),
    Tables\Columns\TextColumn::make('siswa.kelas.tahun_ajaran') // Mengakses tahun ajaran melalui relasi kelas
    ->label('Tahun Ajaran')
    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('check_in'),
                Tables\Columns\TextColumn::make('user.name')->label('Created by'),
                Tables\Columns\TextColumn::make('tanggal')])
            ->defaultSort('created_at', 'desc')
            ->filters([
                // Filter untuk data hari ini
                    Filter::make('Hari Ini')->query(fn(Builder $query) => $query->whereDate('tanggal', Carbon::today())),

                    // Filter untuk data minggu ini
                    Filter::make('Minggu Ini')->query(fn(Builder $query) => $query->whereBetween('tanggal', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])),

                    // Filter untuk data bulan ini
                    Filter::make('Bulan Ini')->query(fn(Builder $query) => $query->whereMonth('tanggal', Carbon::now()->month)),

                    // Filter kustom dengan date picker
                    Filter::make('Custom Date Range')
                        ->form([Forms\Components\DatePicker::make('start_date')->label('Mulai'), Forms\Components\DatePicker::make('end_date')->label('Selesai')])
                        ->query(function (Builder $query, array $data) {
                            if ($data['start_date'] && $data['end_date']) {
                                $query->whereBetween('tanggal', [$data['start_date'], $data['end_date']]);
                            }
                    }),






                    Filter::make('Tahun Ajaran')
    ->form([
        Forms\Components\Select::make('tahun_ajaran')
            ->label('Pilih Tahun Ajaran')
            ->options(function () {
                // Menampilkan tahun ajaran yang unik dari tabel Kelas
                return \App\Models\Kelas::pluck('tahun_ajaran', 'tahun_ajaran')->unique()->toArray();
            })
            ->reactive()  // Menandakan bahwa pilihan ini akan mempengaruhi filter lainnya
    ])
    ->query(function (Builder $query, array $data) {
        // Filter berdasarkan Tahun Ajaran yang dipilih
        if (!empty($data['tahun_ajaran'])) {
            $query->whereHas('siswa.kelas', function (Builder $query) use ($data) {
                $query->where('tahun_ajaran', $data['tahun_ajaran']);
            });
        }
    }),
    Filter::make('Kelas')
    ->form([
        Forms\Components\Select::make('kelas_id')
            ->label('Pilih Kelas')
            ->options(function () {
                // Mengambil semua kelas dan tahun ajaran mereka
                return \App\Models\Kelas::all()->mapWithKeys(function ($kelas) {
                    // Menggabungkan nama kelas dengan tahun ajaran
                    return [$kelas->id => $kelas->name . ' (' . $kelas->tahun_ajaran . ')'];
                })->toArray();
            })
            ->placeholder('Pilih Kelas')  // Menambahkan placeholder
    ])
    ->query(function (Builder $query, array $data) {
        // Filter berdasarkan Kelas yang dipilih
        if (!empty($data['kelas_id'])) {
            $query->whereHas('siswa.kelas', function (Builder $query) use ($data) {
                $query->where('id', $data['kelas_id']);
            });
        }
    }),

    Filter::make('Section')
    ->form([
        Forms\Components\Select::make('section_id')
            ->label('Pilih Section')
            ->options(function () {
                // Mengambil semua section dengan data relasi ke kelas
                return \App\Models\Section::with('kelas')->get()->mapWithKeys(function ($section) {
                    $kelas = $section->kelas; // Mengakses relasi ke kelas
                    $kelasName = $kelas ? $kelas->name : '-'; // Nama kelas
                    $tahunAjaran = $kelas ? $kelas->tahun_ajaran : '-'; // Tahun ajaran dari kelas
                    return [
                        $section->id => "{$section->name} - {$kelasName} ({$tahunAjaran})"
                    ];
                })->toArray();
            })
            ->searchable()  // Menambahkan fitur pencarian
            ->reactive(),   // Membuat dropdown dinamis
    ])
    ->query(function (Builder $query, array $data) {
        // Filter berdasarkan Section yang dipilih
        if (!empty($data['section_id'])) {
            $query->whereHas('siswa.section', function (Builder $query) use ($data) {
                $query->where('id', $data['section_id']);
            });
        }
    }),

















                ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
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
            'index' => Pages\ListKehadirans::route('/'),
            'create' => Pages\CreateKehadiran::route('/create'),
            'edit' => Pages\EditKehadiran::route('/{record}/edit'),
        ];
    }
}
