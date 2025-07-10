<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\GeoJsonController;
use App\Http\Controllers\MapController;

Route::get('/geojson/sekolahsmk', [\App\Http\Controllers\SMKGeojsonController::class, 'index']);

Route::get('/geojson/sekolah', [GeoJsonController::class, 'exportSekolah']);
Route::get('/peta', [MapController::class, 'showMap']);
Route::get('/petasmk', [MapController::class, 'showMapsmk']);
Route::get('/infosma', [MapController::class, 'infosma']);
Route::get('/infosmk', [MapController::class, 'infosmk']);
Route::get('/gmaps', [MapController::class, 'google']);
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/import-sekolah', [SekolahController::class, 'importForm']);
Route::post('/import-sekolah', [SekolahController::class, 'import'])->name('sekolah.import');
