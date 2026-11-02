<?php

namespace Tests\Feature;

use App\Models\{Role, User, KpiPeriod};
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class KpiReportTest extends TestCase
{
    use DatabaseTransactions;

    public function test_exports_include_all_pages_or_filtered_tasks_and_pdf_repeats_header()
    {
        activity()->disableLogging();
        $role = Role::create(['role_name' => 'Report owner', 'role_status' => 1]);
        $user = new User(['user_id' => 'report-test', 'user_role_id' => $role->role_id]);
        $user->setRelation('role', $role);
        $this->actingAs($user);
        $period = KpiPeriod::create(['role_id' => $role->role_id, 'type' => 'bulanan', 'year' => 2026, 'number' => 5, 'starts_at' => '2026-05-01', 'ends_at' => '2026-05-31']);
        foreach (range(1, 50) as $number) $period->tasks()->create(['title' => 'Laporan '.$number, 'progress' => 100,
            'description' => $number === 1 ? str_repeat("Keterangan panjang dengan pergantian baris.\n", 100).'AKHIR KETERANGAN' : null,
            'evaluation' => $number === 1 ? str_repeat('Evaluasi panjang ', 280).'AKHIR EVALUASI' : null]);
        $period->tasks()->create(['title' => 'Persiapan', 'progress' => 0]);
        $filters = ['role_id' => $role->role_id, 'year' => 2026, 'period_type' => 'bulanan', 'period_number' => 5, 'status' => 'not_started', 'scope' => 'all', 'format' => 'xlsx', 'page' => 2];
        \Maatwebsite\Excel\Facades\Excel::fake();
        $this->get(route('kpi.export', $filters))->assertOk();
        \Maatwebsite\Excel\Facades\Excel::assertDownloaded('KPI-2026-bulanan-5.xlsx', fn ($export) => count($export->array()) === 57);
        $filters['scope'] = 'filtered';
        $preview = $this->get(route('kpi.export', $filters + ['preview' => 1]))->assertOk()->assertSee('Preview Excel KPI')->assertSee('Persiapan');
        $this->assertNull($preview->headers->get('Content-Disposition'));
        $this->assertCount(7, $preview->viewData('rows'));
        $this->get(route('kpi.export', $filters))->assertOk();
        \Maatwebsite\Excel\Facades\Excel::assertDownloaded('KPI-2026-bulanan-5.xlsx', fn ($export) => count($export->array()) === 7 && $export->array()[6][1] === 'Persiapan');
        $filters['format'] = 'pdf';
        $filters['scope'] = 'all';
        $renderedRows = [];
        \Illuminate\Support\Facades\View::composer('layout.KPI.pdf', function ($view) use (&$renderedRows) {
            $renderedRows = $view->getData()['pdfRows'];
        });
        $response = $this->get(route('kpi.export', $filters))->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertGreaterThan(51, count($renderedRows));
        $allText = '';
        foreach ($renderedRows as $row) foreach ($row['cells'] as $cell) {
            $this->assertLessThanOrEqual(8, count($cell));
            foreach ($cell as $line) $allText .= $line['text'];
        }
        $this->assertStringContainsString('AKHIR KETERANGAN', $allText);
        $this->assertStringContainsString('AKHIR EVALUASI', $allText);
        $this->assertStringStartsWith('%PDF', $response->getContent());
        $this->assertStringContainsString('TimesNewRoman', $response->getContent());
        $pdfPreview = $this->get(route('kpi.export', $filters + ['preview' => 1]))->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString('inline', $pdfPreview->headers->get('Content-Disposition'));
        preg_match_all('/\/Type\s*\/Page\b/', $response->getContent(), $pages);
        $this->assertGreaterThan(1, count($pages[0]));
        preg_match_all('/(\d+) 0 obj\b(.*?)endobj/s', $response->getContent(), $objects, PREG_SET_ORDER);
        $headers = [];
        $pageStreams = [];
        foreach ($objects as $object) {
            if (!preg_match('/stream\r?\n(.*?)\r?\nendstream/s', $object[2], $stream)) continue;
            $decoded = @gzuncompress($stream[1]);
            if ($decoded !== false) $pageStreams[$object[1]] = $decoded;
            if ($decoded !== false && preg_match('/\/I\w+\s+Do\b/', $decoded)) $headers[] = $object[1].' 0 R';
        }
        $this->assertNotEmpty($headers);
        foreach ($objects as $object) {
            if (!preg_match('/\/Type\s*\/Page\b/', $object[2])) continue;
            $this->assertTrue(collect($headers)->contains(fn ($header) => str_contains($object[2], $header)), 'Setiap halaman harus menyertakan objek kop.');
            preg_match_all('/(\d+) 0 R/', $object[2], $references);
            $pageText = '';
            foreach ($references[1] as $reference) $pageText .= $pageStreams[$reference] ?? '';
            foreach (['Periode asal', 'Tugas / Keterangan', 'Progres / Status', 'Evaluasi', 'Bukti / Link'] as $heading) {
                $this->assertTrue(str_contains($pageText, mb_convert_encoding($heading, 'UTF-16BE', 'UTF-8')), 'Header tabel harus ada di setiap halaman: '.$heading);
            }
        }
        $this->get(route('kpi.export', array_merge($filters, ['role_id' => 'missing'])))->assertNotFound();
    }

    public function test_history_tracks_changes_deletion_and_isolates_roles()
    {
        activity()->disableLogging();
        $role = Role::create(['role_name' => 'History owner', 'role_status' => 1]);
        $other = Role::create(['role_name' => 'History other', 'role_status' => 1]);
        $user = new User(['user_id' => 'history-test', 'user_nama' => 'Pencatat', 'user_role_id' => $role->role_id]);
        $user->setRelation('role', $role);
        $this->actingAs($user);
        $data = ['type' => 'bulanan', 'year' => 2026, 'number' => 5, 'title' => 'Tugas riwayat', 'progress' => 0];
        $this->post(route('kpi.tasks.storeForRole', $role), $data)->assertRedirect();
        $task = \App\Models\KpiTask::where('title', 'Tugas riwayat')->firstOrFail();
        $this->put(route('kpi.tasks.update', $task), array_merge($data, ['progress' => 100]))->assertRedirect();
        $this->assertSame(0, Activity::where('log_name', 'kpi-history')->latest('id')->first()->properties['before']['Progres (%)']);
        $this->delete(route('kpi.tasks.destroy', $task))->assertRedirect();
        $this->get(route('kpi.history', ['role_id' => $role->role_id, 'task_id' => $task->id]))->assertOk()->assertSee('Dihapus')->assertSee('Pencatat')->assertSee('Tugas riwayat');
        $this->get(route('kpi.history', ['role_id' => $other->role_id, 'task_id' => $task->id]))->assertOk()->assertDontSee('Tugas riwayat');
        $this->assertSame(3, Activity::where('log_name', 'kpi-history')->where('subject_id', $task->id)->count());
    }
}
