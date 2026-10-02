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
    public function index(Request $request)
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
        $request->validate(['period_type' => ['nullable', Rule::in(array_keys(KpiPeriod::DURATIONS))], 'period_number' => 'nullable|integer|between:1,12']);
        $selectedType = $request->query('period_type') ?: ($selectedPeriod->type ?? 'bulanan');
        $selectedNumber = (int) ($request->query('period_number') ?: ($selectedPeriod && $selectedPeriod->type === $selectedType ? $selectedPeriod->number : 1));
        if ($selectedNumber > 12 / KpiPeriod::DURATIONS[$selectedType]) $selectedNumber = 1;
        $selectedPeriod = $periods->where('type', $selectedType)->firstWhere('number', $selectedNumber);
        $tasks = $selectedPeriod ? $selectedPeriod->tasks()->latest()->get() : collect();
        return view('layout.KPI.index', compact('roles', 'selectedRole', 'canManage', 'periods', 'selectedPeriod', 'tasks', 'selectedYear', 'selectedType', 'selectedNumber'));
    }

    public function storePeriod(Request $request, Role $role)
    {
        Gate::authorize('manage-kpi', $role);
        $data = $request->validate([
            'type' => ['required', Rule::in(array_keys(KpiPeriod::DURATIONS))],
            'year' => ['required', 'integer', 'between:1900,2099'],
            'number' => ['required', 'integer', 'between:1,12'],
        ]);
        if ($data['number'] > 12 / KpiPeriod::DURATIONS[$data['type']]) {
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
