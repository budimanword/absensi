<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Kehadiran extends Model
{
    use HasFactory;
    protected $fillable = [
        'siswa_id',
        'tanggal',
        'check_in',
        'status',
        'created_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kehadiran) {
            $kehadiran->created_by = Auth::id(); // Otomatis setel created_by
        });
    }

    // Relasi ke model Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    // Mengambil kelas dari siswa
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'siswa_id', 'id');
    }

    // Mengambil section dari siswa
    public function section()
    {
        return $this->siswa()->belongsTo(Section::class, 'section_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
