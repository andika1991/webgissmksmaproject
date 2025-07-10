<?php

namespace App\Imports;

use App\Models\Sekolah;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SekolahImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Sekolah([
            'nama_sekolah'    => $row['nama_sekolah'],
            'latitude'        => $row['latitude'],
            'longitude'       => $row['longitude'],
            'desa'            => $row['desa'],
            'kec'       => $row['kec'],
            'kab'       => $row['kab'],
            'alamat_lengkap'  => $row['alamat_lengkap'],
            'foto_sekolah'    => $row['foto_sekolah'],
            'foto_kantin'     => $row['foto_kantin'],
            'jumlah_siswa'    => $row['jumlah_siswa'],
            'jumlah_guru'     => $row['jumlah_guru'],
                   'Url_Google_maps' => $row['url_google_maps'] ?? null,   // dari "Url_Google_maps"
        'Foto_Lokal'      => $row['foto_lokal'] ?? null,  
        ]);
    }
}
