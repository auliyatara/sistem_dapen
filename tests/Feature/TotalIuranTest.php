<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TotalIuranTest extends TestCase
{
    use DatabaseTransactions;

    public function test_total_uses_same_phdp_for_participant_and_employer_rates()
    {
        DB::table('kepesertaans')->insert([
            'kepesertaan_id' => 'total-rate-test', 'kepesertaan_nama' => 'Rate test',
            'kepesertaan_jenis_kelamin' => 'Laki-Laki', 'kepesertaan_nip' => 'rate-test',
            'kepesertaan_tempat_lahir' => 'Malang', 'kepesertaan_tanggal_lahir' => '1980-01-01',
            'kepesertaan_status_kepesertaan' => 'Peserta', 'kepesertaan_status_jabatan' => 'Dosen',
            'kepesertaan_terhitung_mulai_tanggal' => '2000-01-01', 'kepesertaan_telphone_1' => '',
            'kepesertaan_telphone_2' => '', 'kepesertaan_email_1' => '', 'kepesertaan_email_2' => '',
            'kepesertaan_alamat' => '', 'kepesertaan_jenis_pensiun_sekaligus' => '',
            'kepesertaan_terhitung_mulai_tanggal_pensiun' => '2040-01-01',
            'kepesertaan_gaji_pokok' => 10000000, 'kepesertaan_npwp' => '',
        ]);
        $before = (new \App\Http\Controllers\Api\TotalController())->index(new \Illuminate\Http\Request(['year' => 2098]))['data'];
        $this->assertSame('no_data', $before['pencocokan']['status']);
        foreach ([1 => 10000000, 2 => 20000000, 4 => 0, 5 => null] as $month => $phdp) {
            DB::table('kepesertaan_iurans')->insert([
                'kepesertaan_iuran_id' => 'total-rate-'.$month,
                'kepesertaan_iuran_kepesertaan_id' => 'total-rate-test',
                'kepesertaan_iuran_type' => 'iuran_total', 'kepesertaan_iuran_bulan' => $month,
                'kepesertaan_iuran_tahun' => 2098, 'kepesertaan_iuran_jumlah' => $phdp,
            ]);
        }
        $result = (new \App\Http\Controllers\Api\TotalController())->index(new \Illuminate\Http\Request(['year' => 2098]))['data'];
        $this->assertEquals(750000, $result['bulan'][0]['iuran_peserta']);
        $this->assertEquals(570000, $result['bulan'][0]['iuran_pemberi_kerja']);
        $this->assertEquals(2250000, $result['total_iuran_peserta']);
        $this->assertEquals(1710000, $result['total_iuran_pemberi_kerja']);
        $this->assertEquals(0, $result['bulan'][2]['iuran_pemberi_kerja']);
        $this->assertCount(12, $result['bulan']);
        $this->assertFalse($result['bulan'][2]['has_data']);
        $this->assertTrue($result['bulan'][3]['has_data']);
        $this->assertEquals(0, $result['bulan'][3]['iuran_peserta']);
        $this->assertFalse($result['bulan'][4]['has_data']);
        $this->assertSame(3, $result['jumlah_bulan_terisi']);
        $this->assertSame('matched', $result['pencocokan']['status']);
        $this->assertEquals(0, $result['pencocokan']['selisih']);
        $details = (new \App\Http\Controllers\Api\TotalController())->index(new \Illuminate\Http\Request(['year' => 2098, 'month' => 1]))['data'];
        $this->assertCount(1, $details['rincian']);
        $this->assertEquals('Rate test', $details['rincian'][0]->nama);
        $this->assertEquals($result['bulan'][0]['iuran_peserta'], $details['rincian']->sum('iuran_peserta'));
        $this->assertEquals($result['bulan'][0]['iuran_pemberi_kerja'], $details['rincian']->sum('iuran_pemberi_kerja'));
        $empty = (new \App\Http\Controllers\Api\TotalController())->index(new \Illuminate\Http\Request(['year' => 2098, 'month' => 3]))['data'];
        $this->assertCount(0, $empty['rincian']);
        $controller = new \App\Http\Controllers\Api\IuranController();
        $detail = $controller->index(new \Illuminate\Http\Request(['year' => 2098, 'participant_id' => 'total-rate-test']))['data'];
        $this->assertCount(12, $detail['bulan']);
        $this->assertNull($detail['bulan'][2]['iuran']);
        $this->assertEquals(0, $detail['bulan'][3]['iuran']);
        $this->assertNull($detail['bulan'][4]['phdp']);
        $this->assertTrue($detail['pengembangan_estimasi']);
        $this->assertEquals(750000 * (1 + 0.045 / 12), $detail['bulan'][0]['iuran_pengembangan']);
        $annual = $controller->index(new \Illuminate\Http\Request(['year' => 2098]))['data']['peserta']->firstWhere('kepesertaan_id', 'total-rate-test');
        $this->assertEquals(10000000, $annual->phdp);
        $this->assertEqualsWithDelta((float) $annual->iuran_pengembangan, $detail['bulan']->sum('iuran_pengembangan'), 0.001);
        $this->actingAs(new \App\Models\User(['user_id' => 'total-report-test']));
        $filters = ['year' => 2098, 'format' => 'xlsx', 'preview' => 1];
        $this->get(route('total.export', $filters))->assertOk()->assertSee('2.250.000,00');
        \Maatwebsite\Excel\Facades\Excel::fake();
        $this->get(route('total.export', ['year' => 2098, 'format' => 'xlsx']))->assertOk();
        \Maatwebsite\Excel\Facades\Excel::assertDownloaded('Rekap-Iuran-2098.xlsx', function ($export) {
            $rows = $export->array();
            return $rows[4][1] === 750000.0 && $rows[7][1] === 0.0 && $rows[6][1] === 'Belum ada data';
        });
        $pdf = $this->get(route('total.export', ['year' => 2098, 'format' => 'pdf', 'preview' => 1]))->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringContainsString('inline', $pdf->headers->get('Content-Disposition'));
        $this->assertStringContainsString('TimesNewRoman', $pdf->getContent());
        $this->get(route('total.export', ['year' => 2098, 'format' => 'pdf']))->assertOk()->assertDownload('Rekap-Iuran-2098.pdf');
        $this->getJson(route('total.export', ['year' => 2098, 'format' => 'invalid']))->assertUnprocessable();
        $this->get(route('iuran.index', ['year' => 2098]))->assertOk()->assertViewHas('iuranYear', 2098);
        // A record outside calendar months is included by the annual Iuran recap, but not Total.
        DB::table('kepesertaan_iurans')->insert([
            'kepesertaan_iuran_id' => 'bad-month-test', 'kepesertaan_iuran_kepesertaan_id' => 'total-rate-test',
            'kepesertaan_iuran_type' => 'iuran_total', 'kepesertaan_iuran_bulan' => 13,
            'kepesertaan_iuran_tahun' => 2098, 'kepesertaan_iuran_jumlah' => 1000,
        ]);
        $mismatch = (new \App\Http\Controllers\Api\TotalController())->index(new \Illuminate\Http\Request(['year' => 2098]))['data'];
        $this->assertSame('different', $mismatch['pencocokan']['status']);
        $this->assertEquals(-75, $mismatch['pencocokan']['selisih']);
        DB::table('kepesertaan_iurans')->insert([
            'kepesertaan_iuran_id' => 'develop-test', 'kepesertaan_iuran_kepesertaan_id' => 'total-rate-test',
            'kepesertaan_iuran_type' => 'iuran_pengembangan', 'kepesertaan_iuran_bulan' => 1,
            'kepesertaan_iuran_tahun' => 2098, 'kepesertaan_iuran_jumlah' => 12000000,
        ]);
        $detail = $controller->index(new \Illuminate\Http\Request(['year' => 2098, 'participant_id' => 'total-rate-test']))['data'];
        $this->assertFalse($detail['pengembangan_estimasi']);
        $this->assertNull($detail['bulan'][1]['iuran_pengembangan']);
        $this->assertEquals(12000000 * 0.075 * (1 + 0.045 / 12), $detail['bulan'][0]['iuran_pengembangan']);
        $annual = $controller->index(new \Illuminate\Http\Request(['year' => 2098]))['data']['peserta']->firstWhere('kepesertaan_id', 'total-rate-test');
        $this->assertEqualsWithDelta((float) $annual->iuran_pengembangan, $detail['bulan']->sum('iuran_pengembangan'), 0.001);
    }
}
