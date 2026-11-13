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
        try {
            $setting = static::query()->where('key', $key)->first();

            if ($setting === null || $setting->value === null) {
                return $default;
            }

            return $setting->value;
        } catch (\Throwable $e) {
            return $default;
        }
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

    /**
     * Default landing page configuration.
     */
    public static function defaultLandingSettings(): array
    {
        return [
            'show_announcement' => true,
            'announcement_text' => 'Next-Gen Gym Management OS • Native GCash Dues & 2s QR Turnstile Check-In',
            'hero_title' => 'The High-Performance Operating System for Gyms & Combat Academies',
            'hero_subtitle' => 'Eliminate paper logbooks and bloated legacy software. FitLedger unites instant turnstile QR passes, automated GCash dues tracking, and real-time class check-ins into one fast, athletic platform.',
            'primary_cta_text' => 'Start 14-Day Free Trial',
            'primary_cta_url' => '/admin/register',
            'secondary_cta_text' => 'Member Pass Portal',
            'secondary_cta_url' => '/portal',
            'stat_1_value' => '< 2s',
            'stat_1_label' => 'Check-in Scan Speed',
            'stat_2_value' => '100%',
            'stat_2_label' => 'Browser-Based Pass (No App Store)',
            'stat_3_value' => '₱0',
            'stat_3_label' => 'Payment Merchant Markup',
            'stat_4_value' => '99.9%',
            'stat_4_label' => 'Front Desk Kiosk Uptime',
            'features_heading' => 'Engineered for Combat Sports & High-Throughput Facilities',
            'features_subheading' => 'Every tool your gym needs to operate at peak efficiency—without confusing clutter or per-member gouging.',
            'pricing_heading' => 'Simple, Transparent Platform Pricing',
            'pricing_subheading' => 'One flat fee for your facility. 100% feature access, unlimited members, and zero hidden add-ons.',
            'pricing_guarantee' => '14 days free trial upon registration. No credit card required. Cancel anytime.',
            'contact_email' => 'support@fitledger.test',
            'contact_phone' => '+63 917 888 9999',
            'contact_address' => 'BGC, Taguig City, Metro Manila, Philippines',
            'copyright_text' => '© '.date('Y').' FitLedger. All rights reserved. High-Performance Gym Management OS.',
            'show_pricing' => true,
            'show_faq' => true,
        ];
    }

    /**
     * Get landing page settings merged with defaults.
     */
    public static function getLandingSettings(): array
    {
        $raw = static::get('landing_page_settings');

        if (blank($raw)) {
            return static::defaultLandingSettings();
        }

        $decoded = is_array($raw) ? $raw : json_decode((string) $raw, true);

        return array_merge(static::defaultLandingSettings(), is_array($decoded) ? $decoded : []);
    }

    /**
     * Update landing page settings.
     */
    public static function setLandingSettings(array $settings): void
    {
        static::set('landing_page_settings', $settings);
    }
}
