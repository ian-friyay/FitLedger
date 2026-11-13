<?php

namespace Tests\Feature;

use App\Models\ClassBooking;
use App\Models\Gym;
use App\Models\GymClass;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GymClassAndBookingTest extends TestCase
{
    use RefreshDatabase;

    private Gym $gym;

    private User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->gym = Gym::factory()->create([
            'is_active' => true,
            'trial_ends_at' => today()->addDays(14),
        ]);

        $this->owner = User::factory()->create([
            'gym_id' => $this->gym->id,
            'is_platform_admin' => false,
            'is_active' => true,
        ]);
    }

    public function test_owner_can_access_gym_classes_and_bookings_pages(): void
    {
        $this->actingAs($this->owner);

        $base = "/admin/{$this->gym->slug}";

        $this->get("{$base}/gym-classes")->assertSuccessful();
        $this->get("{$base}/class-bookings")->assertSuccessful();
    }

    public function test_it_creates_gym_class_and_booking(): void
    {
        $class = GymClass::create([
            'gym_id' => $this->gym->id,
            'name' => 'Boxing Conditioning',
            'capacity' => 15,
            'schedule_time' => now()->addDay()->setHour(18)->setMinute(0),
            'duration_minutes' => 60,
            'instructor' => 'Coach Manny',
        ]);

        $member = Member::factory()->for($this->gym)->create();

        $booking = ClassBooking::create([
            'gym_id' => $this->gym->id,
            'gym_class_id' => $class->id,
            'member_id' => $member->id,
            'status' => 'booked',
        ]);

        $this->assertDatabaseHas('gym_classes', [
            'id' => $class->id,
            'name' => 'Boxing Conditioning',
            'gym_id' => $this->gym->id,
        ]);

        $this->assertDatabaseHas('class_bookings', [
            'id' => $booking->id,
            'gym_class_id' => $class->id,
            'member_id' => $member->id,
            'status' => 'booked',
        ]);

        $this->assertSame($this->gym->id, $booking->gym_id);
        $this->assertSame($member->name, $booking->member->name);
        $this->assertSame($class->name, $booking->gymClass->name);
    }
}
