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
            $table->id(); 
            $table->unsignedBigInteger('siswa_id');
            $table->date('tanggal'); 
            $table->time('check_in'); 
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa'])->default('hadir'); // Status kehadiran siswa
            $table->unsignedBigInteger('created_by'); // Tambahkan kolom created_by
            $table->timestamps(); 

            $table->foreign('siswa_id')->references('id')->on('siswas')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
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
