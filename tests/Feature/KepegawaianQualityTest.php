<?php
namespace Tests\Feature;

use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class KepegawaianQualityTest extends TestCase
{
    use DatabaseTransactions;

    public function test_blank_and_zero_nip_are_optional()
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('pegawai')) {
            \Illuminate\Support\Facades\DB::statement('CREATE TEMPORARY TABLE pegawai (id INT PRIMARY KEY, nik VARCHAR(50), nip VARCHAR(50))');
        }
        $controller = new \App\Http\Controllers\Api\KepegawaianController();
        $method = new \ReflectionMethod($controller, 'validateIdentity');
        $method->setAccessible(true);
        foreach (['', '0', '0000', null] as $nip) {
            $request = new Request(['nik' => '9876543210987654', 'nip' => $nip]);
            $method->invoke($controller, $request);
            $this->assertNull($request->input('nip'));
        }
    }

    public function test_age_and_remaining_service_change_without_editing_employee()
    {
        try {
            Carbon::setTestNow(Carbon::parse('2026-10-13 00:00:00', 'Asia/Jakarta'));
            $employee = new Pegawai(['tanggal_lahir' => '1980-10-14', 'perkiraan_pensiun' => '2027-10-14', 'usia' => 1, 'sisa_masa_kerja' => 'old']);
            $this->assertSame(45, $employee->usia);
            $this->assertSame('1 Tahun 0 Bulan 1 Hari', $employee->sisa_masa_kerja);
            $employee->masa_kerja = '2021-06-01';
            $this->assertSame('5 Tahun 4 Bulan 12 Hari', $employee->toArray()['masa_kerja_durasi']);
            $this->assertSame('2021-06-01', $employee->masa_kerja);
            Carbon::setTestNow(Carbon::parse('2026-10-14 00:00:00', 'Asia/Jakarta'));
            $this->assertSame(46, $employee->toArray()['usia']);
            $this->assertSame('5 Tahun 4 Bulan 13 Hari', $employee->masa_kerja_durasi);
            $this->assertSame('1 Tahun 0 Bulan 0 Hari', $employee->sisa_masa_kerja);
            Carbon::setTestNow(Carbon::parse('2027-10-14 00:00:00', 'Asia/Jakarta'));
            $this->assertSame('0 Tahun 0 Bulan 0 Hari', $employee->sisa_masa_kerja);
        } finally { Carbon::setTestNow(); }
    }

    public function test_duplicate_identities_are_rejected_but_existing_owner_can_keep_them()
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('pegawai')) {
            \Illuminate\Support\Facades\DB::statement('CREATE TEMPORARY TABLE pegawai (id INT PRIMARY KEY, nik VARCHAR(50), nip VARCHAR(50))');
        }
        \Illuminate\Support\Facades\DB::table('pegawai')->insert(['id' => 999999, 'nik' => '0123456789012345', 'nip' => '001234']);
        $employee = Pegawai::findOrFail(999999);
        $controller = new \App\Http\Controllers\Api\KepegawaianController();
        $request = new Request(['nik' => '0123456789012345', 'nip' => '001234']);
        try {
            $controller->store($request);
            $this->fail('Identitas ganda harus ditolak.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('nik', $exception->errors());
            $this->assertArrayHasKey('nip', $exception->errors());
        }
        $method = new \ReflectionMethod($controller, 'validateIdentity');
        $method->setAccessible(true);
        $method->invoke($controller, $request, $employee->id);
        $this->assertSame('001234', $request->input('nip'));
    }
}
