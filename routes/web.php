<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\App\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\App\UserController;
use App\Http\Controllers\App\RoleAccessController;
use App\Http\Controllers\App\ProgramController;
use App\Http\Controllers\App\ProfileController;
use App\Http\Controllers\App\AktifitasController;
use App\Http\Controllers\App\AktifitasApprovalController;
use App\Http\Controllers\App\RiwayatController;
use App\Http\Controllers\App\InformasiTambahanController;
use App\Http\Controllers\App\KepesertaanController;
use App\Http\Controllers\App\KepesertaanLinkController;
use App\Http\Controllers\App\InvestasiController;
use App\Http\Controllers\App\KeuanganController;

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
    if (\Auth::user()) {
        return redirect()->route('dashboard.index');
   }
   return view('auth.login');
});

Auth::routes();
Route::middleware(['check-auth'])->group(function () {
    Route::resource('dashboard', DashboardController::class)->only(['index']);
    Route::resource('users', UserController::class)->only(['index']);
    Route::resource('program', ProgramController::class)->only(['index']);
    Route::resource('profile', ProfileController::class)->only(['index']);
    Route::resource('aktifitas', AktifitasController::class)->only(['index']);
    Route::resource('aktifitas_approval', AktifitasApprovalController::class)->only(['index']);
    Route::resource('riwayat', RiwayatController::class)->only(['index']);
    Route::resource('informasi_tambahan', InformasiTambahanController::class)->only(['index']);
    Route::resource('kepesertaan', KepesertaanController::class)->only(['index']);
    Route::resource('kepesertaan_link', KepesertaanLinkController::class)->only(['index']);
    Route::resource('roleaccess', RoleAccessController::class)->only(['index']);
    Route::resource('investasi', InvestasiController::class)->only(['index']);
    Route::resource('keuangan', KeuanganController::class)->only(['index']);
});





