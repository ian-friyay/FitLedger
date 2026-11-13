<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GymResource\Pages;
use App\Models\Gym;
use App\Models\PlatformSetting;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GymResource extends Resource
{
    protected static ?string $model = Gym::class;

    protected static bool $isScopedToTenant = false;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Platform';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return auth()->user()?->is_platform_admin === true;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->is_platform_admin === true;
    }

    public static function getNavigationBadge(): ?string
    {
        $pending = Gym::query()->where('subscription_status', 'pending_approval')->count();

        return $pending > 0 ? (string) $pending : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Gym')
                    ->schema([
                        Forms\Components\FileUpload::make('logo_path')
                            ->label('Gym Brand Logo')
                            ->image()
                            ->imageEditor()
                            ->directory('gym-logos')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Used in the portal URL. Leave blank to generate from the name.'),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(30),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('address')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('city')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('province')
                            ->maxLength(255),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Owner')
                    ->schema([
                        Forms\Components\TextInput::make('owner_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('owner_email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('owner_phone')
                            ->tel()
                            ->maxLength(30),
                        Forms\Components\TextInput::make('owner_password')
                            ->label('Owner password')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->visible(fn (string $operation): bool => $operation === 'create')
                            ->helperText('The owner signs in with the email specified above.'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Platform Subscription & Billing')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Gym account is active')
                            ->default(true),
                        Forms\Components\Select::make('subscription_status')
                            ->label('Subscription Status')
                            ->options([
                                'pending_approval' => 'Pending Approval',
                                'active' => 'Active',
                                'suspended' => 'Suspended',
                                'rejected' => 'Rejected',
                            ])
                            ->default('active')
                            ->required(),
                        Forms\Components\Select::make('subscription_plan')
                            ->label('Platform Plan')
                            ->options([
                                'trial' => 'Free Trial',
                                'monthly' => 'Monthly All-Access Pass',
                                'annual' => 'Annual All-Access Pass',
                            ])
                            ->default('trial'),
                        Forms\Components\DatePicker::make('trial_ends_at')
                            ->label('Trial ends at')
                            ->native(false),
                        Forms\Components\DatePicker::make('subscription_ends_at')
                            ->label('Paid subscription ends at')
                            ->native(false),
                        Forms\Components\TextInput::make('scanner_key')
                            ->label('Front-desk scanner key')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull()
                            ->helperText('The gym owner can retrieve this from the gym profile page.'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&background=12151b&color=FF7A00'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('owner_name')
                    ->label('Owner')
                    ->searchable(),
                Tables\Columns\TextColumn::make('owner_email')
                    ->label('Owner email')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('subscription_plan')
                    ->label('Plan')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'monthly' => 'Monthly',
                        'annual' => 'Annual',
                        'trial' => 'Trial',
                        default => $state ? ucfirst($state) : 'Trial',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'annual' => 'warning',
                        'monthly' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('access_status')
                    ->label('Access Status')
                    ->badge()
                    ->state(fn (Gym $record): string => $record->accessStatusLabel())
                    ->color(fn (string $state): string => match ($state) {
                        'Active (Subscribed)' => 'success',
                        'Active (Trial)' => 'info',
                        'Pending Admin Approval' => 'warning',
                        'Registration Rejected' => 'danger',
                        default => 'danger',
                    }),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('days_left')
                    ->label('Days Left')
                    ->state(fn (Gym $record): string => $record->hasActiveAccess() ? $record->daysRemaining().'d' : '0d')
                    ->badge()
                    ->color(fn (Gym $record): string => $record->daysRemaining() > 5 ? 'success' : ($record->daysRemaining() > 0 ? 'warning' : 'danger')),
                Tables\Columns\TextColumn::make('members_count')
                    ->label('Members')
                    ->counts('members')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registered')
                    ->date('M j, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('subscription_status')
                    ->options([
                        'pending_approval' => 'Pending Approval',
                        'active' => 'Active',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('approveGym')
                        ->label('Approve Gym')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading(fn (Gym $record) => "Approve Gym: {$record->name}")
                        ->modalDescription('Approving this gym will immediately activate its platform access according to its selected subscription tier.')
                        ->visible(fn (Gym $record): bool => $record->subscription_status === 'pending_approval' || ! $record->is_active)
                        ->action(function (Gym $record): void {
                            $plan = $record->subscription_plan ?: 'trial';

                            $record->is_active = true;
                            $record->subscription_status = 'active';

                            if ($plan === 'annual') {
                                $record->subscription_ends_at = today()->addYear();
                            } elseif ($plan === 'monthly') {
                                $record->subscription_ends_at = today()->addDays(30);
                            } else {
                                $trialDays = (int) PlatformSetting::get('saas_trial_days', config('fitledger.saas.trial_days', 14));
                                $record->trial_ends_at = today()->addDays($trialDays);
                            }

                            $record->save();

                            // Ensure owner user is active
                            User::where('email', $record->owner_email)->update(['is_active' => true]);

                            Notification::make()
                                ->success()
                                ->title('Gym Approved & Activated')
                                ->body("{$record->name} is now active on the platform.")
                                ->send();
                        }),

                    Tables\Actions\Action::make('denyGym')
                        ->label('Deny Gym')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading(fn (Gym $record) => "Deny Gym: {$record->name}")
                        ->modalDescription('Are you sure you want to deny this gym registration? The owner will not have access to operational features.')
                        ->visible(fn (Gym $record): bool => $record->subscription_status === 'pending_approval')
                        ->action(function (Gym $record): void {
                            $record->is_active = false;
                            $record->subscription_status = 'rejected';
                            $record->save();

                            Notification::make()
                                ->danger()
                                ->title('Gym Registration Denied')
                                ->body("{$record->name} has been rejected.")
                                ->send();
                        }),

                    Tables\Actions\Action::make('revokeSubscription')
                        ->label('Revoke Subscription')
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading(fn (Gym $record) => "Revoke Subscription: {$record->name}")
                        ->modalDescription('Are you sure you want to revoke the subscription of this gym? The gym will be deactivated immediately and locked out of all operational dashboard, scanner, and portal features.')
                        ->visible(fn (Gym $record): bool => $record->is_active || in_array($record->subscription_status, ['active', 'trialing']) || $record->hasActiveAccess())
                        ->action(function (Gym $record): void {
                            $record->is_active = false;
                            $record->subscription_status = 'suspended';
                            $record->trial_ends_at = null;
                            $record->subscription_ends_at = null;
                            $record->save();

                            Notification::make()
                                ->danger()
                                ->title('Subscription Revoked')
                                ->body("Platform subscription for {$record->name} has been revoked and deactivated.")
                                ->send();
                        }),

                    Tables\Actions\Action::make('deleteOwnerAccount')
                        ->label('Delete Owner Account')
                        ->icon('heroicon-o-user-minus')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading(fn (Gym $record) => "Delete Owner Account: {$record->owner_email}")
                        ->modalDescription(fn (Gym $record) => "Permanently delete the login user account for {$record->owner_name} ({$record->owner_email})? They will no longer be able to log in to FitLedger.")
                        ->visible(fn (Gym $record): bool => filled($record->owner_email) && User::where('email', $record->owner_email)->where('is_platform_admin', false)->exists())
                        ->action(function (Gym $record): void {
                            $deleted = User::where('email', $record->owner_email)
                                ->where('is_platform_admin', false)
                                ->delete();

                            if ($deleted) {
                                Notification::make()
                                    ->success()
                                    ->title('Owner Account Deleted')
                                    ->body("User account for {$record->owner_email} was permanently deleted.")
                                    ->send();
                            } else {
                                Notification::make()
                                    ->warning()
                                    ->title('Account Not Found')
                                    ->body("No non-admin user account found matching {$record->owner_email}.")
                                    ->send();
                            }
                        }),

                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->tooltip('Actions')
                ->icon('heroicon-m-ellipsis-vertical'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGyms::route('/'),
            'create' => Pages\CreateGym::route('/create'),
            'edit' => Pages\EditGym::route('/{record}/edit'),
        ];
    }
}
