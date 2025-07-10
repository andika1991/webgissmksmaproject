<?php

namespace App\Imports;

use App\Models\SekolahSmk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SekolahSmkImport implements ToModel, WithHeadingRow
{
 public function model(array $row)
{
    return new SekolahSmk([
        // Sesuaikan nama key dengan header Excel, huruf kecil semua
        'nama_sekolah'    => $row['nama_sekolah'] ?? null,
        'latitude'        => $row['latitude'] ?? null,         // dari "Latitude"
        'longitude'       => $row['longitude'] ?? null,        // dari "Longitude"
        'desa'            => $row['desa'] ?? null,
        'kec'             => $row['kec'] ?? null,
        'kab'             => $row['kab'] ?? null,
        'alamat_lengkap'  => $row['alamat_lengkap'] ?? null,   // dari "Alamat_Sekolah"
        'foto_sekolah'    => $row['foto_sekolah'] ?? null,     // dari "Foto_Sekolah"
        'jumlah_guru'     => $row['jumlah_guru'] ?? null,      // dari "Jumlah_Guru"
        'jumlah_siswa'    => $row['jumlah_siswa'] ?? null,     // dari "Jumlah_Siswa"
        'Url_Google_maps' => $row['url_google_maps'] ?? null,  // dari "Url_Google_maps"
        'Foto_Lokal'      => $row['foto_lokal'] ?? null,       // dari "Foto_Lokal"
    ]);
}

}
