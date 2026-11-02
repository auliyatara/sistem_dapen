<?php
namespace App\Services;

class KpiAchievement
{
    public const LABELS = [1 => 'Baik sekali', 2 => 'Baik', 3 => 'Cukup', 4 => 'Kurang', 5 => 'Sangat kurang'];

    public static function summary($rows): array
    {
        if ($rows->isEmpty()) return ['percentage' => null, 'grade' => null, 'label' => 'Belum ada program kerja'];
        $average = (float) $rows->avg('raw_percentage');
        $grade = self::grade($average);
        return ['percentage' => floor(($average + 0.000000001) * 10) / 10, 'grade' => $grade, 'label' => self::LABELS[$grade]];
    }
    public static function grade(float $percentage): int
    {
        if ($percentage >= 100) return 1;
        if ($percentage >= 80) return 2;
        if ($percentage >= 60) return 3;
        if ($percentage >= 40) return 4;
        return 5;
    }

    public static function rows($programs, $tasks)
    {
        $grouped = $tasks->groupBy('program_id');
        return $programs->map(function ($program) use ($grouped) {
            $executions = $grouped->get($program->program_id, collect());
            $average = $executions->isEmpty() ? 0.0 : (float) $executions->avg('progress');
            return ['program' => $program->program_nama, 'count' => $executions->count(),
                'raw_percentage' => $average,
                // Keep values below 100 from being displayed as completed after rounding.
                'percentage' => floor(($average + 0.000000001) * 10) / 10,
                'grade' => self::grade($average), 'archived' => !empty($program->archived)];
        });
    }
}
