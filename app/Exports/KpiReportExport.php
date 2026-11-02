<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;

class KpiReportExport extends StringValueBinder implements FromArray, WithCustomValueBinder, ShouldAutoSize
{
    private array $data;

    public function __construct(array $data) { $this->data = $data; }

    public function array(): array
    {
        $data = $this->data;
        if (($data['report'] ?? 'tasks') !== 'tasks') return $this->annualRows();
        $rows = [
            ['Laporan KPI', $data['selectedRole']->role_name],
            ['Periode', $data['reportPeriod']->label],
            ['Dicetak', $data['generatedAt']->locale('id')->translatedFormat('j F Y H:i').' WIB'],
            ['Cakupan', request('scope') === 'all' ? 'Semua tugas periode ini' : 'Hasil filter', 'Pencarian', $data['search'], 'Status', $data['status']],
            ['Ringkasan seluruh periode', 'Total', $data['summary']['total'], 'Selesai', $data['summary']['completed'], 'Sedang dikerjakan', $data['summary']['in_progress'], 'Belum dimulai', $data['summary']['not_started']],
            ['Periode asal', 'Tugas', 'Keterangan', 'Progres (%)', 'Status', 'Evaluasi', 'Bukti', 'Link'],
        ];
        foreach ($data['tasks'] as $task) {
            $rows[] = [$task->period->label, ($task->program_name ? 'Program: '.$task->program_name."\nPelaksanaan: " : '').$task->title, $task->description, $task->progress,
                $task->progress === 100 ? 'Selesai' : ($task->progress === 0 ? 'Belum dimulai' : 'Sedang dikerjakan'),
                $task->evaluation, implode("\n", array_column($task->all_attachments, 'name')), implode("\n", $task->links ?? [])];
        }
        return $rows;
    }

    private function annualRows(): array
    {
        $d = $this->data;
        $achievement = $d['report'] === 'achievement';
        $rows = [[$achievement ? 'Pencapaian KPI' : 'Program Kerja', $d['selectedRole']->role_name], ['Tahun', $d['selectedYear']], ['Dicetak', $d['generatedAt']->locale('id')->translatedFormat('j F Y H:i').' WIB'], ['Cakupan', 'Seluruh tahun'], ['Penilaian', '1 tertinggi, 5 terendah'], $achievement ? ['Program kerja', 'Pelaksanaan', 'Pencapaian (%)', 'Nilai KPI', 'Keterangan'] : ['Program kerja', 'Pelaksanaan', 'Keterangan', 'Progres (%)', 'Evaluasi', 'Bukti', 'Link']];
        if ($achievement) {
            foreach ($d['achievements'] as $a) $rows[] = [$a['program'], $a['count'], $a['percentage'], $a['grade'], \App\Services\KpiAchievement::LABELS[$a['grade']]];
            $s = \App\Services\KpiAchievement::summary($d['achievements']);
            $rows[] = ['Rata-rata seluruh program', '', $s['percentage'], $s['grade'], $s['label']];
        } else {
            foreach ($d['programs'] as $program) {
                $tasks = $d['programTasks']->where('program_id', $program->program_id);
                if ($tasks->isEmpty()) $rows[] = [$program->program_nama, 'Belum ada pelaksanaan', '', '', '', '', ''];
                foreach ($tasks as $task) $rows[] = [$program->program_nama, $task->title, $task->description, $task->progress, $task->evaluation, implode("\n", array_column($task->all_attachments, 'name')), implode("\n", $task->links ?? [])];
            }
        }
        return $rows;
    }
}
