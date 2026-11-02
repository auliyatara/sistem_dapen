<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class WorkingHoursTest extends TestCase
{
    public function test_warning_follows_wib_work_hour_boundaries()
    {
        $this->actingAs(new User(['user_id' => 'hours-test']));
        try {
            foreach (['07:59:59' => true, '08:00:00' => false, '15:59:59' => false, '16:00:00' => true, '23:00:00' => true] as $time => $expected) {
                Carbon::setTestNow(Carbon::parse('2026-10-02 '.$time, 'Asia/Jakarta'));
                $html = view('component.working-hours')->render();
                $this->assertSame($expected, str_contains($html, 'ANDA TELAH MELEBIHI BATAS JAM KERJA'), $time);
                if ($expected) {
                    $this->assertStringContainsString(route('logout'), $html);
                    $this->assertStringContainsString('name="_token"', $html);
                }
            }
        } finally {
            Carbon::setTestNow();
        }
    }
}
