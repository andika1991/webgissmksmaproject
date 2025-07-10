<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\SekolahSmkImport;
use Maatwebsite\Excel\Facades\Excel;

class SekolahController extends Controller
{
    public function importForm()
    {
        return view('import_sekolah'); // Buat form upload di view
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        Excel::import(new SekolahSmkImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data sekolah berhasil diimport!');
    }
}
