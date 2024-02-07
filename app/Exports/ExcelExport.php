<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExcelExport implements FromCollection, WithHeadings, WithMapping
{
    private $operation;

    public function __construct($operation)
    {
        $this->operation = $operation;
    }

    public function collection()
    {
        return collect($this->operation);
    }

    public function map($row): array
    {
        $programPelaksanaanNames = $this->getRelatedNames($row['program_pelaksanaan'],'program_pelaksanaan');
        $programEvaluasiNames = $this->getRelatedNames($row['program_evaluasi'],'program_evaluasi');

        static $counter = 1; // initialize counter to 1
        return [
            $counter++,
            $row['program_nama'],
            $programPelaksanaanNames, 
            $programEvaluasiNames,
        ];
    }
    
    protected function getRelatedNames($relatedData,$type)
    {
        if (is_array($relatedData) && !empty($relatedData)) {
            $names = array_column($relatedData, $type.'_nama');
            return implode(', ', $names);
        }

        return ''; // Return an empty string if no related data
    }

    public function headings(): array
    {
        return ['NO', 'Nama Program' ,'Detail Pelaksanaan Program', 'Detail Evaluasi Program'];
    }
}
