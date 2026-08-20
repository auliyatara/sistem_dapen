<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IuranController extends Controller
{
    public function index(Request $request)
    {
        $latestYear = (int) DB::table('kepesertaan_iurans')
            ->where('kepesertaan_iuran_type', 'iuran_total')
            ->whereNotNull('kepesertaan_iuran_jumlah')
            ->max('kepesertaan_iuran_tahun');
        $year = (int) $request->input('year', $latestYear ?: now()->year);

        $totalIuran = DB::table('kepesertaan_iurans')
            ->where('kepesertaan_iuran_tahun', $year)
            ->where('kepesertaan_iuran_type', 'iuran_total')
            ->whereNotNull('kepesertaan_iuran_jumlah')
            ->groupBy('kepesertaan_iuran_kepesertaan_id')
            ->selectRaw('kepesertaan_iuran_kepesertaan_id, AVG(kepesertaan_iuran_jumlah) as phdp, SUM(kepesertaan_iuran_jumlah * 0.075) as iuran');

        $iuranPengembangan = DB::table('kepesertaan_iurans')
            ->where('kepesertaan_iuran_tahun', $year)
            ->where('kepesertaan_iuran_type', 'iuran_pengembangan')
            ->whereNotNull('kepesertaan_iuran_jumlah')
            ->groupBy('kepesertaan_iuran_kepesertaan_id')
            ->selectRaw('kepesertaan_iuran_kepesertaan_id, SUM(kepesertaan_iuran_jumlah * 0.075 * (1 + 0.045 / 12)) as iuran_pengembangan');

        $peserta = DB::table('kepesertaans as peserta')
            ->leftJoinSub($totalIuran, 'total', function ($join) {
                $join->on('total.kepesertaan_iuran_kepesertaan_id', '=', 'peserta.kepesertaan_id');
            })
            ->leftJoinSub($iuranPengembangan, 'pengembangan', function ($join) {
                $join->on('pengembangan.kepesertaan_iuran_kepesertaan_id', '=', 'peserta.kepesertaan_id');
            })
            ->whereIn('peserta.kepesertaan_status_jabatan', ['Dosen', 'Guru Besar', 'Karyawan'])
            ->orderBy('peserta.kepesertaan_nama')
            ->selectRaw('peserta.kepesertaan_id, peserta.kepesertaan_nama as nama')
            ->selectRaw('COALESCE(total.phdp, 0) as phdp')
            ->selectRaw('COALESCE(total.iuran, 0) as iuran')
            ->selectRaw('COALESCE(pengembangan.iuran_pengembangan, total.iuran * (1 + 0.045 / 12), 0) as iuran_pengembangan')
            ->get();

        return $this->response([
            'tahun' => $year,
            'total_peserta' => $peserta->count(),
            'peserta' => $peserta->values(),
        ]);
    }
}
