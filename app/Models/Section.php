<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    protected $fillable = [
        'class_id',
        'name',
        'user_id',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'class_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class); // Relasi ke model User
    }
}
