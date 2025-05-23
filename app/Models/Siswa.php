<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Siswa extends Model
{
    use HasFactory;

    protected $casts = [
        'options' => 'array',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'class_id', 'id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }
    


    protected $fillable = [
        'nisn',      // Barcode unik siswa
        'name',         // Nama siswa
        'gender',       // Jenis kelamin
        'birth_date',   // Tanggal lahir siswa
        'class_id',  
        'section_id',   // ID kelas (relasi ke tabel kelas)
        'status',       // Status siswa (active/inactive)
    ];
    
}
