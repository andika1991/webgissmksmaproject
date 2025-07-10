<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    protected $table = 'sekolah';

    protected $fillable = [
        'nama_sekolah',
        'latitude',
        'longitude',
        'desa',
        'kec',
        'kab',
        'alamat_lengkap',
        'foto_sekolah',
        'foto_kantin',
        'jumlah_siswa',
        'jumlah_guru',
         'Url_Google_maps',
        'Foto_Lokal',
    ];
}
