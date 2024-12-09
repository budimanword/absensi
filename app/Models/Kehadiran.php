<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    use HasFactory;

    // protected $table = 'kehadiran';

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'check_in',
        'status',
    ];

    // Relasi ke model Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'siswa_id');
    }


    public function section()
    {
        return $this->belongsTo(Section::class, 'siswa_id');
    }

    

    
}
