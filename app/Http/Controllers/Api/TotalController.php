<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TotalController extends Controller
{
    public function index(Request $request)
    {
        $latestYear = (int) DB::table('kepesertaan_iurans')
            ->where('kepesertaan_iuran_type', 'iuran_total')
            ->whereNotNull('kepesertaan_iuran_jumlah')
            ->max('kepesertaan_iuran_tahun');
        $year = (int) $request->input('year', $latestYear ?: now()->year);
        $iuranPerBulan = DB::table('kepesertaan_iurans as iuran')
            ->join('kepesertaans as peserta', 'peserta.kepesertaan_id', '=', 'iuran.kepesertaan_iuran_kepesertaan_id')
            ->where('iuran.kepesertaan_iuran_tahun', $year)
            ->where('iuran.kepesertaan_iuran_type', 'iuran_total')
            ->whereNotNull('iuran.kepesertaan_iuran_jumlah')
            ->whereIn('peserta.kepesertaan_status_jabatan', ['Dosen', 'Guru Besar', 'Karyawan'])
            ->selectRaw('iuran.kepesertaan_iuran_bulan as bulan, SUM(iuran.kepesertaan_iuran_jumlah * 0.075) as iuran_peserta')
            ->groupBy('iuran.kepesertaan_iuran_bulan')
            ->pluck('iuran_peserta', 'bulan');

        $bulan = collect(range(1, 12))->map(fn ($nomor) => [
            'bulan' => $nomor,
            'iuran_peserta' => (float) ($iuranPerBulan[$nomor] ?? 0),
        ]);

        return $this->response([
            'tahun' => $year,
            'bulan' => $bulan,
            'total_iuran_peserta' => $bulan->sum('iuran_peserta'),
        ]);
    }
}
