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
use App\Http\Controllers\App\IuranController;
use App\Http\Controllers\App\KepegawaianController;
use App\Http\Controllers\App\TotalController;
use App\Http\Controllers\App\KPIController;

// use App\Http\Controllers\App\UserController;

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
    Route::resource('kpi', KPIController::class)->only(['index']);
    Route::get('kpi/export', [KPIController::class, 'export'])->name('kpi.export');
    Route::get('kpi/history', [KPIController::class, 'history'])->name('kpi.history');
    Route::post('kpi/{role}/periods', [KPIController::class, 'storePeriod'])->name('kpi.periods.store');
    Route::post('kpi/roles/{role}/tasks', [\App\Http\Controllers\App\KpiTaskController::class, 'storeForRole'])->name('kpi.tasks.storeForRole');
    Route::post('kpi/periods/{period}/tasks', [\App\Http\Controllers\App\KpiTaskController::class, 'store'])->name('kpi.tasks.store');
    Route::put('kpi/tasks/{task}', [\App\Http\Controllers\App\KpiTaskController::class, 'update'])->name('kpi.tasks.update');
    Route::delete('kpi/tasks/{task}', [\App\Http\Controllers\App\KpiTaskController::class, 'destroy'])->name('kpi.tasks.destroy');
    Route::get('kpi/tasks/{task}/evidence', [\App\Http\Controllers\App\KpiTaskController::class, 'evidence'])->name('kpi.tasks.evidence');
    Route::resource('users', UserController::class)->only(['index']);
    Route::resource('program', ProgramController::class)->only(['index']);
    Route::resource('profile', ProfileController::class)->only(['index']);
    Route::resource('aktifitas', AktifitasController::class)->only(['index']);
    Route::resource('aktifitas_approval', AktifitasApprovalController::class)->only(['index']);
    Route::resource('riwayat', RiwayatController::class)->only(['index']);
    Route::resource('informasi_tambahan', InformasiTambahanController::class)->only(['index']);
    Route::resource('kepesertaan', KepesertaanController::class)->only(['index']);
    Route::post('kepesertaan/import/preview', [\App\Http\Controllers\App\KepesertaanImportController::class, 'preview'])->name('kepesertaan.import.preview');
    Route::get('kepesertaan/import/page', [\App\Http\Controllers\App\KepesertaanImportController::class, 'page'])->name('kepesertaan.import.page');
    Route::post('kepesertaan/import/commit', [\App\Http\Controllers\App\KepesertaanImportController::class, 'commit'])->name('kepesertaan.import.commit');
    Route::post('kepesertaan/import/resolve', [\App\Http\Controllers\App\KepesertaanImportController::class, 'resolve'])->name('kepesertaan.import.resolve');
    Route::resource('kepesertaan_link', KepesertaanLinkController::class)->only(['index']);
    Route::resource('roleaccess', RoleAccessController::class)->only(['index']);
    Route::resource('investasi', InvestasiController::class)->only(['index']);
    Route::resource('keuangan', KeuanganController::class)->only(['index']);
    Route::resource('iuran', IuranController::class)->only(['index']);
    Route::resource('total', TotalController::class)->only(['index']);
    Route::get('total/export', [TotalController::class, 'export'])->name('total.export');
    Route::resource('kepegawaian', KepegawaianController::class)->only(['index']);
});

