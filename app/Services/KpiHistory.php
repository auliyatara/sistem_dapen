<?php

namespace App\Services;

use App\Models\KpiTask;
use Spatie\Activitylog\Models\Activity;

class KpiHistory
{
    public static function snapshot(KpiTask $task): array
    {
        return ['Program kerja' => $task->program_name, 'Tugas' => $task->title, 'Keterangan' => $task->description, 'Progres (%)' => $task->progress,
            'Evaluasi' => $task->evaluation, 'Periode' => $task->period->label,
            'Bukti' => array_values(array_column($task->all_attachments, 'name')), 'Link' => $task->links ?? []];
    }

    public static function record(KpiTask $task, string $event, ?array $before, ?array $after): void
    {
        if ($before === $after) return;
        $user = auth()->user();
        $log = new Activity();
        $log->log_name = 'kpi-history';
        $log->description = $event;
        $log->event = $event;
        $log->subject_type = KpiTask::class;
        $log->subject_id = (string) $task->id;
        $log->causer_type = \App\Models\User::class;
        $log->causer_id = (string) ($user->getAuthIdentifier());
        $log->properties = collect(['role_id' => (string) $task->period->role_id,
            'actor' => $user->user_nama ?: $user->user_username ?: $user->getAuthIdentifier(),
            'title' => $task->title, 'before' => $before, 'after' => $after]);
        $log->save();
    }
}
