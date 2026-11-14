<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BootstrapProductionAccounts extends Command
{
    protected $signature = 'fitledger:bootstrap-production-accounts
                            {--from-environment : Read initial account details from deployment secrets}';

    protected $description = 'Create the first platform admin and a regular user on a fresh production database';

    public function handle(): int
    {
        if (! app()->environment('production')) {
            $this->error('This command may only be run in production.');

            return self::FAILURE;
        }

        if (User::query()->where('is_platform_admin', true)->exists()) {
            if ($this->option('from-environment')) {
                $this->info('Initial accounts already exist. Bootstrap skipped.');

                return self::SUCCESS;
            }

            $this->error('A platform admin already exists. Manage accounts from Platform > User Accounts.');

            return self::FAILURE;
        }

        if ($this->option('from-environment')) {
            $adminName = getenv('FITLEDGER_INITIAL_ADMIN_NAME') ?: 'FitLedger Admin';
            $adminEmail = strtolower(trim((string) getenv('FITLEDGER_INITIAL_ADMIN_EMAIL')));
            $adminPassword = (string) getenv('FITLEDGER_INITIAL_ADMIN_PASSWORD');
            $adminPasswordConfirmation = $adminPassword;
            $userName = getenv('FITLEDGER_INITIAL_USER_NAME') ?: 'Gym User';
            $userEmail = strtolower(trim((string) getenv('FITLEDGER_INITIAL_USER_EMAIL')));
            $userPassword = (string) getenv('FITLEDGER_INITIAL_USER_PASSWORD');
            $userPasswordConfirmation = $userPassword;

            if (blank($adminEmail) || blank($adminPassword) || blank($userEmail) || blank($userPassword)) {
                $this->error('Set both initial account emails and passwords as Render environment secrets.');

                return self::FAILURE;
            }
        } else {
            $adminName = $this->ask('Platform admin name', 'FitLedger Admin');
            $adminEmail = strtolower(trim($this->ask('Platform admin email', 'admin@fitledger.com')));
            $adminPassword = $this->secret('Platform admin password (minimum 12 characters)');
            $adminPasswordConfirmation = $this->secret('Confirm platform admin password');

            $userName = $this->ask('Regular user name', 'Gym User');
            $userEmail = strtolower(trim($this->ask('Regular user email', 'owner@fitledger.com')));
            $userPassword = $this->secret('Regular user password (minimum 12 characters)');
            $userPasswordConfirmation = $this->secret('Confirm regular user password');
        }

        if ($adminEmail === $userEmail) {
            $this->error('The platform admin and regular user must have different email addresses.');

            return self::FAILURE;
        }

        $validator = Validator::make([
            'admin_name' => $adminName,
            'admin_email' => $adminEmail,
            'admin_password' => $adminPassword,
            'admin_password_confirmation' => $adminPasswordConfirmation,
            'user_name' => $userName,
            'user_email' => $userEmail,
            'user_password' => $userPassword,
            'user_password_confirmation' => $userPasswordConfirmation,
        ], [
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'admin_password' => ['required', 'string', 'min:12', 'same:admin_password_confirmation'],
            'admin_password_confirmation' => ['required'],
            'user_name' => ['required', 'string', 'max:255'],
            'user_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'user_password' => ['required', 'string', 'min:12', 'same:user_password_confirmation'],
            'user_password_confirmation' => ['required'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        if (! $this->option('from-environment') && ! $this->confirm('Create the platform administrator and regular user now?', false)) {
            $this->warn('No accounts were created.');

            return self::FAILURE;
        }

        DB::transaction(function () use ($adminName, $adminEmail, $adminPassword, $userName, $userEmail, $userPassword): void {
            User::create([
                'name' => $adminName,
                'email' => $adminEmail,
                'password' => $adminPassword,
                'is_platform_admin' => true,
                'is_active' => true,
            ]);

            User::create([
                'name' => $userName,
                'email' => $userEmail,
                'password' => $userPassword,
                'is_platform_admin' => false,
                'is_active' => true,
            ]);
        });

        $this->info('Created the platform admin and regular user. No gym or demo data was created.');

        return self::SUCCESS;
    }
}
