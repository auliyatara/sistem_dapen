<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

class KpiPeriod extends Model
{
    public const MONTHS = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    public const DURATIONS = ['bulanan' => 1, 'triwulanan' => 3, 'semesteran' => 6, 'tahunan' => 12];

    protected $fillable = ['role_id', 'type', 'year', 'number', 'starts_at', 'ends_at'];
    protected $casts = ['starts_at' => 'date', 'ends_at' => 'date'];

    public function tasks() { return $this->hasMany(KpiTask::class); }
    public function role() { return $this->belongsTo(Role::class, 'role_id'); }

    public static function dateRange(string $type, int $year, int $number): array
    {
        $months = self::DURATIONS[$type] ?? 0;
        if (!$months || $number < 1 || $number > 12 / $months) {
            throw new \InvalidArgumentException('Periode tidak valid.');
        }
        $start = CarbonImmutable::create($year, ($number - 1) * $months + 1, 1, 0, 0, 0, 'Asia/Jakarta');
        return [$start->toDateString(), $start->addMonths($months)->subDay()->toDateString()];
    }

    public function getLabelAttribute(): string
    {
        $labels = ['bulanan' => self::MONTHS[$this->number - 1], 'triwulanan' => 'Triwulan '.$this->number,
            'semesteran' => 'Semester '.$this->number, 'tahunan' => 'Tahun'];
        return $labels[$this->type].' '.$this->year;
    }

    public function getShortLabelAttribute(): string
    {
        return $this->type === 'tahunan' ? 'Tahunan' : preg_replace('/ '.$this->year.'$/', '', $this->label);
    }
}
