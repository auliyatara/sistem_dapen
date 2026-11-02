<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IuranController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['year' => 'nullable|integer|between:1900,2099', 'participant_id' => 'nullable|string|max:16']);
        $year = (int) $request->input('year', now('Asia/Jakarta')->year);

        if ($request->filled('participant_id')) {
            $participant = DB::table('kepesertaans')->where('kepesertaan_id', $request->input('participant_id'))
                ->whereIn('kepesertaan_status_jabatan', ['Dosen', 'Guru Besar', 'Karyawan'])->first();
            abort_unless($participant, 404);
            $entries = DB::table('kepesertaan_iurans')->where('kepesertaan_iuran_kepesertaan_id', $participant->kepesertaan_id)
                ->where('kepesertaan_iuran_tahun', $year)->whereNotNull('kepesertaan_iuran_jumlah')
                ->whereIn('kepesertaan_iuran_type', ['iuran_total', 'iuran_pengembangan'])->get();
            $hasDevelopment = $entries->contains('kepesertaan_iuran_type', 'iuran_pengembangan');
            $months = collect(range(1, 12))->map(function ($month) use ($entries, $hasDevelopment) {
                $base = $entries->where('kepesertaan_iuran_bulan', $month)->where('kepesertaan_iuran_type', 'iuran_total');
                $development = $entries->where('kepesertaan_iuran_bulan', $month)->where('kepesertaan_iuran_type', 'iuran_pengembangan');
                $iuran = $base->isEmpty() ? null : (float) $base->sum(fn ($entry) => $entry->imported_iuran_peserta ?? ((float) $entry->kepesertaan_iuran_jumlah * 0.075));
                return ['bulan' => $month, 'phdp' => $base->isEmpty() ? null : (float) $base->avg('kepesertaan_iuran_jumlah'),
                    'iuran' => $iuran, 'iuran_pengembangan' => $hasDevelopment
                        ? ($development->isEmpty() ? null : (float) $development->sum('kepesertaan_iuran_jumlah') * 0.075 * (1 + 0.045 / 12))
                        : ($iuran === null ? null : $iuran * (1 + 0.045 / 12))];
            });
            return $this->response(['tahun' => $year, 'nama' => $participant->kepesertaan_nama, 'bulan' => $months,
                'pengembangan_estimasi' => !$hasDevelopment]);
        }

        $totalIuran = DB::table('kepesertaan_iurans')
            ->where('kepesertaan_iuran_tahun', $year)
            ->where('kepesertaan_iuran_type', 'iuran_total')
            ->whereNotNull('kepesertaan_iuran_jumlah')
            ->groupBy('kepesertaan_iuran_kepesertaan_id')
            ->selectRaw('kepesertaan_iuran_kepesertaan_id, AVG(kepesertaan_iuran_jumlah) as phdp, SUM(COALESCE(imported_iuran_peserta, kepesertaan_iuran_jumlah * 0.075)) as iuran');

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
            ->selectRaw('total.phdp as phdp')
            ->selectRaw('total.iuran as iuran')
            ->selectRaw('COALESCE(pengembangan.iuran_pengembangan, total.iuran * (1 + 0.045 / 12)) as iuran_pengembangan')
            ->get();

        return $this->response([
            'tahun' => $year,
            'total_peserta' => $peserta->count(),
            'peserta' => $peserta->values(),
        ]);
    }
}
