<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class walas extends Model
{
    use HasFactory;
    protected $table = 'datawalas';
    protected $primaryKey = 'idwalas';
    protected $fillable = ['jenjang', 'namakelas', 'tahunajaran', 'idguru'];
    public function guru()
    {
        return $this->belongsTo(guru::class, 'idguru');
    }
    public function kelas()
    {
        return $this->hasMany(kelas::class, 'idwalas');
    }

    public function kbm()
    {
    return $this->hasMany(kbm::class);
    }

}
