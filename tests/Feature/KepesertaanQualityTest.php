<?php

namespace Tests\Feature;

use App\Models\Kepesertaan;
use App\Http\Controllers\Api\KepesertaanController;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class KepesertaanQualityTest extends TestCase
{
    use DatabaseTransactions;

    public function test_nip_can_be_blank_when_saving_profile()
    {
        $method = new \ReflectionMethod(new KepesertaanController(), 'validateIdentity');
        $method->setAccessible(true);
        $request = new Request(['kepesertaan_nik' => '', 'kepesertaan_nip' => '']);
        $method->invoke(new KepesertaanController(), $request);
        $this->assertSame('', $request->input('kepesertaan_nip'));
        $this->assertNull($request->input('kepesertaan_nik'));
    }

    public function test_blank_or_placeholder_nik_is_allowed_but_real_nik_format_is_checked()
    {
        $controller = new KepesertaanController();
        $method = new \ReflectionMethod($controller, 'validateIdentity');
        $method->setAccessible(true);
        foreach (['', '0000000000000000', null] as $nik) {
            $request = new Request(['kepesertaan_nik' => $nik, 'kepesertaan_nip' => '999999876543210']);
            $method->invoke($controller, $request);
            $this->assertNull($request->input('kepesertaan_nik'));
        }
        $this->expectException(ValidationException::class);
        $method->invoke($controller, new Request(['kepesertaan_nik' => '12345', 'kepesertaan_nip' => '999999876543210']));
    }

    public function test_completeness_reports_missing_fields_and_accepts_zero_phdp()
    {
        $participant = new Kepesertaan(['kepesertaan_nama' => 'Test', 'kepesertaan_gaji_pokok' => 0, 'dokumen_tersedia' => 0]);
        $this->assertFalse($participant->kelengkapan['lengkap']);
        $this->assertContains('NIK', $participant->kelengkapan['kurang']);
        $this->assertContains('Dokumen pendukung', $participant->kelengkapan['kurang']);
        $this->assertNotContains('PHDP', $participant->kelengkapan['kurang']);
        $participant->dokumen_tersedia = 1;
        $this->assertNotContains('Dokumen pendukung', $participant->kelengkapan['kurang']);
    }

    public function test_duplicate_identities_are_rejected_and_current_participant_is_excluded()
    {
        // Roll back a minimal fixture after checking validation, without uploading files.
        $columns = \Illuminate\Support\Facades\DB::select('SHOW COLUMNS FROM kepesertaans');
        $fixture = [];
        foreach ($columns as $column) {
            if ($column->Null === 'NO' && $column->Default === null && !str_contains($column->Extra, 'auto_increment')) {
                $fixture[$column->Field] = preg_match('/int|decimal|float|double/', $column->Type) ? 0 : (preg_match('/date|time/', $column->Type) ? '2026-01-01' : 'Test');
            }
        }
        $fixture = array_merge($fixture, ['kepesertaan_id' => 'quality-test', 'kepesertaan_nik' => '0123456789012345', 'kepesertaan_nip' => '001234']);
        \Illuminate\Support\Facades\DB::table('kepesertaans')->insert($fixture);
        $controller = new KepesertaanController();
        $request = new Request(['kepesertaan_nik' => '0123456789012345', 'kepesertaan_nip' => '001234']);
        $method = new \ReflectionMethod($controller, 'validateIdentity');
        $method->setAccessible(true);
        try {
            $method->invoke($controller, $request);
            $this->fail('Identitas ganda harus ditolak.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('kepesertaan_nik', $exception->errors());
            $this->assertArrayHasKey('kepesertaan_nip', $exception->errors());
        }
        $method->invoke($controller, $request, 'quality-test');
        $this->assertSame('0123456789012345', $request->input('kepesertaan_nik'));
        $loaded = Kepesertaan::findOrFail('quality-test');
        $this->assertArrayHasKey('kelengkapan', $loaded->toArray());
        $this->assertContains('Dokumen pendukung', $loaded->kelengkapan['kurang']);
        $user = new \App\Models\User(['user_id' => 'quality-user']);
        $this->actingAs($user);
        $user->setRelation('role', new \App\Models\Role(['role_name' => 'Investasi', 'role_status' => 1]));
        foreach (['store', 'update', 'destroy'] as $action) {
            try {
                if ($action === 'store') $controller->store($request);
                elseif ($action === 'update') $controller->update($request, 'quality-test');
                else $controller->destroy('quality-test');
                $this->fail('Akun tanpa izin edit harus ditolak.');
            } catch (\Illuminate\Auth\Access\AuthorizationException $exception) {
                $this->assertFalse(\Illuminate\Support\Facades\Gate::allows('manage-kepesertaan'));
            }
        }
        $user->setRelation('role', new \App\Models\Role(['role_name' => 'Bidang Kepesertaan', 'role_status' => 1]));
        \Illuminate\Support\Facades\Storage::fake('public');
        \Illuminate\Support\Facades\Storage::disk('public')->put('kepesertaan_dokumen/old.pdf', 'original');
        \Illuminate\Support\Facades\DB::table('kepesertaan_dokumens')->insert([
            'kepesertaan_dokumen_id' => 'quality-doc', 'kepesertaan_dokumen_kepesertaan_id' => 'quality-test',
            'kepesertaan_dokumen_judul' => 'Original', 'kepesertaan_dokumen_file' => 'old.pdf',
        ]);
        $fixture['kepesertaan_id'] = 'quality-other';
        $fixture['kepesertaan_nik'] = '0123456789012346';
        $fixture['kepesertaan_nip'] = '001235';
        \Illuminate\Support\Facades\DB::table('kepesertaans')->insert($fixture);
        try {
            $controller->update(new Request(['kepesertaan_nik' => $fixture['kepesertaan_nik'], 'kepesertaan_nip' => $fixture['kepesertaan_nip'], 'list_dokumen_id' => ['quality-doc']]), 'quality-other');
            $this->fail('Dokumen peserta lain harus ditolak.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('list_dokumen_id.0', $exception->errors());
        }
        // Remove document rows, then force a failure later in the update transaction.
        $failed = $controller->update(new Request(['kepesertaan_nik' => '0123456789012345', 'kepesertaan_nip' => '001234', 'list_dokumen_id' => [], 'list_dokumen_nama' => []]), 'quality-test');
        $this->assertFalse($failed['status']);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists('kepesertaan_dokumen/old.pdf');
        $this->assertDatabaseHas('kepesertaan_dokumens', ['kepesertaan_dokumen_id' => 'quality-doc']);
    }
}
