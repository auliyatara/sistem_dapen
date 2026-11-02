<?php
namespace Tests\Feature;

use App\Models\{Role, User, KpiPeriod};
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class KpiAggregationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_field_without_period_still_displays_summary_filters_and_empty_table()
    {
        activity()->disableLogging();
        $role = Role::create(['role_name' => 'Empty field', 'role_status' => 1]);
        $user = (new User(['user_id' => 'empty-field', 'user_role_id' => $role->role_id]))->setRelation('role', $role);
        $this->actingAs($user);
        foreach (['bulanan', 'harian'] as $type) {
            $response = $this->get(route('kpi.index', ['role_id' => $role->role_id, 'tab' => 'tasks', 'year' => 2026, 'period_type' => $type, 'period_number' => 1]))->assertOk()->assertSee('Total tugas')->assertSee('Cari tugas')->assertSee('Belum ada tugas pada periode ini.');
            $this->assertSame(0, $response->viewData('summary')['total']);
            $this->assertFalse($response->viewData('selectedPeriod')->exists);
        }
        $this->assertSame(0, KpiPeriod::where('role_id', $role->role_id)->count());
    }

    public function test_task_filters_and_pagination_preserve_period_summary()
    {
        activity()->disableLogging();
        $role = Role::create(['role_name' => 'Filter owner', 'role_status' => 1]);
        $user = new User(['user_id' => 'filter-test', 'user_role_id' => $role->role_id]);
        $user->setRelation('role', $role);
        $this->actingAs($user);
        $period = KpiPeriod::create(['role_id' => $role->role_id, 'type' => 'bulanan', 'year' => 2026, 'number' => 5, 'starts_at' => '2026-05-01', 'ends_at' => '2026-05-31']);
        foreach (range(1, 16) as $number) $period->tasks()->create(['title' => 'Laporan '.$number, 'progress' => 100]);
        $period->tasks()->create(['title' => 'Persiapan', 'progress' => 0]);
        $period->tasks()->create(['title' => 'Tinjauan', 'description' => 'Laporan sedang diperiksa', 'progress' => 50]);
        $filters = ['role_id' => $role->role_id, 'year' => 2026, 'period_type' => 'bulanan', 'period_number' => 5, 'search' => 'Laporan', 'status' => 'completed'];
        $response = $this->get(route('kpi.index', $filters))->assertOk();
        $this->assertSame(['total' => 18, 'completed' => 16, 'in_progress' => 1, 'not_started' => 1], $response->viewData('summary'));
        $this->assertSame(16, $response->viewData('tasks')->total());
        $this->assertCount(15, $response->viewData('tasks'));
        $this->assertStringContainsString('status=completed', $response->viewData('tasks')->nextPageUrl());
        $page = $this->get(route('kpi.index', $filters + ['page' => 2]))->assertOk();
        $this->assertSame(['Laporan 16'], $page->viewData('tasks')->pluck('title')->all());
        $filters['status'] = 'in_progress';
        $this->assertSame(['Tinjauan'], $this->get(route('kpi.index', $filters))->assertOk()->viewData('tasks')->pluck('title')->all());
    }

    public function test_calendar_rollups_include_each_task_once_and_isolate_roles_and_years()
    {
        activity()->disableLogging();
        $role = Role::create(['role_name' => 'Aggregation owner', 'role_status' => 1]);
        $other = Role::create(['role_name' => 'Aggregation other', 'role_status' => 1]);
        $user = new User(['user_id' => 'aggregate-test', 'user_role_id' => $role->role_id]);
        $user->setRelation('role', $role);
        $this->actingAs($user);
        foreach (range(1, 12) as $month) {
            [$start, $end] = KpiPeriod::dateRange('bulanan', 2026, $month);
            $period = KpiPeriod::create(['role_id' => $role->role_id, 'type' => 'bulanan', 'year' => 2026, 'number' => $month, 'starts_at' => $start, 'ends_at' => $end]);
            $period->tasks()->create(['title' => 'Month '.$month, 'progress' => $month]);
        }
        foreach ([[$other->role_id, 2026], [$role->role_id, 2025]] as [$roleId, $year]) {
            $period = KpiPeriod::create(['role_id' => $roleId, 'type' => 'tahunan', 'year' => $year, 'number' => 1, 'starts_at' => "$year-01-01", 'ends_at' => "$year-12-31"]);
            $period->tasks()->create(['title' => 'Excluded', 'progress' => 0]);
        }
        foreach ([['triwulanan', 1, 1, 3], ['triwulanan', 4, 10, 12], ['semesteran', 1, 1, 6], ['semesteran', 2, 7, 12], ['tahunan', 1, 1, 12]] as [$type, $number, $first, $last]) {
            $response = $this->get(route('kpi.index', ['role_id' => $role->role_id, 'year' => 2026, 'period_type' => $type, 'period_number' => $number]));
            $response->assertOk()->assertDontSee('Excluded');
            $this->assertSame(array_map(fn ($month) => 'Month '.$month, range($first, $last)), $response->viewData('tasks')->pluck('title')->all());
            if ($type === 'tahunan') $response->assertSee('<table', false);
        }
    }
}
