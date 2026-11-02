<?php
namespace Tests\Feature;

use App\Models\{Role, User, KpiTask};
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class KpiProgramTest extends TestCase
{
    use DatabaseTransactions;

    public function test_all_program_groups_are_mapped_to_their_fields()
    {
        $fields = [
            'Direktur' => 'program_direktur',
            'Wakil Direktur Investasi Dan Manajemen Risiko' => 'program_wakil_investasi',
            'Wakil Direktur Keuangan dan Akuntansi' => 'program_wakil_keuangan',
            'Wakil Direktur Kepatuhan, Tata Kelola dan SDM' => 'program_wakil_kepatuhan_tata_kelola_sdm',
            'Bidang Keuangan' => 'program_bidang_keuangan', 'Bidang Kepesertaan' => 'program_bidang_kepesertaan',
            'Bidang Sistem Informasi dan Digitalisasi Tata Kelola' => 'program_bidang_sistem_informasi',
            'Bidang Umum dan Administrasi' => 'program_bidang_umum_administrasi', 'Bidang Investasi' => 'program_bidang_investasi',
        ];
        foreach ($fields as $name => $type) $this->assertSame($type, \App\Services\KpiPrograms::typeFor(new Role(['role_name' => $name])));
    }

    public function test_program_execution_is_year_scoped_and_retains_legacy_semesters()
    {
        activity()->disableLogging();
        $role = Role::create(['role_name' => 'Bidang Kepesertaan', 'role_status' => 1]);
        $user = new User(['user_id' => 'program-test', 'user_role_id' => $role->role_id]);
        $user->setRelation('role', $role);
        $this->actingAs($user);
        DB::table('programs')->insert([
            ['program_id' => 'kpi-source-test', 'program_nama' => 'Program uji semester', 'program_type' => 'program_bidang_kepesertaan'],
            ['program_id' => 'kpi-other-test', 'program_nama' => 'Program bidang lain', 'program_type' => 'program_bidang_investasi'],
        ]);
        $filters = ['role_id' => $role->role_id, 'year' => 2026, 'tab' => 'programs'];
        $response = $this->get(route('kpi.index', $filters))->assertOk()->assertSee('Program uji semester')->assertDontSee('Program bidang lain');
        $this->assertTrue($response->viewData('programTasks')->isEmpty());
        $data = ['type' => 'tahunan', 'year' => 2026, 'number' => 1, 'program_id' => 'kpi-source-test',
            'title' => 'Pelaksanaan pertama', 'description' => 'Mengolah data peserta', 'progress' => 50, 'evaluation' => 'Perlu tindak lanjut', 'links' => ['https://example.test/bukti']];
        $this->post(route('kpi.tasks.storeForRole', $role), $data)->assertRedirect()->assertSessionHasNoErrors();
        $task = KpiTask::where('title', 'Pelaksanaan pertama')->firstOrFail();
        $this->assertSame('Program uji semester', $task->program_name);
        $this->post(route('kpi.tasks.storeForRole', $role), array_merge($data, ['title' => 'Pelaksanaan kedua', 'progress' => 99.9]))->assertRedirect()->assertSessionHasNoErrors();
        $this->assertEquals(99.9, KpiTask::where('title', 'Pelaksanaan kedua')->firstOrFail()->progress);
        $response = $this->get(route('kpi.index', $filters))->assertOk()->assertSee('Pelaksanaan pertama')->assertSee('Perlu tindak lanjut');
        $this->assertCount(2, $response->viewData('programTasks'));
        $achievement = $this->get(route('kpi.index', array_merge($filters, ['tab' => 'achievement'])))->assertOk()->assertSee('Pencapaian KPI 2026');
        $row = $achievement->viewData('achievements')->firstWhere('program', 'Program uji semester');
        $this->assertSame(3, $row['grade']);
        $this->assertEquals(74.9, $row['percentage']);
        $export = $this->get(route('kpi.export', array_merge($filters, ['report' => 'achievement', 'format' => 'xlsx', 'scope' => 'all', 'preview' => 1])))->assertOk()->assertSee('Pencapaian KPI');
        $exportRow = collect($export->viewData('rows'))->first(fn ($cells) => ($cells[0] ?? '') === 'Program uji semester');
        $this->assertEquals(74.9, $exportRow[2]);
        $this->assertSame(3, $exportRow[3]);
        $this->assertSame('Cukup', $exportRow[4]);
        $taskOnly = $this->get(route('kpi.export', array_merge($filters, ['format' => 'xlsx', 'scope' => 'all', 'preview' => 1])))->assertOk();
        $this->assertFalse(collect($taskOnly->viewData('rows'))->contains(fn ($cells) => ($cells[0] ?? '') === 'Rata-rata seluruh program'));
        $programExport = $this->get(route('kpi.export', array_merge($filters, ['report' => 'programs', 'format' => 'xlsx', 'scope' => 'all', 'preview' => 1])))->assertOk();
        $this->assertSame('Program Kerja', $programExport->viewData('rows')[0][0]);
        $this->assertTrue(collect($programExport->viewData('rows'))->contains(fn ($cells) => ($cells[1] ?? '') === 'Pelaksanaan pertama'));
        $pdfData = [];
        \Illuminate\Support\Facades\View::composer('layout.KPI.annual-pdf', function ($view) use (&$pdfData) { $pdfData = $view->getData(); });
        $this->get(route('kpi.export', array_merge($filters, ['report' => 'achievement', 'format' => 'pdf', 'scope' => 'all', 'preview' => 1])))->assertOk();
        $this->assertEquals($export->viewData('achievements')->toArray(), $pdfData['achievements']->toArray());
        $this->assertEquals(\App\Services\KpiAchievement::summary($export->viewData('achievements')), $pdfData['achievementSummary']);
        $this->get(route('kpi.export', array_merge($filters, ['report' => 'programs', 'format' => 'pdf', 'scope' => 'all', 'preview' => 1])))->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertSame('programs', $pdfData['report']);
        $response->assertSee('Pelaksanaan 1')->assertSee('Pelaksanaan 2')->assertDontSee('Lihat catatan');
        foreach ($response->viewData('programTasks') as $execution) {
            $response->assertSee('data-bs-target="#kpi-execution-'.$execution->id.'"', false)
                ->assertSee('id="kpi-execution-'.$execution->id.'"', false);
        }
        foreach ([1, 2] as $semester) {
            $period = \App\Models\KpiPeriod::create(['role_id' => $role->role_id, 'type' => 'semesteran', 'year' => 2026, 'number' => $semester,
                'starts_at' => $semester === 1 ? '2026-01-01' : '2026-07-01', 'ends_at' => $semester === 1 ? '2026-06-30' : '2026-12-31']);
            $period->tasks()->create(['title' => 'Catatan semester lama '.$semester, 'program_id' => 'kpi-source-test', 'program_name' => 'Program uji semester', 'progress' => 100]);
        }
        $annual = $this->get(route('kpi.index', $filters))->assertOk()->assertDontSee('id="program-semester"', false);
        $this->assertCount(4, $annual->viewData('programTasks'));
        $this->assertCount(0, $this->get(route('kpi.index', array_merge($filters, ['year' => 2025])))->assertOk()->viewData('programTasks'));
        $this->post(route('kpi.tasks.storeForRole', $role), array_merge($data, ['type' => 'bulanan']))->assertSessionHasErrors('program_id');
        $this->post(route('kpi.tasks.storeForRole', $role), array_merge($data, ['program_id' => 'kpi-other-test']))->assertSessionHasErrors('program_id');
        $other = Role::create(['role_name' => 'Bidang Investasi', 'role_status' => 1]);
        $this->post(route('kpi.tasks.storeForRole', $other), $data)->assertForbidden();
        $this->assertDatabaseHas('programs', ['program_id' => 'kpi-source-test', 'program_nama' => 'Program uji semester']);
        DB::table('programs')->where('program_id', 'kpi-source-test')->delete();
        $this->get(route('kpi.index', $filters))->assertOk()->assertSee('Program asal sudah dihapus')->assertSee('Pelaksanaan pertama');
        $this->assertSame('Program uji semester', $task->fresh()->program_name);
    }
}
