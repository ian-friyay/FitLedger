<?php

return [

    'currency' => env('FITLEDGER_CURRENCY', 'PHP'),

    'scanner' => [
        /*
         * Optional platform-wide key for the front-desk scanner. Every gym also
         * has its own scanner key; either key is accepted for that gym.
         */
        'key' => env('FITLEDGER_SCANNER_KEY'),
    ],

    'dues' => [
        /*
         * A subscription within this many days of expiry is flagged as "due"
         * (yellow) even if it has already been paid, so the front desk can ask
         * the member to renew.
         */
        'due_soon_days' => (int) env('FITLEDGER_DUE_SOON_DAYS', 3),

        /*
         * How many days after expiry the daily reminder command keeps
         * chasing an unpaid subscription.
         */
        'reminder_grace_days' => (int) env('FITLEDGER_REMINDER_GRACE_DAYS', 7),
    ],

    'schedules' => [
        /*
         * Default operating hours schedule for affiliated gyms.
         */
        'default_opening_time' => env('FITLEDGER_DEFAULT_OPENING', '06:00'),
        'default_closing_time' => env('FITLEDGER_DEFAULT_CLOSING', '22:00'),
        'operating_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
    ],

    'saas' => [
        /*
         * Default trial period in days for newly registered gyms.
         */
        'trial_days' => (int) env('FITLEDGER_TRIAL_DAYS', 14),

        /*
         * Available platform-wide SaaS subscription plans.
         * Every plan provides 100% full access to the entire platform.
         */
        'plans' => [
            'monthly' => [
                'name' => 'Monthly All-Access Pass',
                'description' => 'Complete, unrestricted access to all FitLedger features for 30 days.',
                'price' => (float) env('FITLEDGER_MONTHLY_PRICE', 1499.00),
                'days' => 30,
                'badge' => 'Most Flexible',
            ],
            'annual' => [
                'name' => 'Annual All-Access Pass',
                'description' => 'Complete, unrestricted access to all FitLedger features for 365 days (save ₱2,989/year).',
                'price' => (float) env('FITLEDGER_ANNUAL_PRICE', 14999.00),
                'days' => 365,
                'badge' => 'Best Value (Save 17%)',
            ],
        ],
    ],

];
