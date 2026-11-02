<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TotalController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['year' => 'nullable|integer|between:1900,2099', 'month' => 'nullable|integer|between:1,12']);
        $year = (int) $request->input('year', now('Asia/Jakarta')->year);
        $iuranPerBulan = DB::table('kepesertaan_iurans as iuran')
            ->join('kepesertaans as peserta', 'peserta.kepesertaan_id', '=', 'iuran.kepesertaan_iuran_kepesertaan_id')
            ->where('iuran.kepesertaan_iuran_tahun', $year)
            ->where('iuran.kepesertaan_iuran_type', 'iuran_total')
            ->whereNotNull('iuran.kepesertaan_iuran_jumlah')
            ->whereIn('peserta.kepesertaan_status_jabatan', ['Dosen', 'Guru Besar', 'Karyawan'])
            ->selectRaw('iuran.kepesertaan_iuran_bulan as bulan, COUNT(*) as jumlah_data, SUM(COALESCE(iuran.imported_iuran_peserta, iuran.kepesertaan_iuran_jumlah * 0.075)) as iuran_peserta, SUM(COALESCE(iuran.imported_iuran_pk, iuran.kepesertaan_iuran_jumlah * 0.057)) as iuran_pemberi_kerja')
            ->groupBy('iuran.kepesertaan_iuran_bulan')
            ->get()->keyBy('bulan');

        $bulan = collect(range(1, 12))->map(fn ($nomor) => [
            'bulan' => $nomor,
            'has_data' => isset($iuranPerBulan[$nomor]),
            'jumlah_data' => (int) ($iuranPerBulan[$nomor]->jumlah_data ?? 0),
            'iuran_peserta' => (float) ($iuranPerBulan[$nomor]->iuran_peserta ?? 0),
            'iuran_pemberi_kerja' => (float) ($iuranPerBulan[$nomor]->iuran_pemberi_kerja ?? 0),
        ]);

        $result = [
            'tahun' => $year,
            'bulan' => $bulan,
            'total_iuran_peserta' => $bulan->sum('iuran_peserta'),
            'total_iuran_pemberi_kerja' => $bulan->sum('iuran_pemberi_kerja'),
            'jumlah_bulan_terisi' => $bulan->where('has_data', true)->count(),
        ];
        if (!$request->filled('month')) {
            $annual = (new IuranController())->index(new Request(['year' => $year]))['data']['peserta'];
            $iuranTotal = round((float) $annual->sum('iuran'), 2);
            $total = round((float) $result['total_iuran_peserta'], 2);
            $difference = round($total - $iuranTotal, 2);
            $hasData = $annual->contains(fn ($participant) => $participant->iuran !== null);
            $result['pencocokan'] = ['total_menu_total' => $total, 'total_menu_iuran' => $iuranTotal,
                'selisih' => $difference, 'status' => !$hasData && !$result['jumlah_bulan_terisi'] ? 'no_data' : (abs($difference) < 0.01 ? 'matched' : 'different')];
        }
        if ($request->filled('month')) {
            $result['rincian'] = DB::table('kepesertaan_iurans as iuran')
                ->join('kepesertaans as peserta', 'peserta.kepesertaan_id', '=', 'iuran.kepesertaan_iuran_kepesertaan_id')
                ->where('iuran.kepesertaan_iuran_tahun', $year)
                ->where('iuran.kepesertaan_iuran_bulan', (int) $request->input('month'))
                ->where('iuran.kepesertaan_iuran_type', 'iuran_total')
                ->whereNotNull('iuran.kepesertaan_iuran_jumlah')
                ->whereIn('peserta.kepesertaan_status_jabatan', ['Dosen', 'Guru Besar', 'Karyawan'])
                ->selectRaw('peserta.kepesertaan_id, peserta.kepesertaan_nama as nama, peserta.kepesertaan_nip as nip, SUM(iuran.kepesertaan_iuran_jumlah) as phdp, SUM(COALESCE(iuran.imported_iuran_peserta, iuran.kepesertaan_iuran_jumlah * 0.075)) as iuran_peserta, SUM(COALESCE(iuran.imported_iuran_pk, iuran.kepesertaan_iuran_jumlah * 0.057)) as iuran_pemberi_kerja')
                ->groupBy('peserta.kepesertaan_id', 'peserta.kepesertaan_nama', 'peserta.kepesertaan_nip')
                ->orderBy('peserta.kepesertaan_nama')->get();
        }
        return $this->response($result);
    }
}
