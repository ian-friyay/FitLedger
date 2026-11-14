<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BootstrapProductionAccountsTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_cannot_be_run_outside_production(): void
    {
        $this->artisan('fitledger:bootstrap-production-accounts')
            ->expectsOutputToContain('may only be run in production')
            ->assertExitCode(1);

        $this->assertDatabaseCount('users', 0);
    }

    public function test_command_creates_initial_admin_and_user_without_a_gym(): void
    {
        $this->app->instance('env', 'production');

        $this->artisan('fitledger:bootstrap-production-accounts')
            ->expectsQuestion('Platform admin name', 'FitLedger Admin')
            ->expectsQuestion('Platform admin email', 'admin@fitledger.com')
            ->expectsQuestion('Platform admin password (minimum 12 characters)', 'AdminPassword123!')
            ->expectsQuestion('Confirm platform admin password', 'AdminPassword123!')
            ->expectsQuestion('Regular user name', 'Gym User')
            ->expectsQuestion('Regular user email', 'owner@fitledger.com')
            ->expectsQuestion('Regular user password (minimum 12 characters)', 'OwnerPassword123!')
            ->expectsQuestion('Confirm regular user password', 'OwnerPassword123!')
            ->expectsConfirmation('Create the platform administrator and regular user now?', 'yes')
            ->expectsOutputToContain('No gym or demo data was created')
            ->assertSuccessful();

        $admin = User::where('email', 'admin@fitledger.com')->firstOrFail();
        $user = User::where('email', 'owner@fitledger.com')->firstOrFail();

        $this->assertTrue($admin->is_platform_admin);
        $this->assertFalse($user->is_platform_admin);
        $this->assertNull($admin->gym_id);
        $this->assertNull($user->gym_id);
        $this->assertTrue(Hash::check('AdminPassword123!', $admin->password));
        $this->assertTrue(Hash::check('OwnerPassword123!', $user->password));
    }

    public function test_environment_bootstrap_is_idempotent_for_render_restarts(): void
    {
        $this->app->instance('env', 'production');

        $environment = [
            'FITLEDGER_INITIAL_ADMIN_EMAIL' => 'admin@fitledger.com',
            'FITLEDGER_INITIAL_ADMIN_PASSWORD' => 'AdminPassword123!',
            'FITLEDGER_INITIAL_USER_EMAIL' => 'owner@fitledger.com',
            'FITLEDGER_INITIAL_USER_PASSWORD' => 'OwnerPassword123!',
        ];

        foreach ($environment as $key => $value) {
            putenv("{$key}={$value}");
        }

        try {
            $this->artisan('fitledger:bootstrap-production-accounts', ['--from-environment' => true])
                ->expectsOutputToContain('No gym or demo data was created')
                ->assertSuccessful();

            $this->artisan('fitledger:bootstrap-production-accounts', ['--from-environment' => true])
                ->expectsOutputToContain('Bootstrap skipped')
                ->assertSuccessful();

            $this->assertDatabaseCount('users', 2);
            $this->assertDatabaseCount('gyms', 0);
        } finally {
            foreach (array_keys($environment) as $key) {
                putenv($key);
            }
        }
    }
}
