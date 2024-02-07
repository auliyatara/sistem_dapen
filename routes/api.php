<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleAccessController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\AktifitasController;
use App\Http\Controllers\Api\ApprovalAktifitasController;
use App\Http\Controllers\Api\RiwayatController;
use App\Http\Controllers\Api\InformasiTambahanController;
use App\Http\Controllers\Api\KepesertaanController;
use App\Http\Controllers\Api\KepesertaanDokumenController;
use App\Http\Controllers\Api\KepesertaanLinkController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\NotifikasiController;
use App\Http\Controllers\Api\InvestasiController;
use App\Http\Controllers\Api\KeuanganController;
use App\Http\Controllers\Api\KeuanganBulanController;
use App\Http\Controllers\Api\OneTimeRunController;
use App\Http\Controllers\Api\MenuStorageController;
use App\Http\Controllers\Api\InvestasiSubdataController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|

    Route::controller(UserController::class)->name('users.')->prefix('users')->group(function () {
        $route = ['index', 'show', 'store','update']; 
        $id = [false,true,false,true]; 
         
        foreach ($route as $key=>$value) {
            $url = ($id[$key] == true)? $value.'/{id}':$value;
            Route::any($url=='index'?'':'/'.$url, $value)->name($value);
        }
    });
*/

Route::post('/login', [LoginController::class, 'loginapi']);

Route::middleware(['check-auth'])->group(function () {
    Route::controller(AktifitasController::class)->name('aktifitas_api.')->prefix('aktifitas_api')->group(function () {
        Route::any('/updatealternate', 'updatealternate')->name('updatealternate');
    });

    Route::controller(UserController::class)->name('users_api.')->prefix('users_api')->group(function () {
        Route::any('/updatealternate', 'updatealternate')->name('updatealternate');
    });

    Route::controller(KepesertaanController::class)->name('kepesertaan_api_count.')->prefix('kepesertaan_api_count')->group(function () {
        Route::any('/count', 'count')->name('count');
    });

    Route::any('/printPdf', [ProgramController::class, 'generatepdf'])->name('program_api.pdf');
    Route::any('/printExcel', [ProgramController::class, 'generateexcel'])->name('program_api.excel');
    Route::get('/role_api/read/{role_api}', [RoleController::class, 'read'])->name('role_api.read');
    Route::get('/program_api/programList', [ProgramController::class, 'programList'])->name('program_api.programList');

    // Default Route Pattern
    Route::resource('dashboard_api', DashboardController::class)->only(['index']);
    Route::resource('profile_api', ProfileController::class)->only(['index']);
    Route::resource('users_api', UserController::class)->except(['create','edit']);
    Route::resource('roleaccess_api', RoleAccessController::class)->except(['create','edit']);
    Route::resource('role_api', RoleController::class)->except(['create','edit']);
    Route::resource('program_api', ProgramController::class)->except(['create','edit']);
    Route::resource('aktifitas_api', AktifitasController::class)->except(['create','edit']);
    Route::resource('approval_aktifitas_api', ApprovalAktifitasController::class)->only(['show']);
    Route::resource('riwayat_api', RiwayatController::class)->except(['create','edit']);
    Route::resource('informasi_tambahan_api', InformasiTambahanController::class)->except(['create','edit']);
    Route::resource('kepesertaan_api', KepesertaanController::class)->except(['create','edit','update']);
    Route::resource('kepesertaan_link_api', KepesertaanLinkController::class)->only(['index','update']);
    Route::resource('kepesertaan_dokumen_api', KepesertaanDokumenController::class)->except(['create','edit']);
    Route::resource('menu_api', MenuController::class)->except(['create','edit']);
    Route::resource('notifikasi_api', NotifikasiController::class)->only(['index','update']);
    Route::resource('investasi_api', InvestasiController::class)->except(['create','edit','update']);
    Route::resource('keuangan_api', KeuanganController::class)->except(['create','edit']);
    Route::resource('keuangan_bulan_api', KeuanganBulanController::class)->only(['store','show']);
    Route::resource('menu_storage_api', MenuStorageController::class)->only(['index','update']);
    Route::resource('investasi_subdata_api', InvestasiSubdataController::class)->only(['show']);

    // Adjustment of update route
    Route::post('/investasi_api/{investasi_api}', [InvestasiController::class, 'update'])->name('investasi_api.update');
    Route::post('/aktifitas_api/{aktifitas_api}', [AktifitasController::class, 'update'])->name('aktifitas_api.update');
    Route::post('/kepesertaan_api/{kepesertaan_api}', [KepesertaanController::class, 'update'])->name('kepesertaan_api.update');
    Route::post('/investasi_subdata_api/{investasi_subdata_api}', [InvestasiSubdataController::class, 'update'])->name('investasi_subdata_api.update');
});


/*
|--------------------------------------------------------------------------
| Run for trigger only
|--------------------------------------------------------------------------
| The First Route to set createAt in Program Controller
| The Second Route to reinit menus with access view and edit
*/
// Route::controller(ProgramController::class)->name('program_api.')->prefix('program_api')->group(function () {
//     Route::any('/setAllCreatedAt', 'setAllCreatedAt')->name('setAllCreatedAt');
// });
// Route::resource('one_time_run_api', OneTimeRunController::class)->only(['index']);



/*
|--------------------------------------------------------------------------
| UNUSED ROUTE
|--------------------------------------------------------------------------
| I think, I dont use these route below anymore. but if the next developer need this just uncomment
*/
// Route::controller(KepesertaanController::class)->name('kepesertaan_api.')->prefix('kepesertaan_api')->group(function () {
//     Route::any('/updatealternate', 'updatealternate')->name('updatealternate');
// });

