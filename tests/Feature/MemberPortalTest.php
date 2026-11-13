<?php

namespace Tests\Feature;

use App\Enums\PaymentState;
use App\Enums\PaymentStatus;
use App\Models\Gym;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\PayMongoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class MemberPortalTest extends TestCase
{
    use RefreshDatabase;

    private Gym $gym;

    protected function setUp(): void
    {
        parent::setUp();
        $this->gym = Gym::factory()->create();
    }

    public function test_lookup_page_renders_successfully(): void
    {
        $response = $this->get(route('portal.lookup'));

        $response->assertOk()
            ->assertSee('Find Your Member Pass')
            ->assertSee('Enter Phone Number, QR Code, or Email')
            ->assertSee('images/fitledger-logo.jpg')
            ->assertSee('shadow-orange-500/20');
    }

    public function test_lookup_redirects_to_pass_page_by_phone(): void
    {
        $member = Member::factory()->for($this->gym)->create([
            'phone' => '09171234567',
        ]);

        $response = $this->post(route('portal.find'), [
            'query' => '09171234567',
        ]);

        $response->assertRedirect(route('portal.pass', ['qr_code' => $member->qr_code]));
    }

    public function test_lookup_redirects_to_pass_page_by_qr_code(): void
    {
        $member = Member::factory()->for($this->gym)->create();

        $response = $this->post(route('portal.find'), [
            'query' => $member->qr_code,
        ]);

        $response->assertRedirect(route('portal.pass', ['qr_code' => $member->qr_code]));
    }

    public function test_lookup_shows_error_when_member_not_found(): void
    {
        $response = $this->from(route('portal.lookup'))->post(route('portal.find'), [
            'query' => 'nonexistent-phone',
        ]);

        $response->assertRedirect(route('portal.lookup'));
        $response->assertSessionHas('error');
    }

    public function test_pass_page_displays_member_and_gym_details(): void
    {
        $member = Member::factory()->for($this->gym)->create([
            'name' => 'John Doe',
        ]);

        $plan = Plan::factory()->for($this->gym)->create([
            'name' => 'Standard Monthly',
            'price' => 1200,
        ]);

        Subscription::create([
            'gym_id' => $this->gym->id,
            'member_id' => $member->id,
            'plan_id' => $plan->id,
            'amount' => 1200,
            'payment_status' => PaymentStatus::Paid,
            'starts_at' => today()->subDays(5),
            'ends_at' => today()->addDays(25),
        ]);

        $response = $this->get(route('portal.pass', ['qr_code' => $member->qr_code]));

        $response->assertOk()
            ->assertSee('John Doe')
            ->assertSee($this->gym->name)
            ->assertSee('Standard Monthly')
            ->assertSee($member->qr_code)
            ->assertSee('images/fitledger-logo.jpg')
            ->assertSee('shadow-orange-500/20')
            ->assertSee('<svg', false); // SVG QR Code
    }

    public function test_pass_page_returns_404_for_unknown_qr(): void
    {
        $response = $this->get(route('portal.pass', ['qr_code' => 'FL-UNKNOWN']));

        $response->assertNotFound();
    }

    public function test_pay_gcash_initiates_checkout_when_dues_exist(): void
    {
        $member = Member::factory()->for($this->gym)->create();

        $plan = Plan::factory()->for($this->gym)->create([
            'name' => 'VIP Plan',
            'price' => 2000,
        ]);

        $subscription = Subscription::create([
            'gym_id' => $this->gym->id,
            'member_id' => $member->id,
            'plan_id' => $plan->id,
            'amount' => 2000,
            'payment_status' => PaymentStatus::Unpaid,
            'starts_at' => today(),
            'ends_at' => today()->addDays(30),
        ]);

        $mockPayMongo = Mockery::mock(PayMongoService::class);
        $mockPayment = new Payment([
            'checkout_url' => 'https://checkout.paymongo.com/test_session_123',
            'status' => PaymentState::Pending,
        ]);

        $mockPayMongo->shouldReceive('createGcashCheckout')
            ->once()
            ->with(Mockery::type(Subscription::class))
            ->andReturn($mockPayment);

        $this->app->instance(PayMongoService::class, $mockPayMongo);

        $response = $this->post(route('portal.pay', ['qr_code' => $member->qr_code]));

        $response->assertRedirect('https://checkout.paymongo.com/test_session_123');
    }
}
