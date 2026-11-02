<?php
namespace App\Services;

class KpiPdfRows
{
    public function build($tasks, $metrics): array
    {
        $font = $metrics->getFont('times new roman', 'normal');
        $rows = [];
        foreach ($tasks as $task) {
            $evidence = [];
            foreach ($task->all_attachments as $key => $file) {
                $evidence[] = ['text' => $file['name'], 'url' => route('kpi.tasks.evidence', ['task' => $task->id, 'attachment' => $key, 'preview' => 1])];
            }
            foreach ($task->links ?? [] as $link) $evidence[] = ['text' => $link, 'url' => $link];
            if (!$evidence) $evidence[] = ['text' => 'Belum ada bukti.', 'url' => null];
            $columns = [
                [['text' => $task->period->label, 'url' => null]],
                [['text' => ($task->program_name ? 'Program: '.$task->program_name."\nPelaksanaan: " : '').$task->title, 'url' => null], ['text' => $task->description ?? '', 'url' => null]],
                [['text' => $task->progress.'% — '.($task->progress === 100 ? 'Selesai' : ($task->progress === 0 ? 'Belum dimulai' : 'Sedang dikerjakan')), 'url' => null]],
                [['text' => $task->evaluation ?: 'Belum ada evaluasi.', 'url' => null]], $evidence,
            ];
            $lines = [];
            foreach ($columns as $index => $items) {
                $lines[$index] = [];
                foreach ($items as $item) {
                    foreach ($this->wrap($item['text'], [70, 150, 75, 135, 150][$index], $metrics, $font) as $line) {
                        $lines[$index][] = ['text' => $line, 'url' => $item['url']];
                    }
                }
            }
            $count = max(array_map('count', $lines));
            for ($offset = 0; $offset < $count; $offset += 8) {
                $cells = [];
                foreach ($lines as $column) $cells[] = array_slice($column, $offset, 8);
                $rows[] = ['cells' => $cells, 'continued' => $offset > 0];
            }
        }
        return $rows;
    }

    private function wrap(string $text, int $width, $metrics, $font): array
    {
        $result = [];
        foreach (preg_split('/\R/u', $text) as $paragraph) {
            $line = '';
            foreach (preg_split('//u', $paragraph, -1, PREG_SPLIT_NO_EMPTY) as $character) {
                if ($line !== '' && $metrics->getTextWidth($line.$character, $font, 10) > $width) {
                    $result[] = $line;
                    $line = '';
                }
                $line .= $character;
            }
            $result[] = $line;
        }
        return $result;
    }
}
