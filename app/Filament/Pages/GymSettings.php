<?php

namespace App\Filament\Pages;

use App\Models\Gym;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class GymSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Gym Profile & Logo';

    protected static ?string $title = 'Gym Profile & Brand Settings';

    protected static ?int $navigationSort = 0;

    protected static string $view = 'filament.pages.gym-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $gym = Filament::getTenant();

        if ($gym) {
            $this->form->fill($gym->attributesToArray());
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Gym Brand & Logo')
                    ->description('Upload your gym logo to be featured across digital passes, kiosks, and sidebar navigation.')
                    ->schema([
                        FileUpload::make('logo_path')
                            ->label('Gym Brand Logo')
                            ->image()
                            ->imageEditor()
                            ->directory('gym-logos')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->columnSpanFull()
                            ->helperText('Upload your gym logo (PNG, JPG, SVG, WebP). Displayed prominently on member passes, check-in kiosks, and navigation.'),
                        TextInput::make('name')
                            ->label('Gym Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Contact Phone / Mobile')
                            ->tel()
                            ->maxLength(30),
                        TextInput::make('email')
                            ->label('Official Email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('address')
                            ->label('Street Address')
                            ->maxLength(255),
                        TextInput::make('city')
                            ->label('City')
                            ->maxLength(255),
                        TextInput::make('province')
                            ->label('Province / State')
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Owner Contact Details')
                    ->schema([
                        TextInput::make('owner_name')
                            ->label('Owner Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('owner_email')
                            ->label('Owner Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('owner_phone')
                            ->label('Owner Phone')
                            ->tel()
                            ->maxLength(30),
                    ])
                    ->columns(3),

                Section::make('Operating Hours & Schedule')
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
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Changes')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $gym = Filament::getTenant();

        if ($gym) {
            $gym->update($data);

            Notification::make()
                ->title('Gym profile & branding updated successfully')
                ->success()
                ->send();
        }
    }

    public static function canAccess(): bool
    {
        return ! (auth()->user()?->is_platform_admin ?? false) && Filament::getTenant() !== null;
    }
}
