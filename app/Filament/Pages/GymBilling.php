<?php

namespace App\Filament\Pages;

use App\Models\Gym;
use App\Models\GymPayment;
use App\Services\PayMongoService;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Log;

class GymBilling extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Platform Subscription';

    protected static ?string $title = 'FitLedger Platform Subscription';

    protected static ?string $slug = 'billing';

    protected static ?int $navigationSort = 50;

    protected static string $view = 'filament.pages.gym-billing';

    public ?string $paymentStatus = null;

    public ?string $paymentRef = null;

    public function mount(): void
    {
        $this->paymentStatus = request()->query('status');
        $this->paymentRef = request()->query('ref');

        if ($this->paymentRef && $this->paymentStatus === 'success') {
            $payment = GymPayment::query()
                ->where('reference_number', $this->paymentRef)
                ->first();

            if ($payment && $payment->status->value !== 'paid') {
                try {
                    if (filled($payment->provider_checkout_id)) {
                        $payMongo = app(PayMongoService::class);
                        $session = $payMongo->retrieveCheckoutSession($payment->provider_checkout_id);
                        $payments = data_get($session, 'attributes.payments', []);
                        $providerPayment = collect($payments)->firstWhere('status', 'paid') ?? ($payments[0] ?? null);

                        if ($providerPayment && data_get($providerPayment, 'attributes.status') === 'paid') {
                            $payment->markPaid(
                                amount: (float) $payment->amount,
                                providerPaymentId: data_get($providerPayment, 'id'),
                                method: data_get($providerPayment, 'attributes.source.type', 'gcash'),
                                payload: $session,
                            );
                        }
                    }
                } catch (\Throwable $e) {
                    Log::warning('Could not automatically sync PayMongo session on billing return', [
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            if ($payment && $payment->refresh()->status->value === 'paid') {
                Notification::make()
                    ->title('Subscription Activated!')
                    ->body('Thank you! Your gym now has full unrestricted access to FitLedger.')
                    ->success()
                    ->send();
            }
        } elseif ($this->paymentStatus === 'cancelled') {
            Notification::make()
                ->title('Payment Cancelled')
                ->body('The GCash checkout session was cancelled. You can retry whenever you are ready.')
                ->warning()
                ->send();
        }
    }

    public function getTitle(): string|Htmlable
    {
        return 'FitLedger Platform Subscription';
    }

    public function subscribe(string $planKey): void
    {
        $gym = Filament::getTenant();
        $user = auth()->user();

        if (! $gym instanceof Gym || ! $user) {
            Notification::make()
                ->title('Unable to process payment')
                ->body('Gym tenant or user session not found.')
                ->danger()
                ->send();

            return;
        }

        try {
            $payMongo = app(PayMongoService::class);
            $payment = $payMongo->createGymSaaSCheckout($gym, $planKey, $user);

            if ($payment->checkout_url) {
                $this->redirect($payment->checkout_url);
            } else {
                Notification::make()
                    ->title('Error launching checkout')
                    ->body('Could not generate GCash checkout URL.')
                    ->danger()
                    ->send();
            }
        } catch (\Throwable $exception) {
            Notification::make()
                ->title('Checkout error')
                ->body($exception->getMessage())
                ->danger()
                ->send();
        }
    }

    public function getViewData(): array
    {
        /** @var Gym|null $gym */
        $gym = Filament::getTenant();

        $plans = config('fitledger.saas.plans', []);
        $payments = $gym
            ? $gym->gymPayments()->latest()->take(10)->get()
            : collect();

        return [
            'gym' => $gym,
            'plans' => $plans,
            'payments' => $payments,
            'hasActiveAccess' => $gym?->hasActiveAccess() ?? false,
            'isOnTrial' => $gym?->isOnTrial() ?? false,
            'isSubscribed' => $gym?->isSubscribed() ?? false,
            'daysRemaining' => $gym?->daysRemaining() ?? 0,
            'expiryDate' => $gym?->accessExpiryDate(),
            'statusLabel' => $gym?->accessStatusLabel() ?? 'Unknown',
        ];
    }
}
