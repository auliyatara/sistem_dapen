<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\KpiPeriod;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class KPIController extends Controller
{
    public function index(Request $request, bool $exporting = false)
    {
        $user = $request->user();
        abort_unless($user && $user->role, 403);

        $roles = Role::where('role_status', 1)
            ->orderBy('role_name')
            ->get(['role_id', 'role_name', 'role_status'])
            ->reject(function ($role) {
                $name = strtolower(preg_replace('/[^a-z0-9]/i', '', $role->role_name));
                return in_array($name, ['admin', 'superadmin'], true);
            })
            ->values();

        $request->validate(['role_id' => 'nullable|string', 'period_id' => 'nullable|integer', 'year' => 'nullable|integer|between:1900,2099']);
        $selectedRole = $request->filled('role_id')
            ? $roles->firstWhere('role_id', $request->query('role_id'))
            : ($roles->firstWhere('role_id', $user->user_role_id) ?? $roles->first());
        abort_if($request->filled('role_id') && !$selectedRole, 404);
        $canManage = $selectedRole && Gate::forUser($user)->allows('manage-kpi', $selectedRole);

        $periods = $selectedRole
            ? KpiPeriod::where('role_id', $selectedRole->role_id)->orderByDesc('starts_at')->orderBy('ends_at')->get()
            : collect();

        $requestedPeriod = $request->filled('period_id') ? $periods->firstWhere('id', $request->query('period_id')) : null;
        abort_if($request->filled('period_id') && !$requestedPeriod, 404);
        $selectedYear = (int) ($request->query('year') ?: ($requestedPeriod->year ?? now('Asia/Jakarta')->year));
        $periods = $periods->where('year', $selectedYear);
        $selectedPeriod = $requestedPeriod && (int) $requestedPeriod->year === $selectedYear ? $requestedPeriod : $periods->first();
        $request->validate(['period_type' => ['nullable', Rule::in(array_keys(KpiPeriod::DURATIONS))], 'period_number' => 'nullable|integer|between:1,366', 'date' => 'nullable|date_format:Y-m-d']);
        $selectedType = $request->query('period_type') ?: ($selectedPeriod->type ?? 'bulanan');
        $selectedNumber = (int) ($request->query('period_number') ?: ($selectedPeriod && $selectedPeriod->type === $selectedType ? $selectedPeriod->number : 1));
        if ($selectedType === 'harian' && $request->filled('date')) {
            $date = \Carbon\CarbonImmutable::parse($request->query('date'));
            $selectedYear = $date->year;
            abort_unless($selectedYear >= 1900 && $selectedYear <= 2099, 422);
            $selectedNumber = $date->dayOfYear;
            $periods = $selectedRole ? KpiPeriod::where('role_id', $selectedRole->role_id)->where('year', $selectedYear)->get() : collect();
        }
        if ($selectedNumber > KpiPeriod::countFor($selectedType, $selectedYear)) $selectedNumber = 1;
        $selectedPeriod = $periods->where('type', $selectedType)->firstWhere('number', $selectedNumber);
        [$start, $end] = KpiPeriod::dateRange($selectedType, $selectedYear, $selectedNumber);
        if (!$selectedPeriod) {
            $selectedPeriod = new KpiPeriod(['role_id' => $selectedRole->role_id ?? null, 'type' => $selectedType,
                'year' => $selectedYear, 'number' => $selectedNumber, 'starts_at' => $start, 'ends_at' => $end]);
        }
        $includedPeriods = $periods->filter(fn ($period) => $period->starts_at->toDateString() >= $start && $period->ends_at->toDateString() <= $end);
        $request->validate(['search' => 'nullable|string|max:255', 'status' => ['nullable', Rule::in(['not_started', 'in_progress', 'completed'])]]);
        $search = trim((string) $request->query('search', ''));
        $status = (string) $request->query('status', '');
        $taskQuery = \App\Models\KpiTask::whereIn('kpi_period_id', $includedPeriods->pluck('id'));
        $summary = [
            'total' => (clone $taskQuery)->count(),
            'completed' => (clone $taskQuery)->where('progress', 100)->count(),
            'in_progress' => (clone $taskQuery)->where('progress', '>', 0)->where('progress', '<', 100)->count(),
            'not_started' => (clone $taskQuery)->where('progress', 0)->count(),
        ];
        if ($search !== '') {
            $taskQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', '%'.$search.'%')->orWhere('description', 'like', '%'.$search.'%');
            });
        }
        if ($status === 'completed') $taskQuery->where('progress', 100);
        elseif ($status === 'in_progress') $taskQuery->where('progress', '>', 0)->where('progress', '<', 100);
        elseif ($status === 'not_started') $taskQuery->where('progress', 0);
        $tasks = $taskQuery->with('period')->orderBy(
            KpiPeriod::select('starts_at')->whereColumn('kpi_periods.id', 'kpi_tasks.kpi_period_id')
        )->orderBy('id');
        $tasks = $exporting ? $tasks->get() : $tasks->paginate(15)->withQueryString();
        $programType = $selectedRole ? \App\Services\KpiPrograms::typeFor($selectedRole) : null;
        $programs = $programType ? \Illuminate\Support\Facades\DB::table('programs')->where('program_type', $programType)->orderBy('created_at')->orderBy('program_id')->get() : collect();
        $programTasks = \App\Models\KpiTask::with('period')->whereNotNull('program_id')
            ->whereHas('period', fn ($query) => $query->where('role_id', $selectedRole->role_id ?? null)->where('year', $selectedYear))
            ->orderBy('id')->get();
        foreach ($programTasks->groupBy('program_id') as $id => $records) {
            if (!$programs->contains('program_id', $id)) $programs->push((object) ['program_id' => $id, 'program_nama' => $records->first()->program_name ?: 'Program lama', 'archived' => true]);
        }
        $editableTasks = collect($exporting ? $tasks : $tasks->items())->merge($programTasks)->unique('id');
        $achievements = \App\Services\KpiAchievement::rows($programs, $programTasks);
        return view('layout.KPI.index', compact('roles', 'selectedRole', 'canManage', 'periods', 'selectedPeriod', 'tasks', 'selectedYear', 'selectedType', 'selectedNumber', 'summary', 'search', 'status', 'programs', 'programTasks', 'programType', 'editableTasks', 'achievements'));
    }

    public function export(Request $request)
    {
        $request->validate(['report' => ['nullable', Rule::in(['tasks', 'achievement', 'programs'])]]);
        $report = $request->query('report', 'tasks');
        if ($report !== 'tasks') {
            $request->query->set('period_type', 'tahunan');
            $request->query->set('period_number', 1);
            $request->query->set('scope', 'all');
        }
        $request->validate(['format' => ['required', Rule::in(['pdf', 'xlsx'])], 'scope' => ['required', Rule::in(['all', 'filtered'])], 'preview' => 'nullable|boolean']);
        if ($request->query('scope') === 'all') $request->query->remove('search');
        if ($request->query('scope') === 'all') $request->query->remove('status');
        $data = $this->index($request, true)->getData();
        abort_unless($data['selectedRole'], 404);
        $data['reportPeriod'] = new KpiPeriod(['type' => $data['selectedType'], 'year' => $data['selectedYear'], 'number' => $data['selectedNumber']]);
        [$start, $end] = KpiPeriod::dateRange($data['selectedType'], $data['selectedYear'], $data['selectedNumber']);
        $data['reportPeriod']->starts_at = $start;
        $data['reportPeriod']->ends_at = $end;
        $data['generatedAt'] = now('Asia/Jakarta');
        $data['achievementSummary'] = \App\Services\KpiAchievement::summary($data['achievements']);
        $data['report'] = $report;
        $name = 'KPI-'.$data['selectedYear'].'-'.$data['selectedType'].'-'.$data['selectedNumber'];
        if ($report !== 'tasks') $name = ($report === 'achievement' ? 'Pencapaian-KPI-' : 'Program-Kerja-').$data['selectedYear'];
        if ($request->query('format') === 'xlsx') {
            if ($request->boolean('preview')) {
                $data['rows'] = (new \App\Exports\KpiReportExport($data))->array();
                return view('layout.KPI.excel-preview', $data);
            }
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\KpiReportExport($data), $name.'.xlsx');
        }
        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->getFontMetrics()->setFontFamily('times new roman', [
            'normal' => public_path('fonts/times'), 'bold' => public_path('fonts/timesbd'),
            'italic' => public_path('fonts/timesi'), 'bold_italic' => public_path('fonts/timesbi'),
        ]);
        $data['pdfRows'] = app(\App\Services\KpiPdfRows::class)->build($data['tasks'], $pdf->getDomPDF()->getFontMetrics());
        if ($report !== 'tasks') $data['rows'] = (new \App\Exports\KpiReportExport($data))->array();
        $pdf->loadView($report === 'tasks' ? 'layout.KPI.pdf' : 'layout.KPI.annual-pdf', $data)->setOptions(['isRemoteEnabled' => false])->setPaper('a4', 'landscape');
        return $request->boolean('preview') ? $pdf->stream($name.'.pdf') : $pdf->download($name.'.pdf');
    }

    public function history(Request $request)
    {
        $data = $this->index($request)->getData();
        abort_unless($data['selectedRole'], 404);
        $request->validate(['task_id' => 'nullable|integer|min:1']);
        $logs = \Spatie\Activitylog\Models\Activity::where('log_name', 'kpi-history')
            ->whereJsonContains('properties->role_id', (string) $data['selectedRole']->role_id);
        if ($request->filled('task_id')) $logs->where('subject_id', (string) $request->query('task_id'));
        $data['logs'] = $logs->latest('id')->paginate(20)->withQueryString();
        return view('layout.KPI.history', $data);
    }

    public function storePeriod(Request $request, Role $role)
    {
        Gate::authorize('manage-kpi', $role);
        $data = $request->validate([
            'type' => ['required', Rule::in(array_keys(KpiPeriod::DURATIONS))],
            'year' => ['required', 'integer', 'between:1900,2099'],
            'number' => ['required_unless:type,harian', 'nullable', 'integer', 'between:1,366'],
            'date' => ['required_if:type,harian', 'nullable', 'date_format:Y-m-d'],
        ]);
        if ($data['type'] === 'harian') {
            $date = \Carbon\CarbonImmutable::parse($data['date']);
            if ($date->year !== (int) $data['year']) throw ValidationException::withMessages(['date' => 'Tanggal harus sesuai tahun yang dipilih.']);
            $data['number'] = $date->dayOfYear;
        }
        unset($data['date']);
        if ($data['number'] > KpiPeriod::countFor($data['type'], (int) $data['year'])) {
            throw ValidationException::withMessages(['number' => 'Pilihan periode tidak sesuai dengan jenis periode.']);
        }
        [$start, $end] = KpiPeriod::dateRange($data['type'], (int) $data['year'], (int) $data['number']);
        try {
            $period = KpiPeriod::create(array_merge($data, ['role_id' => $role->role_id, 'starts_at' => $start, 'ends_at' => $end]));
        } catch (QueryException $exception) {
            if (($exception->errorInfo[1] ?? null) !== 1062) {
                throw $exception;
            }
            throw ValidationException::withMessages(['number' => 'Periode tersebut sudah tersedia untuk bidang ini.']);
        }
        return redirect()->route('kpi.index', ['role_id' => $role->role_id, 'period_id' => $period->id])->with('success', 'Periode KPI berhasil ditambahkan.');
    }
}
