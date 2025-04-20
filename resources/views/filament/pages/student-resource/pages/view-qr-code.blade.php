<x-filament::page>
    <div class="text-center space-y-4">
        <!-- Judul Halaman -->
        <h1 class="text-lg font-bold">QR Code for {{ $record->name }}</h1>

        <!-- Tampilkan QR Code -->
        <div class="inline-block">
            {!! QrCode::size(300)->generate($record->nisn) !!}
        </div>

        <!-- Tombol Download -->
        <div>
            <a 
                href="{{ route('siswa.download-barcode', ['record' => $record->id]) }}" 
                class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300"
            >
                Download QR Code
            </a>
        </div>
    </div>
</x-filament::page>