<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kehadirans', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('siswa_id'); // Foreign key ke tabel siswa
            $table->date('tanggal'); // Tanggal kehadiran
            $table->time('check_in'); // Waktu scan barcode (check-in)
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa'])->default('hadir'); // Status kehadiran siswa
            $table->timestamps(); // Kolom created_at dan updated_at

            // Foreign key constraint
            $table->foreign('siswa_id')->references('id')->on('siswas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadirans');
    }
};
