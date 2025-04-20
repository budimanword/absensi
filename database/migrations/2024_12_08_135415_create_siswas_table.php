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
            $table->id(); 
            $table->string('nisn', 255)->unique(); 
            $table->string('name', 255); 
            $table->enum('gender', ['L', 'P']); 
            $table->date('birth_date'); 
            $table->unsignedBigInteger('class_id'); 
            $table->unsignedBigInteger('section_id'); 
            $table->enum('status', ['active', 'inactive'])->default('active'); 
            $table->timestamps(); // 
        
        
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
