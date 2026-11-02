<?php

namespace Tests\Feature;

use App\Models\KpiPeriod;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class KpiPeriodTest extends TestCase
{
    use DatabaseTransactions;

    public function test_calendar_ranges()
    {
        $this->assertSame(['2024-12-31', '2024-12-31'], KpiPeriod::dateRange('harian', 2024, 366));
        $this->assertSame(['2024-02-01', '2024-02-29'], KpiPeriod::dateRange('bulanan', 2024, 2));
        $this->assertSame(['2026-02-01', '2026-02-28'], KpiPeriod::dateRange('bulanan', 2026, 2));
        $this->assertSame(['2026-10-01', '2026-12-31'], KpiPeriod::dateRange('triwulanan', 2026, 4));
        $this->assertSame(['2026-07-01', '2026-12-31'], KpiPeriod::dateRange('semesteran', 2026, 2));
        $this->assertSame(['2026-01-01', '2026-12-31'], KpiPeriod::dateRange('tahunan', 2026, 1));
    }

    public function test_period_creation_validation_and_role_isolation()
    {
        activity()->disableLogging();
        $role = Role::create(['role_name' => 'KPI Test A', 'role_status' => 1]);
        $other = Role::create(['role_name' => 'KPI Test B', 'role_status' => 1]);
        $user = new User(['user_id' => 'kpi-test-user', 'user_role_id' => $role->role_id]);
        $user->setRelation('role', $role);
        $this->actingAs($user);
        $url = route('kpi.periods.store', $role->role_id);
        $data = ['type' => 'triwulanan', 'year' => 2026, 'number' => 2];
        $this->post($url, $data)->assertRedirect();
        $this->assertDatabaseHas('kpi_periods', ['role_id' => $role->role_id, 'starts_at' => '2026-04-01', 'ends_at' => '2026-06-30']);
        $this->get(route('kpi.index', ['role_id' => $role->role_id]))
            ->assertOk()->assertSee('Triwulan 2 2026')->assertSee('Simpan Tugas');
        $this->get(route('kpi.index', ['role_id' => $other->role_id]))
            ->assertOk()->assertSee('Hanya lihat')->assertDontSee('Simpan Tugas')->assertDontSee('Triwulan 2 2026');
        $this->post($url, $data)->assertSessionHasErrors('number');
        $this->assertSame(1, KpiPeriod::where('role_id', $role->role_id)->count());
        $this->post($url, array_merge($data, ['number' => 5]))->assertSessionHasErrors('number');
        $this->post($url, array_merge($data, ['type' => 'invalid']))->assertSessionHasErrors('type');
        $this->post(route('kpi.periods.store', $other->role_id), $data)->assertForbidden();
        $this->assertSame(0, KpiPeriod::where('role_id', $other->role_id)->count());
        $this->post($url, ['type' => 'harian', 'year' => 2026, 'date' => '2026-12-31'])->assertSessionHasNoErrors()->assertRedirect();
        $daily = KpiPeriod::where('role_id', $role->role_id)->where('type', 'harian')->firstOrFail();
        $this->assertSame(365, (int) $daily->number);
        $daily->tasks()->create(['title' => 'Kinerja harian akhir tahun', 'progress' => 100]);
        foreach (['harian' => 365, 'bulanan' => 12, 'triwulanan' => 4, 'semesteran' => 2, 'tahunan' => 1] as $type => $number) {
            $this->get(route('kpi.index', ['role_id' => $role->role_id, 'year' => 2026, 'period_type' => $type, 'period_number' => $number]))->assertOk()->assertSee('Kinerja harian akhir tahun');
        }
        $this->post($url, ['type' => 'harian', 'year' => 2026, 'date' => '2026-12-31'])->assertSessionHasErrors('number');
        $this->post($url, ['type' => 'harian', 'year' => 2026, 'date' => '2026-02-30'])->assertSessionHasErrors('date');
    }
}
