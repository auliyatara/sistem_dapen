<?php
namespace Tests\Feature;

use App\Services\KpiAchievement;
use Tests\TestCase;

class KpiAchievementTest extends TestCase
{
    public function test_all_rating_boundaries()
    {
        foreach ([[100,1], [99.9,2], [80,2], [79.9,3], [60,3], [59.9,4], [40,4], [39.9,5], [0,5]] as [$percentage,$grade]) {
            $this->assertSame($grade, KpiAchievement::grade($percentage));
        }
    }

    public function test_program_score_uses_average_and_unexecuted_program_gets_five()
    {
        $programs = collect([(object) ['program_id' => 'a', 'program_nama' => 'Program A'], (object) ['program_id' => 'b', 'program_nama' => 'Program B']]);
        $tasks = collect([(object) ['program_id' => 'a', 'progress' => 100], (object) ['program_id' => 'a', 'progress' => 59.9]]);
        $rows = KpiAchievement::rows($programs, $tasks);
        $this->assertSame(3, $rows[0]['grade']);
        $this->assertEquals(79.9, $rows[0]['percentage']);
        $this->assertSame(2, $rows[0]['count']);
        $this->assertSame(5, $rows[1]['grade']);
        $this->assertSame(0, $rows[1]['count']);
        $summary = KpiAchievement::summary($rows);
        $this->assertEquals(39.9, $summary['percentage']);
        $this->assertSame(5, $summary['grade']);
        $this->assertSame('Sangat kurang', $summary['label']);
        $this->assertNull(KpiAchievement::summary(collect())['grade']);
        $nearComplete = KpiAchievement::rows($programs->take(1), collect([(object) ['program_id' => 'a', 'progress' => 100], (object) ['program_id' => 'a', 'progress' => 99.9]]));
        $this->assertEquals(99.9, $nearComplete[0]['percentage']);
        $this->assertSame(2, $nearComplete[0]['grade']);
        $complete = KpiAchievement::rows($programs->take(1), collect([(object) ['program_id' => 'a', 'progress' => 100]]));
        $this->assertSame('Baik sekali', KpiAchievement::summary($complete)['label']);
    }
}
