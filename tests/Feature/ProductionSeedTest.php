<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Database\Seeders\FitLedgerDemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionSeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_seeder_does_not_create_demo_data_in_production(): void
    {
        $this->app->instance('env', 'production');

        $this->artisan('db:seed', [
            '--class' => DatabaseSeeder::class,
            '--force' => true,
        ])->assertSuccessful();

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('gyms', 0);
    }

    public function test_demo_seeder_cannot_be_run_directly_in_production(): void
    {
        $this->app->instance('env', 'production');

        $this->expectException(\LogicException::class);

        $this->seed(FitLedgerDemoSeeder::class);
    }
}
