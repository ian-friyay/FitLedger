<?php

namespace App\Filament\Resources;

use App\Enums\PaymentState;
use App\Filament\Resources\PlatformPaymentResource\Pages;
use App\Models\GymPayment;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class PlatformPaymentResource extends Resource
{
    protected static ?string $model = GymPayment::class;

    protected static bool $isScopedToTenant = false;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Platform';

    protected static ?string $navigationLabel = 'Platform Transactions';

    protected static ?string $modelLabel = 'Platform Transaction';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return auth()->user()?->is_platform_admin === true;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->is_platform_admin === true;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference_number')
                    ->label('Ref Number')
                    ->searchable()
                    ->fontFamily('mono')
                    ->weight('bold')
                    ->copyable(),
                Tables\Columns\TextColumn::make('gym.name')
                    ->label('Gym')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('plan')
                    ->label('Plan')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('PHP')
                    ->weight('black'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (PaymentState $state): string => match ($state) {
                        PaymentState::Paid => 'success',
                        PaymentState::Pending => 'warning',
                        PaymentState::Failed => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\ImageColumn::make('proof_image_path')
                    ->label('Receipt Proof')
                    ->disk('public')
                    ->square()
                    ->defaultImageUrl('/images/no-receipt.png'),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Gym Note')
                    ->limit(30)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('M j, Y h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('approvedByUser.name')
                    ->label('Reviewed By')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('view_proof')
                    ->label('View Proof')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->visible(fn (GymPayment $record): bool => filled($record->proof_image_path))
                    ->modalHeading('Proof of Payment')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalContent(fn (GymPayment $record) => new HtmlString(
                        '<div class="flex justify-center p-2"><img src="' . e($record->getProofUrl()) . '" alt="Receipt" class="max-h-[500px] w-auto rounded-lg shadow-lg border border-zinc-700" /></div>'
                    )),
                Tables\Actions\Action::make('approve')
                    ->label('Confirm & Activate')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (GymPayment $record): bool => $record->status === PaymentState::Pending)
                    ->requiresConfirmation()
                    ->modalHeading('Approve Subscription Payment')
                    ->modalDescription(fn (GymPayment $record): string => "Are you sure you want to approve payment of PHP " . number_format($record->amount, 2) . " for {$record->gym?->name}? This will immediately activate/extend their platform access.")
                    ->form([
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Note (Optional)')
                            ->placeholder('e.g. Verified GCash ref # from BPI/GCash app.'),
                    ])
                    ->action(function (GymPayment $record, array $data): void {
                        $record->markApproved(auth()->user(), $data['admin_notes'] ?? null);

                        Notification::make()
                            ->title('Payment Approved!')
                            ->body("{$record->gym?->name} has been activated successfully.")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (GymPayment $record): bool => $record->status === PaymentState::Pending)
                    ->requiresConfirmation()
                    ->modalHeading('Reject Payment')
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->label('Rejection Reason')
                            ->placeholder('e.g. Reference number does not match receipt, or invalid payment.')
                            ->required(),
                    ])
                    ->action(function (GymPayment $record, array $data): void {
                        $record->markRejected(auth()->user(), $data['reason'] ?? null);

                        Notification::make()
                            ->title('Payment Rejected')
                            ->body("The payment transaction was marked as rejected.")
                            ->warning()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlatformPayments::route('/'),
        ];
    }
}
