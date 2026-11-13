<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Gym;
use App\Models\PlatformSetting;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;

class RegisterGym extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register gym';
    }

    public static function canView(): bool
    {
        return auth()->check();
    }

    public function form(Form $form): Form
    {
        $plans = PlatformSetting::getPlans();
        $trialDays = (int) PlatformSetting::get('saas_trial_days', config('fitledger.saas.trial_days', 14));

        return $form
            ->schema([
                Section::make('Gym Location Details')
                    ->description('Provide the details for this gym facility.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Gym Name')
                            ->placeholder('e.g. Iron Peak Fitness QC')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Contact Number')
                            ->tel()
                            ->placeholder('0917xxxxxxx')
                            ->maxLength(30),
                        TextInput::make('email')
                            ->label('Official Email')
                            ->email()
                            ->placeholder('info@yourgym.com')
                            ->maxLength(255),
                        TextInput::make('address')
                            ->label('Street Address')
                            ->placeholder('123 Gym Street')
                            ->maxLength(255),
                        TextInput::make('city')
                            ->label('City')
                            ->placeholder('Quezon City')
                            ->maxLength(255),
                        TextInput::make('province')
                            ->label('Province / State')
                            ->placeholder('Metro Manila')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Platform Subscription Plan')
                    ->description('Select the SaaS subscription tier for this gym. Each gym branch requires its own subscription.')
                    ->schema([
                        Radio::make('subscription_plan')
                            ->label('Choose Plan')
                            ->options([
                                'trial' => "Free Trial ({$trialDays} Days) — Test drive full access",
                                'monthly' => 'Monthly All-Access (PHP ' . number_format($plans['monthly']['price'], 2) . ' / 30 days)',
                                'annual' => 'Annual All-Access (PHP ' . number_format($plans['annual']['price'], 2) . ' / year — Best Value)',
                            ])
                            ->descriptions([
                                'trial' => "100% full platform access for {$trialDays} days upon platform administrator confirmation.",
                                'monthly' => 'Unrestricted monthly access with zero setup fees or long-term lock-in.',
                                'annual' => 'Complete annual platform pass with best value pricing and priority platform support.',
                            ])
                            ->default('trial')
                            ->required(),
                    ]),
            ]);
    }

    protected function handleRegistration(array $data): Gym
    {
        $user = auth()->user();
        $plan = $data['subscription_plan'] ?? 'trial';

        $gym = Gym::create([
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'address' => $data['address'] ?? null,
            'city' => $data['city'] ?? null,
            'province' => $data['province'] ?? null,
            'owner_name' => $user->name,
            'owner_email' => $user->email,
            'owner_phone' => $data['phone'] ?? null,
            'subscription_plan' => $plan,
            'subscription_status' => 'pending_approval',
            'is_active' => false,
            'trial_ends_at' => null,
            'subscription_ends_at' => null,
        ]);

        if (is_null($user->gym_id)) {
            $user->update(['gym_id' => $gym->id]);
        }

        return $gym;
    }
}
