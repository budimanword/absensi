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
        Schema::create('sections', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('class_id'); // Foreign key ke tabel kelas
            $table->string('name', 255); // Nama section
            $table->timestamps(); // Kolom created_at dan updated_at
            
            // Foreign key constraint
            $table->foreign('class_id')->references('id')->on('kelas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
