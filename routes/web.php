<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Kamar;
use App\Models\Pasien;
use App\Models\Dokter;

use App\Imports\PasienImport;
use App\Imports\DokterImport;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $query = Kamar::select('kamar.id', 'pasien.nama as nama_ps', 'pasien.alamat', 'dokter.nama as nama_dk', 'dokter.jabatan')
        ->join('pasien', 'kamar.id_pasien', '=', 'pasien.id')
        ->join('dokter', 'kamar.id_dokter', '=', 'dokter.id')
        ->get();
        return view('home0111', ['kamar' => $query]);
});

Route::get('/dokter', function () {
    $query = Dokter::select('id', 'nama', 'jabatan')->get();
    return view('dokter0111', ['dokter' => $query]);
});

Route::get('/dokter/tambah', function () {
    return view('dokter_tambah0111');
});

Route::post('/dokter/tambah', function (Request $request) {
    Dokter::create($request->all());
    return redirect('/dokter');
});

Route::get('/dokter/hapus/{id}', function (Request $request) {
    Dokter::find($request->id)->delete();
    return redirect()->back();
});

Route::get('/dokter/edit/{id}', function (Request $request) {
    $query = Dokter::select('id', 'nama', 'jabatan')->where('id', $request->id)->first();
    return view('dokter_ubah0111', ['pasien' => $query]);
});

Route::post('/dokter/edit/{id}', function (Request $request) {
    Dokter::find($request->id)->update([
        'nama' => $request->nama,
        'jabatan' => $request->jabatan
    ]);
    return redirect('/dokter');
});

Route::post('/dokter/import', function (Request $request) {
    $file = $request->file('file');
    $nama_file = rand().$file->getClientOriginalName();
    $file->move('file_dokter', $nama_file);
    Excel::import(new DokterImport, public_path('/file_dokter/'.$nama_file));
    return redirect('/dokter');
});

Route::get('/pasien', function () {
    $query = Pasien::select('id', 'nama', 'alamat')->get();
    return view('pasien0111', ['pasien' => $query]);
});

Route::get('/pasien/tambah', function () {
    return view('pasien_tambah0111');
});

Route::post('/pasien/tambah', function (Request $request) {
    Pasien::create($request->all());
    return redirect('/pasien');
});

Route::get('/pasien/hapus/{id}', function (Request $request) {
    Pasien::find($request->id)->delete();
    return redirect()->back();
});

Route::get('/pasien/edit/{id}', function (Request $request) {
    $query = Pasien::select('id', 'nama', 'alamat')->where('id', $request->id)->first();
    return view('pasien_ubah0111', ['pasien' => $query]);
});

Route::post('/pasien/edit/{id}', function (Request $request) {
    Pasien::find($request->id)->update([
        'nama' => $request->nama,
        'alamat' => $request->alamat
    ]);
    return redirect('/pasien');
});

Route::post('/pasien/import', function (Request $request) {
    $file = $request->file('file');
    $nama_file = rand().$file->getClientOriginalName();
    $file->move('file_pasien', $nama_file);
    Excel::import(new PasienImport, public_path('/file_pasien/'.$nama_file));
    return redirect('/pasien');
});