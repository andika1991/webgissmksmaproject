<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;

class GeoJsonController extends Controller
{
    public function exportSekolah()
    {
        $sekolah = Sekolah::all();

        $features = [];

        foreach ($sekolah as $s) {
            $features[] = [
                "type" => "Feature",
                "properties" => [
                    "nama_sekolah" => $s->nama_sekolah,
                    "desa" => $s->desa,
                    "kecamatan" => $s->kec,
                    "kabupaten" => $s->kab,
                    "alamat_lengkap" => $s->alamat_lengkap,
                    "foto_sekolah" => $s->foto_sekolah,
                    "foto_kantin" => $s->foto_kantin,
                    "jumlah_siswa" => $s->jumlah_siswa,
                    "jumlah_guru" => $s->jumlah_guru,
                  "url_google_maps" => $s->url_google_maps,
                  "foto_lokal"=>$s->foto_lokal,
                ],
                "geometry" => [
                    "type" => "Point",
                    "coordinates" => [
                        (float)$s->longitude,
                        (float)$s->latitude
                    ]
                ]
            ];
        }

        $geojson = [
            "type" => "FeatureCollection",
            "features" => $features
        ];

        return response()->json($geojson);
    }
}
