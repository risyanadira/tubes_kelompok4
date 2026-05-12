<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\CoaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\PDFController;


/* LOGIN */
Route::get('/', [AuthController::class, 'showLoginForm']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

/* LOGOUT */
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

/* DASHBOARD */
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth');

/* COA */
Route::resource('coa', CoaController::class);

/* PENJUALAN */
Route::get('/penjualan', [PenjualanController::class, 'index']);
Route::post('/penjualan/tambah/{id}', [PenjualanController::class, 'tambah']);
Route::post('/penjualan/simpan', [PenjualanController::class, 'simpan']);
Route::get('/penjualan/nota/{id}', [PenjualanController::class, 'nota']);

/* MIDTRANS (optional) */
Route::post('/midtrans/token', [MidtransController::class, 'token']);

/* PDF */
Route::get('/contohpdf', [PDFController::class, 'contohpdf']);

Route::get('/pembayaran/{id}', [PenjualanController::class, 'bayar']);

Route::get('/midtrans/{id}', [MidtransController::class, 'bayar']);

Route::get('/pembayaran-success/{id}', [MidtransController::class, 'success']);