<?php

namespace Tests\Feature;

use App\Models\{KpiPeriod, Role, User};
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KpiTaskTest extends TestCase
{
    use DatabaseTransactions;

    public function test_tasks_progress_evidence_and_access_are_scoped_to_the_period_owner()
    {
        activity()->disableLogging();
        Storage::fake('local');
        $role = Role::create(['role_name' => 'Task owner', 'role_status' => 1]);
        $other = Role::create(['role_name' => 'Task viewer', 'role_status' => 1]);
        $user = new User(['user_id' => 'task-test', 'user_role_id' => $role->role_id]);
        $user->setRelation('role', $role);
        $this->actingAs($user);
        $period = KpiPeriod::create(['role_id' => $role->role_id, 'type' => 'bulanan', 'year' => 2026, 'number' => 1, 'starts_at' => '2026-01-01', 'ends_at' => '2026-01-31']);
        $url = route('kpi.tasks.store', $period);
        $this->post($url, ['title' => 'Tugas uji', 'progress' => 101])->assertSessionHasErrors('progress');
        $this->post($url, ['title' => 'Tugas uji', 'progress' => 25, 'evidence' => UploadedFile::fake()->create('bukti.pdf', 10, 'application/pdf')])->assertRedirect();
        $task = $period->tasks()->firstOrFail();
        Storage::disk('local')->assertExists($task->evidence_path);
        $this->get(route('kpi.index', ['role_id' => $role->role_id, 'period_id' => $period->id]))->assertOk()->assertSee('Tugas uji')->assertSee('25%')->assertSee('Tambah Tugas')->assertSee('id="kpi-year"', false);
        $this->get(route('kpi.index', ['role_id' => $role->role_id, 'year' => 2025]))->assertOk()->assertDontSee('Tugas uji')->assertSee('pada tahun 2025');
        $this->put(route('kpi.tasks.update', $task), ['title' => 'Tugas selesai', 'progress' => 100])->assertRedirect();
        $this->assertSame(100, $task->fresh()->progress);
        Storage::disk('local')->assertExists($task->fresh()->evidence_path);
        $this->put(route('kpi.tasks.update', $task), [
            'title' => 'Tugas selesai', 'progress' => 100,
            'links' => ['https://example.com/a', 'https://example.com/b'],
            'evidences' => [UploadedFile::fake()->image('foto.png'), UploadedFile::fake()->create('laporan.pdf', 10, 'application/pdf')],
        ])->assertSessionHasNoErrors()->assertRedirect();
        $task->refresh();
        $this->assertCount(3, $task->all_attachments);
        $this->assertCount(2, $task->links);
        $key = array_key_first($task->attachments);
        $removedPath = $task->attachments[$key]['path'];
        $this->get(route('kpi.tasks.evidence', ['task' => $task, 'attachment' => $key, 'preview' => 1]))->assertOk()->assertHeader('Content-Type', 'image/png');
        $this->get(route('kpi.tasks.evidence', ['task' => $task, 'attachment' => 'invalid']))->assertNotFound();
        $this->put(route('kpi.tasks.update', $task), ['title' => 'Tugas selesai', 'progress' => 100, 'links' => ['javascript:alert(1)']])->assertSessionHasErrors('links.0');
        $this->put(route('kpi.tasks.update', $task), ['title' => 'Tugas selesai', 'progress' => 100, 'remove_attachments' => [$key], 'links' => ['']])->assertRedirect();
        Storage::disk('local')->assertMissing($removedPath);
        $task->refresh();
        $this->assertCount(2, $task->all_attachments);
        $this->assertSame([], $task->links);
        $user->user_role_id = $other->role_id;
        $user->setRelation('role', $other);
        $this->actingAs($user);
        $this->get(route('kpi.index', ['role_id' => $role->role_id, 'period_id' => $period->id]))->assertOk()->assertSee('Tugas selesai')->assertDontSee('Tambah Tugas');
        $this->get(route('kpi.tasks.evidence', $task))->assertOk();
        $this->post($url, ['title' => 'Forbidden', 'progress' => 0])->assertForbidden();
        $this->put(route('kpi.tasks.update', $task), ['title' => 'Forbidden', 'progress' => 0])->assertForbidden();
        $this->delete(route('kpi.tasks.destroy', $task))->assertForbidden();
        $this->get(route('kpi.index', ['role_id' => $other->role_id, 'period_id' => $period->id]))->assertNotFound();
        $user->user_role_id = $role->role_id;
        $user->setRelation('role', $role);
        $this->actingAs($user);
        $this->delete(route('kpi.tasks.destroy', $task))->assertRedirect();
        Storage::disk('local')->assertMissing($task->evidence_path);
        $this->assertSame(0, $period->tasks()->count());
    }
}
