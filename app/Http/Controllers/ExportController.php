<?php
namespace App\Http\Controllers;

use App\Models\Kehadiran;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        // Filter data sesuai kebutuhan
        $filters = $request->query();
        $query = Kehadiran::query()->with(['siswa', 'kelas', 'siswa.section', 'user']); // Pastikan untuk memuat relasi section melalui siswa

        if (isset($filters['filter']) && $filters['filter'] === 'today') {
            $query->whereDate('tanggal', now()->toDateString());
        }

        $data = $query->get();

        // Generate CSV file as a streamed response
        $response = new StreamedResponse(function () use ($data) {
            $output = fopen('php://output', 'w');

            // Header CSV
            fputcsv($output, [
                'Siswa Name',
                'NISN',
                'Kelas',
                'Section',
                'Status',
                'Check In',
                'Created By',
                'Tanggal',
            ]);

            // Data CSV
            foreach ($data as $row) {
                fputcsv($output, [
                    optional($row->siswa)->name, // Siswa Name
                    "'" . optional($row->siswa)->nisn, // NISN (dengan tanda kutip agar nol tidak hilang)
                    optional($row->kelas)->name, // Kelas
                    optional($row->siswa->section)->name, // Section - akses section melalui siswa
                    $row->status, // Status
                    $row->check_in, // Check In
                    optional($row->user)->name, // Created By
                    $row->tanggal, // Tanggal
                ]);
            }

            fclose($output);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="kehadirans.csv"');

        return $response;
    }
}

