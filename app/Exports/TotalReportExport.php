<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\{FromArray, ShouldAutoSize, WithStrictNullComparison, WithColumnFormatting};

class TotalReportExport implements FromArray, ShouldAutoSize, WithStrictNullComparison, WithColumnFormatting
{
    private array $data;
    public function __construct(array $data) { $this->data = $data; }
    public function columnFormats(): array { return ['B' => '#,##0.00', 'C' => '#,##0.00']; }
    public function array(): array
    {
        $data = $this->data;
        $rows = [['REKAP IURAN', $data['tahun']], ['Dicetak', $data['generatedAt']->locale('id')->translatedFormat('j F Y H:i').' WIB'],
            ['Dasar hitung', 'Iuran peserta 7,5% PHDP; pemberi kerja 5,7% PHDP'],
            ['Bulan', 'Iuran peserta', 'Iuran pemberi kerja']];
        foreach ($data['bulan'] as $month) $rows[] = [\App\Models\KpiPeriod::MONTHS[$month['bulan'] - 1],
            $month['has_data'] ? (float) $month['iuran_peserta'] : 'Belum ada data',
            $month['has_data'] ? (float) $month['iuran_pemberi_kerja'] : 'Belum ada data'];
        $rows[] = ['Total', $data['jumlah_bulan_terisi'] ? $data['total_iuran_peserta'] : 'Belum ada data', $data['jumlah_bulan_terisi'] ? $data['total_iuran_pemberi_kerja'] : 'Belum ada data'];
        $check = $data['pencocokan'];
        $rows[] = ['Pencocokan dengan Iuran', ['matched' => 'Sesuai', 'different' => 'Ada selisih', 'no_data' => 'Belum ada data'][$check['status']]];
        $rows[] = ['Iuran peserta pada menu Iuran', $check['status'] === 'no_data' ? 'Belum ada data' : $check['total_menu_iuran']];
        $rows[] = ['Selisih (Total - Iuran)', $check['status'] === 'no_data' ? 'Belum ada data' : $check['selisih']];
        $rows[] = ['Cakupan', 'Tahun yang sama; Dosen, Guru Besar, Karyawan; iuran peserta sebelum pengembangan.'];
        return $rows;
    }
}
