<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarcodeScanController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Filament\Pages\CreateAbsen;
use App\Http\Controllers\ExportController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/siswa/{record}/download-barcode', function ($record) {
    $siswa = \App\Models\Siswa::findOrFail($record);

    // Generate QR Code as PNG
    $qrCodePng = QrCode::format('png')
        ->size(400)
        ->generate($siswa->nisn);

    // Create a GD image from the PNG data
    $image = imagecreatefromstring($qrCodePng);

    // Output the image as JPG
    ob_start();
    imagejpeg($image);
    $qrCodeJpg = ob_get_clean();

    // Clean up GD image resource
    imagedestroy($image);

    // Return the response as a downloadable JPG file
    return response($qrCodeJpg)->header('Content-Type', 'image/jpeg')->header('Content-Disposition', 'attachment; filename="qrcode.jpg"');
})->name('siswa.download-barcode');



//custompage Create absen
Route::get('/create-absen', CreateAbsen::class)->name('filament.pages.create-absen');

Route::get('/export', [ExportController::class, 'export'])->name('export');






