<?php

namespace Tests\Feature;

use App\Enums\PaymentState;
use App\Models\Gym;
use App\Models\GymPayment;
use App\Models\Member;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\PayMongoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GymSaaSBillingTest extends TestCase
{
    use RefreshDatabase;

    private Gym $gym;

    private User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->gym = Gym::factory()->create([
            'trial_ends_at' => today()->addDays(14),
            'is_active' => true,
        ]);

        $this->owner = User::factory()->create([
            'gym_id' => $this->gym->id,
            'is_platform_admin' => false,
            'is_active' => true,
        ]);
    }

    public function test_gym_on_active_trial_can_access_dashboard_and_resources(): void
    {
        $this->actingAs($this->owner);

        $this->get("/admin/{$this->gym->slug}")->assertSuccessful();
        $this->get("/admin/{$this->gym->slug}/members")->assertSuccessful();
        $this->get("/admin/{$this->gym->slug}/billing")->assertSuccessful();
    }

    public function test_expired_gym_is_redirected_to_billing_page(): void
    {
        $this->gym->update([
            'trial_ends_at' => today()->subDays(1),
            'subscription_ends_at' => null,
        ]);

        $this->actingAs($this->owner);

        // Accessing dashboard or members should redirect to billing
        $response = $this->get("/admin/{$this->gym->slug}/members");
        $response->assertRedirect("/admin/{$this->gym->slug}/billing");
    }

    public function test_expired_gym_can_open_billing_page(): void
    {
        $this->gym->update([
            'trial_ends_at' => today()->subDays(5),
            'subscription_ends_at' => null,
        ]);

        $this->actingAs($this->owner);

        $this->get("/admin/{$this->gym->slug}/billing")->assertSuccessful();
    }

    public function test_platform_admin_can_access_expired_gym(): void
    {
        $this->gym->update([
            'trial_ends_at' => today()->subDays(5),
            'subscription_ends_at' => null,
        ]);

        $admin = User::factory()->create([
            'is_platform_admin' => true,
            'is_active' => true,
        ]);

        $this->actingAs($admin);

        $this->get("/admin/{$this->gym->slug}/members")->assertSuccessful();
    }

    public function test_checkin_scanner_rejects_when_gym_subscription_is_expired(): void
    {
        $this->gym->update([
            'trial_ends_at' => today()->subDays(2),
            'subscription_ends_at' => null,
        ]);

        $plan = Plan::factory()->for($this->gym)->create();
        $member = Member::factory()->for($this->gym)->create();
        Subscription::create([
            'gym_id' => $this->gym->id,
            'member_id' => $member->id,
            'plan_id' => $plan->id,
            'starts_at' => today()->subDays(2),
            'ends_at' => today()->addDays(20),
            'amount' => $plan->price,
            'amount_paid' => $plan->price,
            'payment_status' => 'paid',
        ]);

        $response = $this->withHeaders([
            'X-Gym-Slug' => $this->gym->slug,
            'X-Scanner-Key' => $this->gym->scanner_key,
        ])->postJson('/api/check-in', [
            'qr_code' => $member->qr_code,
        ]);

        $response->assertStatus(403)
            ->assertJsonPath('allows_entry', false)
            ->assertJsonPath('status', 'inactive');
    }

    public function test_it_creates_gcash_checkout_for_gym_saas_subscription(): void
    {
        config([
            'services.paymongo.secret_key' => 'sk_test_mock_123',
            'services.paymongo.base_url' => 'https://api.paymongo.com',
        ]);

        Http::fake([
            'api.paymongo.com/v1/checkout_sessions' => Http::response([
                'data' => [
                    'id' => 'cs_saas_test_123',
                    'type' => 'checkout_session',
                    'attributes' => [
                        'checkout_url' => 'https://checkout.paymongo.com/cs_saas_test_123',
                    ],
                ],
            ]),
        ]);

        $service = app(PayMongoService::class);
        $payment = $service->createGymSaaSCheckout($this->gym, 'monthly', $this->owner);

        $this->assertInstanceOf(GymPayment::class, $payment);
        $this->assertSame(PaymentState::Pending, $payment->status);
        $this->assertSame('cs_saas_test_123', $payment->provider_checkout_id);
        $this->assertSame('https://checkout.paymongo.com/cs_saas_test_123', $payment->checkout_url);
        $this->assertSame(1499.00, (float) $payment->amount);

        Http::assertSent(function (Request $request): bool {
            $attributes = $request->data()['data']['attributes'];

            return $request->url() === 'https://api.paymongo.com/v1/checkout_sessions'
                && $attributes['payment_method_types'] === ['gcash']
                && $attributes['line_items'][0]['amount'] === 149900;
        });
    }

    public function test_webhook_activates_gym_subscription_and_marks_payment_paid(): void
    {
        $this->gym->update([
            'trial_ends_at' => today()->subDays(5),
            'subscription_ends_at' => null,
            'is_active' => false,
        ]);

        $payment = GymPayment::create([
            'gym_id' => $this->gym->id,
            'user_id' => $this->owner->id,
            'plan' => 'monthly',
            'provider' => 'paymongo',
            'provider_checkout_id' => 'cs_saas_webhook_123',
            'amount' => 1499.00,
            'currency' => 'PHP',
            'status' => PaymentState::Pending,
        ]);

        $webhookPayload = [
            'data' => [
                'id' => 'evt_123',
                'type' => 'event',
                'attributes' => [
                    'type' => 'checkout_session.payment.paid',
                    'data' => [
                        'id' => 'cs_saas_webhook_123',
                        'type' => 'checkout_session',
                        'attributes' => [
                            'payments' => [
                                [
                                    'id' => 'pay_123',
                                    'attributes' => [
                                        'amount' => 149900,
                                        'status' => 'paid',
                                        'source' => ['type' => 'gcash'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->postJson('/api/webhooks/paymongo', $webhookPayload);
        $response->assertSuccessful();

        $payment->refresh();
        $this->assertSame(PaymentState::Paid, $payment->status);
        $this->assertNotNull($payment->paid_at);

        $this->gym->refresh();
        $this->assertTrue($this->gym->is_active);
        $this->assertTrue($this->gym->isSubscribed());
        $this->assertTrue($this->gym->hasActiveAccess());
        $this->assertNotNull($this->gym->subscription_ends_at);
        $this->assertSame('monthly', $this->gym->subscription_plan);
    }

    public function test_platform_admin_can_manually_approve_payment_and_activate_gym(): void
    {
        $this->gym->update([
            'trial_ends_at' => today()->subDays(10),
            'subscription_ends_at' => null,
            'is_active' => false,
        ]);

        $admin = User::factory()->create([
            'is_platform_admin' => true,
            'is_active' => true,
        ]);

        $payment = GymPayment::create([
            'gym_id' => $this->gym->id,
            'user_id' => $this->owner->id,
            'plan' => 'monthly',
            'provider' => 'manual',
            'amount' => 1499.00,
            'reference_number' => 'GCASH-MANUAL-TEST-001',
            'status' => PaymentState::Pending,
        ]);

        $this->assertSame(PaymentState::Pending, $payment->status);
        $this->assertFalse($this->gym->hasActiveAccess());

        $payment->markApproved($admin, 'Verified on GCash app');

        $payment->refresh();
        $this->assertSame(PaymentState::Paid, $payment->status);
        $this->assertSame($admin->id, $payment->approved_by);
        $this->assertSame('Verified on GCash app', $payment->admin_notes);

        $this->gym->refresh();
        $this->assertTrue($this->gym->is_active);
        $this->assertTrue($this->gym->isSubscribed());
        $this->assertTrue($this->gym->hasActiveAccess());
        $this->assertSame('monthly', $this->gym->subscription_plan);
        $this->assertEquals(today()->addDays(30), $this->gym->subscription_ends_at);
    }

    public function test_platform_admin_can_reject_manual_payment(): void
    {
        $admin = User::factory()->create([
            'is_platform_admin' => true,
            'is_active' => true,
        ]);

        $payment = GymPayment::create([
            'gym_id' => $this->gym->id,
            'user_id' => $this->owner->id,
            'plan' => 'monthly',
            'provider' => 'manual',
            'amount' => 1499.00,
            'reference_number' => 'INVALID-REF-999',
            'status' => PaymentState::Pending,
        ]);

        $payment->markRejected($admin, 'Reference number not found');

        $payment->refresh();
        $this->assertSame(PaymentState::Failed, $payment->status);
        $this->assertSame($admin->id, $payment->approved_by);
        $this->assertSame('Reference number not found', $payment->admin_notes);
    }

    public function test_active_gym_hides_offers_by_default_and_offers_toggle_on_demand(): void
    {
        $this->actingAs($this->owner);
        \Filament\Facades\Filament::setTenant($this->gym);

        \Livewire\Livewire::test(\App\Filament\Pages\GymBilling::class)
            ->assertSet('showPlanOffers', false)
            ->assertSee('Pay Again')
            ->assertSee('Change Subscription Plan')
            ->assertDontSee('Select Platform Subscription')
            ->call('togglePlanOffers')
            ->assertSet('showPlanOffers', true)
            ->assertSee('+30 DAYS')
            ->assertSee('+365 DAYS')
            ->call('selectOffer', 'annual')
            ->assertSet('selectedOffer', 'annual')
            ->call('quickRenew')
            ->assertSet('showPaymentModal', true);
    }
}
