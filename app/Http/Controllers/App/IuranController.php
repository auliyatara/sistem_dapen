<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IuranController extends Controller
{
    public function index(){
        $iuranYear = DB::table('kepesertaan_iurans')
            ->where('kepesertaan_iuran_type', 'iuran_total')
            ->whereNotNull('kepesertaan_iuran_jumlah')
            ->max('kepesertaan_iuran_tahun');

        return view('layout.Iuran.index', compact('iuranYear'));
    }
}
