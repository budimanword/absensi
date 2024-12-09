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
        Schema::create('siswas', function (Blueprint $table) {
                $table->id(); // Primary key
                $table->string('barcode', 255)->unique(); // Barcode unik
                $table->string('name', 255); // Nama siswa
                $table->enum('gender', ['L', 'P']); // Jenis kelamin
                $table->date('birth_date'); // Tanggal lahir siswa
                $table->unsignedBigInteger('class_id'); // Foreign key ke tabel kelas
                $table->unsignedBigInteger('section_id'); // Foreign key ke tabel sections
                $table->enum('status', ['active', 'inactive'])->default('active'); // Status siswa
                $table->timestamps(); // Kolom created_at dan updated_at
    
                // Foreign key constraint
                $table->foreign('class_id')->references('id')->on('kelas')->onDelete('cascade');
                $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
                // $table->foreign('siswa_id')->references('id')->on('siswas')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
