<?php

namespace App\Filament\Pages;
use Filament\Pages\Page;
use Filament\Support\Colors\Color;
use App\Models\Kehadiran;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Actions\Modal\Actions\Action;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CreateAbsen extends Page
{
    protected static ?string $model = Kehadiran::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Menu Absensi';

    protected static string $view = 'filament.pages.create-absen';

    public $siswa_id;
    public $tanggal;
    public $check_in;
    public $status;

    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {


        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([


                Fieldset::make('')
                    ->schema([
                        TextInput::make('siswa_id')
                        ->label('Barcodes')
                        ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state) {$this->submit();
                    })
                    ->autofocus(),

                    ]),
            ])

            ->statePath('data')
            ->model(Kehadiran::class);
    }

    public function submit()
{
    $data = $this->form->getState(); // Ambil state dari form

    // Pastikan siswa_id ada dan valid
    $siswa = \App\Models\Siswa::where('nisn', $data['siswa_id'])->first();

    if (!$siswa) {
        session()->flash('error', 'Siswa dengan NISN tersebut tidak ditemukan!');
        return;
    }

    // Assign siswa_id yang valid
    $data['siswa_id'] = $siswa->id;

    // Assign default values for other fields
    $data['tanggal'] = $data['tanggal'] ?? now()->toDateString();
    $data['check_in'] = $data['check_in'] ?? now()->toTimeString();
    $data['status'] = $data['status'] ?? 'hadir';

    // Create Kehadiran record
    Kehadiran::create($data);

    session()->flash('success', 'Data berhasil disimpan!');
    return redirect()->route('filament.pages.create-absen');
}

}
