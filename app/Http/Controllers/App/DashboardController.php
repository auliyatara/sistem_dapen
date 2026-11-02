<?php

namespace App\Http\Controllers\App;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Kepesertaan;

class DashboardController extends Controller
{
    public function index(){
        $iuranYear = now('Asia/Jakarta')->year;
        $query = Kepesertaan::where('kepesertaan_status_kepesertaan', 'Peserta')
            ->whereBetween('kepesertaan_terhitung_mulai_tanggal_pensiun', [today()->toDateString(), today()->addYear()->toDateString()]);
        $upcomingCount = (clone $query)->count();
        $upcoming = $query->orderBy('kepesertaan_terhitung_mulai_tanggal_pensiun')->limit(5)
            ->get(['kepesertaan_nama', 'kepesertaan_status_jabatan', 'kepesertaan_terhitung_mulai_tanggal_pensiun']);
        return view('layout.Dashboard.index', compact('iuranYear', 'upcomingCount', 'upcoming'));
    }
}
