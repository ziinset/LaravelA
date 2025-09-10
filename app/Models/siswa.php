<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class siswa extends Model
{
    use HasFactory;
    protected $table = 'datasiswa';
    protected $primaryKey = 'idsiswa';
    protected $fillable = [
        'id',
        'nama',
        'tb',
        'bb'
    ];

    public function admin()
    {
        return $this->belongsTo(admin::class, 'id');
    }
    
    /**
     * Get the kelas records associated with the student.
     */
    public function kelas()
    {
        return $this->hasMany(\App\Models\kelas::class, 'idsiswa', 'idsiswa');
    }
}
