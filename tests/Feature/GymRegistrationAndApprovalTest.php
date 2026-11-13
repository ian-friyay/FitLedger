<?php

namespace Tests\Feature;

use App\Filament\Pages\GymBilling;
use App\Models\Gym;
use App\Models\PlatformSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GymRegistrationAndApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_register_additional_gym_with_selected_plan(): void
    {
        $owner = User::factory()->create([
            'email' => 'owner@example.com',
            'is_platform_admin' => false,
            'is_active' => true,
        ]);

        $firstGym = Gym::factory()->create([
            'name' => 'First Gym QC',
            'owner_email' => $owner->email,
            'is_active' => true,
            'trial_ends_at' => today()->addDays(20),
        ]);

        $owner->update(['gym_id' => $firstGym->id]);

        $this->actingAs($owner);

        // Register a second gym
        $secondGym = Gym::create([
            'name' => 'Second Gym BGC',
            'owner_name' => $owner->name,
            'owner_email' => $owner->email,
            'subscription_plan' => 'monthly',
            'subscription_status' => 'pending_approval',
            'is_active' => false,
        ]);

        // Verify the user can access both tenants
        $tenants = $owner->getTenants(filament()->getCurrentPanel());
        $this->assertTrue($tenants->contains('id', $firstGym->id));
        $this->assertTrue($tenants->contains('id', $secondGym->id));

        $this->assertTrue($owner->canAccessTenant($firstGym));
        $this->assertTrue($owner->canAccessTenant($secondGym));

        // First gym has active access; second gym does not because it's pending approval
        $this->assertTrue($firstGym->hasActiveAccess());
        $this->assertFalse($secondGym->hasActiveAccess());
    }

    public function test_platform_admin_can_update_saas_pricing_settings(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@fitledger.test',
            'is_platform_admin' => true,
            'is_active' => true,
        ]);

        $this->actingAs($admin);

        Livewire::test(GymBilling::class)
            ->set('monthlyPrice', 1999.00)
            ->set('annualPrice', 18999.00)
            ->set('trialDays', 21)
            ->set('adminGcashNumber', '0918-999-8888')
            ->set('adminGcashName', 'FitLedger SuperAdmin')
            ->set('adminPaymentInstructions', 'Custom note for gym owners.')
            ->call('savePricingSettings')
            ->assertHasNoErrors();

        $this->assertEquals(1999.00, (float) PlatformSetting::get('saas_monthly_price'));
        $this->assertEquals(18999.00, (float) PlatformSetting::get('saas_annual_price'));
        $this->assertEquals(21, (int) PlatformSetting::get('saas_trial_days'));
        $this->assertEquals('0918-999-8888', PlatformSetting::get('admin_gcash_number'));
    }

    public function test_approving_a_pending_gym_activates_it_and_sets_dates(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@fitledger.test',
            'is_platform_admin' => true,
            'is_active' => true,
        ]);

        $pendingGym = Gym::factory()->create([
            'name' => 'Metro MMA Studio',
            'subscription_plan' => 'annual',
            'subscription_status' => 'pending_approval',
            'is_active' => false,
            'subscription_ends_at' => null,
        ]);

        $this->assertFalse($pendingGym->hasActiveAccess());

        // Admin approves the gym
        $pendingGym->is_active = true;
        $pendingGym->subscription_status = 'active';
        $pendingGym->subscription_ends_at = today()->addYear();
        $pendingGym->save();

        $pendingGym->refresh();
        $this->assertTrue($pendingGym->hasActiveAccess());
        $this->assertTrue($pendingGym->isSubscribed());
        $this->assertEquals('Active (Subscribed)', $pendingGym->accessStatusLabel());
    }
}
