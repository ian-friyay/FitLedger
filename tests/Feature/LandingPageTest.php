<?php

namespace Tests\Feature;

use App\Filament\Pages\ManageLandingPage;
use App\Models\Gym;
use App\Models\PlatformSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_renders_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('FIT');
        $response->assertSee('LEDGER');
        $response->assertSee('Start 14-Day Free Trial');
        $response->assertSee('Member Pass Portal');
    }

    public function test_platform_admin_can_access_and_update_landing_page_cms(): void
    {
        $admin = User::factory()->create([
            'is_platform_admin' => true,
            'is_active' => true,
        ]);

        $this->actingAs($admin);

        Livewire::test(ManageLandingPage::class)
            ->assertSuccessful()
            ->set('data.hero_title', 'Custom Champion Academy Operating System')
            ->set('data.stat_1_value', '< 1s')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Custom Champion Academy Operating System', PlatformSetting::getLandingSettings()['hero_title']);

        $response = $this->get('/');
        $response->assertSee('Custom Champion Academy Operating System');
        $response->assertSee('< 1s');
    }

    public function test_regular_gym_owner_cannot_access_landing_page_cms(): void
    {
        $gym = Gym::factory()->create();
        $owner = User::factory()->create([
            'gym_id' => $gym->id,
            'is_platform_admin' => false,
            'is_active' => true,
        ]);

        $this->actingAs($owner);

        $response = $this->get('/admin/manage-landing-page');
        $this->assertTrue(in_array($response->status(), [403, 404]));
    }
}
