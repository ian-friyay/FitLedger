<?php

namespace App\Filament\Pages;

use App\Enums\PaymentState;
use App\Models\Gym;
use App\Models\GymPayment;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Livewire\WithFileUploads;

class GymBilling extends Page
{
    use WithFileUploads;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Platform Subscription';

    protected static ?string $title = 'FitLedger Platform Subscription';

    protected static ?string $slug = 'billing';

    protected static ?int $navigationSort = 50;

    protected static string $view = 'filament.pages.gym-billing';

    public ?string $selectedPlan = null;

    public ?string $referenceNumber = '';

    public ?string $senderNotes = '';

    public $proofImage = null;

    public bool $showPaymentModal = false;

    public function getTitle(): string|Htmlable
    {
        return 'FitLedger Platform Subscription';
    }

    public function openPaymentModal(string $planKey): void
    {
        $this->selectedPlan = $planKey;
        $this->referenceNumber = '';
        $this->senderNotes = '';
        $this->proofImage = null;
        $this->showPaymentModal = true;
    }

    public function closePaymentModal(): void
    {
        $this->showPaymentModal = false;
        $this->selectedPlan = null;
        $this->proofImage = null;
    }

    public function submitManualPayment(): void
    {
        $this->validate([
            'selectedPlan' => ['required', 'string', 'in:monthly,annual'],
            'referenceNumber' => ['required', 'string', 'min:6', 'max:100'],
            'senderNotes' => ['nullable', 'string', 'max:500'],
            'proofImage' => ['nullable', 'image', 'max:5120'], // Max 5MB
        ]);

        $gym = Filament::getTenant();
        $user = auth()->user();

        if (! $gym instanceof Gym || ! $user) {
            Notification::make()
                ->title('Error')
                ->body('Tenant session could not be resolved.')
                ->danger()
                ->send();

            return;
        }

        $plans = config('fitledger.saas.plans', []);
        $planDetails = $plans[$this->selectedPlan] ?? null;

        if (! $planDetails) {
            Notification::make()
                ->title('Invalid Plan')
                ->body('The selected subscription plan does not exist.')
                ->danger()
                ->send();

            return;
        }

        $proofPath = null;

        if ($this->proofImage) {
            $proofPath = $this->proofImage->store('payment-proofs', 'public');
        }

        GymPayment::create([
            'gym_id' => $gym->id,
            'user_id' => $user->id,
            'plan' => $this->selectedPlan,
            'provider' => 'manual',
            'amount' => (float) $planDetails['price'],
            'currency' => 'PHP',
            'status' => PaymentState::Pending,
            'payment_method' => 'gcash',
            'reference_number' => trim((string) $this->referenceNumber),
            'proof_image_path' => $proofPath,
            'notes' => $this->senderNotes,
        ]);

        $this->closePaymentModal();

        Notification::make()
            ->title('Payment Submitted!')
            ->body('Your transaction has been submitted for Admin verification. Once confirmed, your gym will be immediately activated.')
            ->success()
            ->persistent()
            ->send();
    }

    public function getViewData(): array
    {
        /** @var Gym|null $gym */
        $gym = Filament::getTenant();

        $plans = config('fitledger.saas.plans', []);
        $payments = $gym
            ? $gym->gymPayments()->latest()->take(10)->get()
            : collect();

        $latestPending = $gym
            ? $gym->gymPayments()->where('status', PaymentState::Pending->value)->latest()->first()
            : null;

        return [
            'gym' => $gym,
            'plans' => $plans,
            'payments' => $payments,
            'latestPending' => $latestPending,
            'hasActiveAccess' => $gym?->hasActiveAccess() ?? false,
            'isOnTrial' => $gym?->isOnTrial() ?? false,
            'isSubscribed' => $gym?->isSubscribed() ?? false,
            'daysRemaining' => $gym?->daysRemaining() ?? 0,
            'expiryDate' => $gym?->accessExpiryDate(),
            'statusLabel' => $gym?->accessStatusLabel() ?? 'Unknown',
        ];
    }
}
