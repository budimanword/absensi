<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    

    protected $fillable = [
        'name',
        'tahun_ajaran'
    ];

    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'class_id'); // Relasi one-to-many ke siswa
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'class_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class); // Pastikan 'user_id' adalah foreign key yang benar
    }

}
