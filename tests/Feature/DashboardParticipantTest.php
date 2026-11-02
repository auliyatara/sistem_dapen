<?php

namespace Tests\Feature;

use Tests\TestCase;

class DashboardParticipantTest extends TestCase
{
    public function test_participant_statistics_can_group_without_document_count_columns()
    {
        $result = (new \App\Http\Controllers\Api\DashboardController())->pesertaStatistik();
        $this->assertTrue($result['status']);
        $this->assertEquals($result['data']['jabatan']->sum('total'), $result['data']['peserta']['total']);
        $this->assertEquals($result['data']['pensiunan']['kategori']->sum('total'), $result['data']['pensiunan']['total']);
        $this->assertCount(3, $result['data']['jabatan']);
        $this->assertEquals(\App\Models\Kepesertaan::whereIn('kepesertaan_status_kepesertaan', ['Pensiunan', 'Pensiun Ditunda', 'Pensiun Sekaligus', 'Pensiunan Yang Sudah Tidak Berhak Menerima MP'])->count(), $result['data']['pensiunan']['total_semua']);
    }
}
