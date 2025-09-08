<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class kelas extends Model
{
    use HasFactory;
    protected $table = 'datakelas';
    protected $primaryKey = 'idkelas';
    public function walas()
    {
        return $this->belongsTo(walas::class, 'idwalas');
    }
    public function siswa()
    {
        return $this->belongsTo(siswa::class, 'idsiswa');
    }

}
