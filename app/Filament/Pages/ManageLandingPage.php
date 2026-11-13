<?php

namespace App\Filament\Pages;

use App\Models\PlatformSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageLandingPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationGroup = 'Platform';

    protected static ?string $navigationLabel = 'Landing Page CMS';

    protected static ?string $title = 'Website Landing Page Editor';

    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.manage-landing-page';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->is_platform_admin === true;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->is_platform_admin === true;
    }

    public function mount(): void
    {
        $this->form->fill(PlatformSetting::getLandingSettings());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Hero Header & Announcement Banner')
                    ->description('Customize the main headline, description, and action buttons shown at the top of the landing page.')
                    ->schema([
                        Toggle::make('show_announcement')
                            ->label('Display Top Announcement Tag')
                            ->default(true),
                        TextInput::make('announcement_text')
                            ->label('Announcement Tag Copy')
                            ->columnSpanFull(),
                        TextInput::make('hero_title')
                            ->label('Hero Main Headline')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('hero_subtitle')
                            ->label('Hero Subtitle / Description')
                            ->rows(3)
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('primary_cta_text')
                            ->label('Primary CTA Button Text')
                            ->required(),
                        TextInput::make('primary_cta_url')
                            ->label('Primary CTA Destination URL')
                            ->required(),
                        TextInput::make('secondary_cta_text')
                            ->label('Secondary CTA Button Text')
                            ->required(),
                        TextInput::make('secondary_cta_url')
                            ->label('Secondary CTA Destination URL')
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Key Performance Metrics Banner')
                    ->description('Display up to 4 high-impact statistical proof points.')
                    ->schema([
                        TextInput::make('stat_1_value')->label('Metric 1 (Value)')->required(),
                        TextInput::make('stat_1_label')->label('Metric 1 (Label)')->required(),
                        TextInput::make('stat_2_value')->label('Metric 2 (Value)')->required(),
                        TextInput::make('stat_2_label')->label('Metric 2 (Label)')->required(),
                        TextInput::make('stat_3_value')->label('Metric 3 (Value)')->required(),
                        TextInput::make('stat_3_label')->label('Metric 3 (Label)')->required(),
                        TextInput::make('stat_4_value')->label('Metric 4 (Value)')->required(),
                        TextInput::make('stat_4_label')->label('Metric 4 (Label)')->required(),
                    ])
                    ->columns(2),

                Section::make('Features & Value Proposition')
                    ->schema([
                        TextInput::make('features_heading')
                            ->label('Section Heading')
                            ->required()
                            ->columnSpanFull(),
                        Textarea::make('features_subheading')
                            ->label('Section Subheading')
                            ->rows(2)
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Platform Pricing Display')
                    ->description('Controls the pricing section on the landing page (prices automatically pull from Platform Subscription Pricing settings).')
                    ->schema([
                        Toggle::make('show_pricing')
                            ->label('Show Pricing Section on Landing Page')
                            ->default(true),
                        TextInput::make('pricing_heading')
                            ->label('Pricing Heading')
                            ->columnSpanFull(),
                        Textarea::make('pricing_subheading')
                            ->label('Pricing Subheading')
                            ->rows(2)
                            ->columnSpanFull(),
                        TextInput::make('pricing_guarantee')
                            ->label('Guarantee & Trial Footnote')
                            ->columnSpanFull(),
                    ]),

                Section::make('FAQ & Footer Contact Details')
                    ->schema([
                        Toggle::make('show_faq')
                            ->label('Show FAQ Section on Landing Page')
                            ->default(true),
                        TextInput::make('contact_email')
                            ->label('Official Contact Email')
                            ->email(),
                        TextInput::make('contact_phone')
                            ->label('Contact Phone / Mobile'),
                        TextInput::make('contact_address')
                            ->label('Physical Office / City Address')
                            ->columnSpanFull(),
                        TextInput::make('copyright_text')
                            ->label('Copyright Footer Line')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Landing Page Changes')
                ->submit('save'),
            Action::make('preview')
                ->label('View Live Landing Page ↗')
                ->color('gray')
                ->url(url('/'), shouldOpenInNewTab: true),
        ];
    }

    public function save(): void
    {
        $state = $this->form->getState();

        PlatformSetting::setLandingSettings($state);

        Notification::make()
            ->title('Landing page settings updated successfully')
            ->success()
            ->send();
    }
}
