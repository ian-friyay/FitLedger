<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::query()->where('key', $key)->first();

        if ($setting === null || $setting->value === null) {
            return $default;
        }

        return $setting->value;
    }

    public static function set(string $key, mixed $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : (string) $value]
        );
    }

    /**
     * Get SaaS plans configured in platform settings or config.
     */
    public static function getPlans(): array
    {
        $configPlans = config('fitledger.saas.plans', []);

        $monthlyPrice = (float) static::get('saas_monthly_price', $configPlans['monthly']['price'] ?? 1499.00);
        $annualPrice = (float) static::get('saas_annual_price', $configPlans['annual']['price'] ?? 14999.00);

        return [
            'monthly' => [
                'name' => 'Monthly All-Access Pass',
                'description' => 'Complete, unrestricted access to all FitLedger features for 30 days.',
                'price' => $monthlyPrice,
                'days' => 30,
                'badge' => 'Most Flexible',
            ],
            'annual' => [
                'name' => 'Annual All-Access Pass',
                'description' => 'Complete, unrestricted access to all FitLedger features for 365 days (Best Value).',
                'price' => $annualPrice,
                'days' => 365,
                'badge' => 'Best Value (Annual)',
            ],
        ];
    }

    /**
     * Get platform GCash receiving details.
     */
    public static function getGcashDetails(): array
    {
        return [
            'number' => (string) static::get('admin_gcash_number', '0917-888-9999'),
            'name' => (string) static::get('admin_gcash_name', 'FitLedger Platform Admin'),
            'instructions' => (string) static::get(
                'admin_payment_instructions',
                'Send your subscription payment via GCash Express Send. Copy the 13-digit Reference Number and attach a screenshot receipt below.'
            ),
        ];
    }
}
