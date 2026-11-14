<?php

namespace Tests\Feature;

use App\Enums\PaymentStatus;
use App\Models\Gym;
use App\Models\Member;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilamentPanelTest extends TestCase
{
    use RefreshDatabase;

    private Gym $gym;

    private User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->gym = Gym::factory()->create();
        $this->owner = User::factory()->create([
            'gym_id' => $this->gym->id,
            'is_platform_admin' => false,
            'is_active' => true,
        ]);

        $plan = Plan::factory()->for($this->gym)->create();
        $member = Member::factory()->for($this->gym)->create();

        Subscription::create([
            'gym_id' => $this->gym->id,
            'member_id' => $member->id,
            'plan_id' => $plan->id,
            'starts_at' => today()->subDays(2),
            'ends_at' => today()->addDays(27),
            'amount' => $plan->price,
            'amount_paid' => $plan->price,
            'payment_status' => PaymentStatus::Paid,
        ]);
    }

    public function test_owner_can_open_every_tenant_resource(): void
    {
        $base = "/admin/{$this->gym->slug}";

        $this->actingAs($this->owner);

        $this->get($base)->assertSuccessful();
        $this->get("{$base}/members")->assertSuccessful();
        $this->get("{$base}/members/create")->assertSuccessful();
        $this->get("{$base}/subscriptions")->assertSuccessful();
        $this->get("{$base}/plans")->assertSuccessful();
        $this->get("{$base}/payments")->assertSuccessful();
        $this->get("{$base}/check-ins")->assertSuccessful();
        $this->get("{$base}/scan")->assertSuccessful();
        $this->get("{$base}/users")->assertSuccessful();
        $this->get("{$base}/profile")->assertSuccessful();
    }

    public function test_dashboard_actions_link_to_existing_tenant_pages(): void
    {
        $this->actingAs($this->owner);
        \Filament\Facades\Filament::setTenant($this->gym);

        \Livewire\Livewire::test(\App\Filament\Widgets\AccountWidget::class)
            ->assertSee(route('filament.admin.resources.check-ins.index', ['tenant' => $this->gym->slug]), false)
            ->assertSee(route('filament.admin.pages.scan', ['tenant' => $this->gym->slug]), false)
            ->assertSee(route('filament.admin.resources.members.create', ['tenant' => $this->gym->slug]), false)
            ->assertSee(route('filament.admin.resources.members.index', ['tenant' => $this->gym->slug]), false)
            ->assertSee('fl-collection-ring', false)
            ->assertSee('fl-banner-action', false)
            ->assertSee('fl-stat-meter', false)
            ->assertSee('stroke-width="1.4"', false)
            ->assertDontSee('+12%')
            ->assertDontSee('+7%')
            ->assertDontSee('20%');
    }

    public function test_owner_cannot_access_another_gym(): void
    {
        $otherGym = Gym::factory()->create();

        $this->actingAs($this->owner)
            ->get("/admin/{$otherGym->slug}/members")
            ->assertNotFound();
    }

    public function test_owner_cannot_manage_platform_gyms(): void
    {
        $this->actingAs($this->owner)
            ->get("/admin/{$this->gym->slug}/gyms")
            ->assertForbidden();
    }

    public function test_platform_admin_can_access_the_gym_management_resource(): void
    {
        $admin = User::factory()->create([
            'gym_id' => null,
            'is_platform_admin' => true,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get("/admin/{$this->gym->slug}/gyms")
            ->assertSuccessful();
    }

    public function test_guest_can_view_split_login_page(): void
    {
        $this->get('/admin/login')
            ->assertSuccessful()
            ->assertSee('FitLedger')
            ->assertSee('images/fitledger-logo.jpg')
            ->assertSee('shadow-orange-500/20')
            ->assertSee('Log in to your Account')
            ->assertSee('Welcome back! Enter your credentials.')
            ->assertDontSee('Quick Demo Role')
            ->assertDontSee('admin@fitledger.test')
            ->assertDontSee('Scanner Station');
    }

    public function test_guest_can_view_split_registration_page(): void
    {
        $this->get('/admin/register')
            ->assertSuccessful()
            ->assertSee('FitLedger')
            ->assertSee('images/fitledger-logo.jpg')
            ->assertSee('shadow-orange-500/20')
            ->assertSee('Create an Account')
            ->assertDontSee('Scanner Station');
    }
}
