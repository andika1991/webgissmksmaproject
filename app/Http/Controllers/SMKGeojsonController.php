<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SekolahSmk;

class SMKGeojsonController extends Controller
{
    public function index()
    {
        // Ambil semua data
        $sekolah = SekolahSmk::all();

        // Konversi ke GeoJSON
        $geojson = [
            'type' => 'FeatureCollection',
            'features' => []
        ];

        foreach ($sekolah as $s) {
            $feature = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        (float) $s->longitude,
                        (float) $s->latitude
                    ]
                ],
                'properties' => [
                     "nama_sekolah" => $s->nama_sekolah,
                    "desa" => $s->desa,
                    "kecamatan" => $s->kec,
                    "kabupaten" => $s->kab,
                    "alamat_lengkap" => $s->alamat_lengkap,
                    "foto_sekolah" => $s->foto_sekolah,
       
                    "jumlah_siswa" => $s->jumlah_siswa,
                    "jumlah_guru" => $s->jumlah_guru,
                    "Url_Google_maps" => $s->Url_Google_maps,
                    "Foto_Lokal"=>$s->Foto_Lokal,
                    ]
            ];

            $geojson['features'][] = $feature;
        }

        return response()->json($geojson);
    }
}
