<?php

namespace App\Http\Controllers;

class MapController extends Controller
{
    public function showMap()
    {
        return view('peta');
    }

     public function showMapsmk()
    {
        return view('petasmk');
    }

        public function infosma()
    {
        return view('infosma');
    }

     public function infosmk()
    {
        return view('infosmk');
    }


         public function google()
    {
        return view('petagooglemaps');
    }
}
