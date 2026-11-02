<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TotalController extends Controller
{
    public function export(\Illuminate\Http\Request $request)
    {
        $request->validate(['year' => 'required|integer|between:1900,2099', 'format' => 'required|in:pdf,xlsx', 'preview' => 'nullable|boolean']);
        $data = (new \App\Http\Controllers\Api\TotalController())->index(new \Illuminate\Http\Request(['year' => $request->input('year')]))['data'];
        $data['generatedAt'] = now('Asia/Jakarta');
        $name = 'Rekap-Iuran-'.$data['tahun'];
        if ($request->input('format') === 'xlsx') {
            if ($request->boolean('preview')) return view('layout.Total.report', $data + ['pdf' => false]);
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\TotalReportExport($data), $name.'.xlsx');
        }
        $directory = storage_path('fonts');
        if (!is_dir($directory)) \Illuminate\Support\Facades\File::ensureDirectoryExists($directory);
        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->getFontMetrics()->setFontFamily('times new roman', [
            'normal' => public_path('fonts/times'), 'bold' => public_path('fonts/timesbd'),
            'italic' => public_path('fonts/timesi'), 'bold_italic' => public_path('fonts/timesbi'),
        ]);
        $pdf->loadView('layout.Total.report', $data + ['pdf' => true])->setOptions(['isRemoteEnabled' => false])->setPaper('a4');
        return $request->boolean('preview') ? $pdf->stream($name.'.pdf') : $pdf->download($name.'.pdf');
    }
    public function index(Request $request){
        $request->validate(['year' => 'nullable|integer|between:1900,2099']);
        $iuranYear = (int) $request->input('year', now('Asia/Jakarta')->year);

        return view('layout.Total.index', compact('iuranYear'));
    }
}
