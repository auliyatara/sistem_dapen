<?php
namespace App\Services;

use PhpOffice\PhpSpreadsheet\Reader\{Xlsx, IReadFilter};
use PhpOffice\PhpSpreadsheet\Shared\Date;

class KepesertaanMonthlyImport
{
    public static function value($sheet, int $column, int $row) {
        $cell = $sheet->getCellByColumnAndRow($column, $row);
        return $cell->isFormula() ? $cell->getOldCalculatedValue() : $cell->getValue();
    }

    public function read(string $path, int $year): array
    {
        $reader = new Xlsx();
        $result = [];
        foreach ($reader->listWorksheetNames($path) as $name) {
            if (in_array(strtolower(trim($name)), ['admin', 'logs'], true)) continue;
            foreach ($this->readSheet($path, $year, $name) as $nip => $entry) {
                if (!isset($result[$nip])) { $result[$nip] = $entry; continue; }
                $result[$nip]['months'] = array_merge($result[$nip]['months'], $entry['months']);
                $result[$nip]['errors'] = array_merge($result[$nip]['errors'], $entry['errors']);
            }
        }
        return $result;
    }

    private function readSheet(string $path, int $year, string $name): array
    {
        $reader = new Xlsx(); $reader->setReadDataOnly(true);
        $reader->setLoadSheetsOnly([$name]);
        $reader->setReadFilter(new class implements IReadFilter { public function readCell($c,$r,$s='') { return $r <= 3; } });
        $book = $reader->load($path); $sheet = $book->getActiveSheet(); $groups = []; $nipColumn = null; $nameColumn = null;
        try {
            $max = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($sheet->getHighestColumn());
            for ($c = 1; $c <= $max; $c++) {
                $header = mb_strtolower(trim((string) self::value($sheet, $c, 2)));
                if (in_array($header, ['nip umm', 'nip'], true)) $nipColumn = $c;
                if (in_array($header, ['nama peserta', 'nama'], true)) $nameColumn = $c;
            }
            for ($c = 1; $c <= $max; $c++) {
                $date = self::value($sheet, $c, 2);
                if (!in_array(strtolower(trim((string) self::value($sheet, $c, 3))), ['gaji pokok', 'phdp'], true) || !is_numeric($date) || $date < 20000) continue;
                if (strtolower(trim((string) self::value($sheet, $c + 1, 3))) !== 'iuran peserta' || !in_array(strtolower(trim((string) self::value($sheet, $c + 2, 3))), ['iuran pk', 'iuran pemberi kerja'], true)) continue;
                $date = Date::excelToDateTimeObject((float) $date);
                if ((int) $date->format('Y') !== $year) continue;
                $groups[] = ['column' => $c, 'month' => (int) $date->format('n')];
            }
        } finally { $book->disconnectWorksheets(); }
        if (!$groups || !$nipColumn || !$nameColumn) return [];
        $columns = [$nipColumn,$nameColumn]; foreach ($groups as $group) array_push($columns, $group['column'], $group['column']+1, $group['column']+2);
        $reader->setReadFilter(new class($columns) implements IReadFilter {
            private array $columns; public function __construct($columns) {$this->columns=$columns;}
            public function readCell($c,$r,$s='') { return $r <= 10000 && in_array(\PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($c), $this->columns, true); }
        });
        $book = $reader->load($path); $sheet = $book->getActiveSheet(); $result = [];
        try {
            for ($row=4; $row <= $sheet->getHighestRow(); $row++) {
                $nip = trim((string) self::value($sheet,$nipColumn,$row));
                if ($nip === '' || !ctype_digit($nip)) continue;
                $months = []; $errors = [];
                foreach ($groups as $group) {
                    $values = []; $present = false;
                    foreach (['phdp','peserta','pk'] as $offset => $field) {
                        $value = self::value($sheet, $group['column']+$offset, $row);
                        if ($value === null || $value === '') continue;
                        $present = true;
                        if (!is_numeric($value) || $value < 0 || $value > 999999999999999) { $errors[] = 'Nominal iuran tidak valid pada bulan '.$group['month'].'.'; continue; }
                        $values[$field] = $field === 'phdp' ? (int) $value : round((float) $value, 4);
                        if ($field === 'phdp' && floor((float) $value) != $value) $errors[] = 'PHDP bulanan harus angka bulat.';
                    }
                    if (!$present) continue;
                    $months[] = ['year'=>$year, 'month'=>$group['month'], 'values'=>$values];
                }
                if (!$months && !$errors) continue;
                if (isset($result[$nip])) { $result[$nip]['errors'][] = 'NIP berulang pada sheet iuran.'; continue; }
                $result[$nip] = ['sheet'=>$name, 'row'=>$row, 'name'=>(string) self::value($sheet,$nameColumn,$row), 'months'=>$months, 'errors'=>$errors];
            }
        } finally { $book->disconnectWorksheets(); }
        return $result;
    }
}
