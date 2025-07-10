<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SekolahSmk extends Model
{
    protected $table = 'sekolahsmk';

    protected $fillable = [
        'nama_sekolah',
        'latitude',
        'longitude',
        'desa',
        'kec',
        'kab',
        'alamat_lengkap',
        'foto_sekolah',
        'jumlah_siswa',
        'jumlah_guru',
        'Url_Google_maps',
        'Foto_Lokal',
    ];
}
