<?php

namespace Tests\Feature;

use App\Models\Gym;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GymScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_default_operating_schedule(): void
    {
        $gym = Gym::factory()->create();

        $schedule = $gym->getOperatingSchedule();

        $this->assertEquals(config('fitledger.schedules.default_opening_time', '06:00'), $schedule['opening_time']);
        $this->assertEquals(config('fitledger.schedules.default_closing_time', '22:00'), $schedule['closing_time']);
        $this->assertIsArray($schedule['operating_days']);
    }

    public function test_it_correctly_evaluates_if_gym_is_open_now(): void
    {
        $gym = Gym::factory()->create([
            'settings' => [
                'schedule' => [
                    'opening_time' => '08:00',
                    'closing_time' => '20:00',
                    'operating_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                ],
            ],
        ]);

        // Simulated time: Wednesday 10:00 AM (Open)
        Carbon::setTestNow(Carbon::parse('2026-11-04 10:00:00')); // Wednesday
        $this->assertTrue($gym->isOpenNow());

        // Simulated time: Wednesday 07:00 AM (Before opening)
        Carbon::setTestNow(Carbon::parse('2026-11-04 07:00:00'));
        $this->assertFalse($gym->isOpenNow());

        // Simulated time: Wednesday 21:00 PM (After closing)
        Carbon::setTestNow(Carbon::parse('2026-11-04 21:00:00'));
        $this->assertFalse($gym->isOpenNow());

        // Simulated time: Sunday 12:00 PM (Closed day)
        Carbon::setTestNow(Carbon::parse('2026-11-08 12:00:00')); // Sunday
        $this->assertFalse($gym->isOpenNow());

        Carbon::setTestNow(); // Reset test time
    }
}
