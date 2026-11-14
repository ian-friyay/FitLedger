Project Title: FitLedger: Gym Management System
Student Name: Adrian Viernes
Course and Section: BSIT III-F2
Project Description: A streamlined B2B SaaS administrative dashboard and client-facing digital pass portal designed for independent gyms in the Philippines. It replaces manual logbooks with a centralized platform featuring QR code check-ins, automated background checks for active subscriptions, and GCash payment integration.
Technologies Used: Laravel 12, Filament PHP v3, MySQL / SQLite, PayMongo API, Semaphore SMS, Tailwind CSS.

## Initial production accounts

For a fresh production database, run migrations first, then run `php artisan fitledger:bootstrap-production-accounts` from the production shell. The command is production-only, prompts for two distinct accounts and hidden passwords, and refuses to run if a platform admin already exists. For hosts without a shell, `--from-environment` reads the `FITLEDGER_INITIAL_ADMIN_*` and `FITLEDGER_INITIAL_USER_*` secrets once; remove the password secrets after bootstrap. It creates no gym or sample data. Configure a real mail transport before signing in because authentication codes are delivered by email.

The default database seeder creates demo data only outside production. Do not run `FitLedgerDemoSeeder` against a production database.

## Render preview deployment

Connect this repository to Render and create a Blueprint from `render.yaml`. The Blueprint provisions a web service and a fresh PostgreSQL database; startup runs migrations and creates `admin@fitledger.com` and `owner@fitledger.com` without demo accounts or gyms.

Before deploying, add these secret environment variables to the web service in Render:

- `RESEND_API_KEY`: an API key for a Resend account with `fitledger.com` verified.
- `FITLEDGER_INITIAL_ADMIN_PASSWORD` and `FITLEDGER_INITIAL_USER_PASSWORD`: separate strong passwords for the initial accounts.

The sender is `no-reply@fitledger.com`. Remove both initial-password variables after the first successful bootstrap. Do not commit or share these secrets.

This Free plan is for preview use only: its PostgreSQL database expires after 30 days, and uploaded files are ephemeral. Do not store production or irreplaceable data on this deployment.
