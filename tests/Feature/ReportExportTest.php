<?php

namespace Tests\Feature;

use App\Enums\PaymentState;
use App\Enums\PaymentStatus;
use App\Models\CheckIn;
use App\Models\Gym;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\ReportExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportExportTest extends TestCase
{
    use RefreshDatabase;

    private Gym $gym;

    private Member $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->gym = Gym::factory()->create();
        $this->member = Member::factory()->for($this->gym)->create([
            'name' => 'Adrian Test',
            'phone' => '09171234567',
        ]);
    }

    public function test_it_exports_attendance_report_with_peak_hour(): void
    {
        $plan = Plan::factory()->for($this->gym)->create(['name' => 'Monthly Boxing']);

        $subscription = Subscription::create([
            'gym_id' => $this->gym->id,
            'member_id' => $this->member->id,
            'plan_id' => $plan->id,
            'starts_at' => today(),
            'ends_at' => today()->addDays(30),
            'amount' => 1500,
            'payment_status' => PaymentStatus::Paid,
        ]);

        CheckIn::create([
            'gym_id' => $this->gym->id,
            'member_id' => $this->member->id,
            'subscription_id' => $subscription->id,
            'status' => 'active',
            'message' => 'Welcome Adrian!',
            'scanned_at' => now(),
        ]);

        $service = app(ReportExportService::class);
        $response = $service->exportAttendance($this->gym, ['range' => 'this_month']);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('text/csv; charset=UTF-8', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attendance-report-', (string) $response->headers->get('Content-Disposition'));

        // Capture streamed content
        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertStringContainsString('Peak Hour Window', $content);
        $this->assertStringContainsString('Adrian Test', $content);
        $this->assertStringContainsString('Monthly Boxing', $content);
        $this->assertStringContainsString('YES', $content);
    }

    public function test_it_exports_revenue_report_with_transactions(): void
    {
        Payment::create([
            'gym_id' => $this->gym->id,
            'member_id' => $this->member->id,
            'amount' => 2500.00,
            'currency' => 'PHP',
            'status' => PaymentState::Paid,
            'payment_method' => 'gcash',
            'reference_number' => 'GCASH-FL-TEST-123',
            'paid_at' => now(),
        ]);

        $service = app(ReportExportService::class);
        $response = $service->exportRevenue($this->gym, ['range' => 'this_month', 'status' => 'all']);

        $this->assertEquals(200, $response->getStatusCode());

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertStringContainsString('GCASH-FL-TEST-123', $content);
        $this->assertStringContainsString('2500.00', $content);
        $this->assertStringContainsString('GCASH', $content);
    }

    public function test_it_respects_tenant_isolation_on_exports(): void
    {
        $otherGym = Gym::factory()->create();
        $otherMember = Member::factory()->for($otherGym)->create(['name' => 'Secret Other Gym Member']);

        CheckIn::create([
            'gym_id' => $otherGym->id,
            'member_id' => $otherMember->id,
            'status' => 'active',
            'scanned_at' => now(),
        ]);

        $service = app(ReportExportService::class);
        $response = $service->exportAttendance($this->gym, ['range' => 'all_time']);

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertStringNotContainsString('Secret Other Gym Member', $content);
    }
}
