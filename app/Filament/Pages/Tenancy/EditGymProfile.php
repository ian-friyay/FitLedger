<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditGymProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Gym profile';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General Information')
                    ->schema([
                        FileUpload::make('logo_path')
                            ->label('Gym Brand Logo')
                            ->image()
                            ->directory('gym-logos')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->columnSpanFull()
                            ->helperText('Upload your gym logo (PNG, JPG, SVG). Displayed prominently on digital member passes.'),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(30),
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('address')
                            ->maxLength(255),
                        TextInput::make('city')
                            ->maxLength(255),
                        TextInput::make('province')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Owner Contact')
                    ->schema([
                        TextInput::make('owner_name')
                            ->label('Owner name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('owner_email')
                            ->label('Owner email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('owner_phone')
                            ->label('Owner phone')
                            ->tel()
                            ->maxLength(30),
                    ])
                    ->columns(2),

                Section::make('Operating Schedule')
                    ->description('Set your standard operating hours and weekly open days.')
                    ->schema([
                        TimePicker::make('settings.schedule.opening_time')
                            ->label('Opening Time')
                            ->default(config('fitledger.schedules.default_opening_time', '06:00'))
                            ->seconds(false)
                            ->required(),
                        TimePicker::make('settings.schedule.closing_time')
                            ->label('Closing Time')
                            ->default(config('fitledger.schedules.default_closing_time', '22:00'))
                            ->seconds(false)
                            ->required(),
                        CheckboxList::make('settings.schedule.operating_days')
                            ->label('Operating Days')
                            ->options([
                                'Monday' => 'Monday',
                                'Tuesday' => 'Tuesday',
                                'Wednesday' => 'Wednesday',
                                'Thursday' => 'Thursday',
                                'Friday' => 'Friday',
                                'Saturday' => 'Saturday',
                                'Sunday' => 'Sunday',
                            ])
                            ->default(config('fitledger.schedules.operating_days', []))
                            ->columns(4)
                            ->columnSpanFull()
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Front-Desk Scanner')
                    ->schema([
                        TextInput::make('scanner_key')
                            ->label('Front-desk scanner key')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull()
                            ->helperText('Send this key with the scanner request as X-Scanner-Key. Never share it publicly.'),
                    ]),
            ]);
    }
}
