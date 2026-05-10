<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\PenjualanController;

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
    // return view('welcome');
    return view('login');
});

Route::resource('coa', CoaController::class);

// login kasir
Route::get('/depan', [App\Http\Controllers\KeranjangController::class, 'daftarbarang'])->middleware('kasir')->name('depan');
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::get('/penjualan', [PenjualanController::class, 'index']);

Route::post('/penjualan/tambah/{id}', [PenjualanController::class, 'tambah']);

Route::post('/penjualan/simpan', [PenjualanController::class, 'simpan']);

Route::get('/penjualan/nota/{id}', [PenjualanController::class, 'nota']);

Route::get('/dashboard', [PenjualanController::class, 'dashboard']);