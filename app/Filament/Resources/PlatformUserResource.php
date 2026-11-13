<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlatformUserResource\Pages;
use App\Models\Gym;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PlatformUserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static bool $isScopedToTenant = false;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Platform';

    protected static ?string $navigationLabel = 'User Accounts';

    protected static ?string $modelLabel = 'user account';

    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return auth()->user()?->is_platform_admin === true;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->is_platform_admin === true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Account Details')
                    ->schema([
                        Forms\Components\FileUpload::make('avatar_path')
                            ->label('Profile Avatar')
                            ->avatar()
                            ->image()
                            ->imageEditor()
                            ->directory('avatars')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->alignment('center')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->maxLength(255),
                        Forms\Components\Select::make('gym_id')
                            ->label('Associated Gym')
                            ->relationship('gym', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Forms\Components\Toggle::make('is_platform_admin')
                            ->label('Platform SuperAdmin privileges')
                            ->helperText('SuperAdmins have unrestricted access to all platform management tools.'),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Account is active')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar_path')
                    ->label('Avatar')
                    ->circular()
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&background=FF7A00&color=fff'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->state(fn (User $record): string => $record->getRoleName())
                    ->color(fn (string $state): string => match ($state) {
                        'Platform Admin' => 'primary',
                        'Gym Owner' => 'warning',
                        'Staff' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('gym.name')
                    ->label('Associated Gym')
                    ->placeholder('Platform Wide')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->date('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->label('Role')
                    ->options([
                        'admin' => 'Platform Admins',
                        'owner' => 'Gym Owners',
                        'staff' => 'Staff Accounts',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (blank($data['value'])) {
                            return $query;
                        }

                        return match ($data['value']) {
                            'admin' => $query->where('is_platform_admin', true),
                            'owner' => $query->where('is_platform_admin', false)
                                ->whereIn('email', Gym::pluck('owner_email')),
                            'staff' => $query->where('is_platform_admin', false)
                                ->whereNotNull('gym_id')
                                ->whereNotIn('email', Gym::pluck('owner_email')),
                            default => $query,
                        };
                    }),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->label('Delete Account')
                        ->modalHeading(fn (User $record) => "Delete User: {$record->name}")
                        ->modalDescription('Are you sure you want to permanently delete this user account? This cannot be undone.')
                        ->visible(fn (User $record): bool => $record->getKey() !== auth()->id()),
                ])
                ->tooltip('Actions')
                ->icon('heroicon-m-ellipsis-vertical'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function ($records) {
                            $records->filter(fn ($user) => $user->getKey() !== auth()->id())->each->delete();
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlatformUsers::route('/'),
            'create' => Pages\CreatePlatformUser::route('/create'),
            'edit' => Pages\EditPlatformUser::route('/{record}/edit'),
        ];
    }
}
