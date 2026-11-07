<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Gym;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;

class RegisterGym extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register gym';
    }

    public function form(Form $form): Form
    {
        return $form
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
            ->columns(2);
    }

    protected function handleRegistration(array $data): Gym
    {
        $user = auth()->user();

        $gym = Gym::create([
            ...$data,
            'owner_name' => $user->name,
            'owner_email' => $user->email,
            'owner_phone' => $data['phone'] ?? null,
            'is_active' => true,
            'trial_ends_at' => today()->addDays(30),
        ]);

        $user->update(['gym_id' => $gym->id]);

        return $gym;
    }
}
