<?php

namespace App\Filament\Pages;

use App\Enums\PaymentState;
use App\Models\Gym;
use App\Models\GymPayment;
use App\Models\PlatformSetting;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Livewire\WithFileUploads;

class GymBilling extends Page
{
    use WithFileUploads;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $slug = 'billing';

    protected static ?int $navigationSort = 50;

    protected static string $view = 'filament.pages.gym-billing';

    // Gym Owner Payment Modal State
    public ?string $selectedPlan = null;
    public ?string $referenceNumber = '';
    public ?string $senderNotes = '';
    public $proofImage = null;
    public bool $showPaymentModal = false;

    // Platform Admin Pricing Form State
    public ?float $monthlyPrice = null;
    public ?float $annualPrice = null;
    public ?int $trialDays = null;
    public ?string $adminGcashNumber = null;
    public ?string $adminGcashName = null;
    public ?string $adminPaymentInstructions = null;

    public function mount(): void
    {
        $plans = PlatformSetting::getPlans();
        $gcash = PlatformSetting::getGcashDetails();

        $this->monthlyPrice = (float) $plans['monthly']['price'];
        $this->annualPrice = (float) $plans['annual']['price'];
        $this->trialDays = (int) PlatformSetting::get('saas_trial_days', config('fitledger.saas.trial_days', 14));
        $this->adminGcashNumber = $gcash['number'];
        $this->adminGcashName = $gcash['name'];
        $this->adminPaymentInstructions = $gcash['instructions'];
    }

    public static function getNavigationGroup(): ?string
    {
        return auth()->user()?->is_platform_admin ? 'Platform' : 'Settings';
    }

    public static function getNavigationLabel(): string
    {
        return auth()->user()?->is_platform_admin ? 'Subscription Pricing' : 'Platform Subscription';
    }

    public static function getNavigationBadge(): ?string
    {
        if (auth()->user()?->is_platform_admin) {
            return null;
        }

        $gym = Filament::getTenant();
        if (! $gym instanceof Gym) {
            return null;
        }

        if ($gym->subscription_status === 'pending_approval') {
            return 'Pending';
        }

        $days = $gym->daysRemaining();
        if ($days <= 0) {
            return 'Expired';
        }

        return "{$days}d left";
    }

    public static function getNavigationBadgeColor(): ?string
    {
        if (auth()->user()?->is_platform_admin) {
            return 'primary';
        }

        $gym = Filament::getTenant();
        if (! $gym instanceof Gym) {
            return null;
        }

        if ($gym->subscription_status === 'pending_approval') {
            return 'warning';
        }

        $days = $gym->daysRemaining();
        if ($days <= 0) {
            return 'danger';
        }

        if ($days <= 5) {
            return 'warning';
        }

        return 'success';
    }

    public function getTitle(): string|Htmlable
    {
        if (auth()->user()?->is_platform_admin) {
            return 'Platform Pricing & Subscription Management';
        }

        return 'FitLedger Platform Subscription';
    }

    public function savePricingSettings(): void
    {
        if (! auth()->user()?->is_platform_admin) {
            return;
        }

        $this->validate([
            'monthlyPrice' => ['required', 'numeric', 'min:0'],
            'annualPrice' => ['required', 'numeric', 'min:0'],
            'trialDays' => ['required', 'integer', 'min:1', 'max:365'],
            'adminGcashNumber' => ['required', 'string', 'max:50'],
            'adminGcashName' => ['required', 'string', 'max:100'],
            'adminPaymentInstructions' => ['nullable', 'string', 'max:1000'],
        ]);

        PlatformSetting::set('saas_monthly_price', $this->monthlyPrice);
        PlatformSetting::set('saas_annual_price', $this->annualPrice);
        PlatformSetting::set('saas_trial_days', $this->trialDays);
        PlatformSetting::set('admin_gcash_number', trim((string) $this->adminGcashNumber));
        PlatformSetting::set('admin_gcash_name', trim((string) $this->adminGcashName));
        PlatformSetting::set('admin_payment_instructions', trim((string) $this->adminPaymentInstructions));

        Notification::make()
            ->title('Pricing Updated Successfully')
            ->body('SaaS subscription pricing and payment details have been saved across the platform.')
            ->success()
            ->send();
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

        $plans = PlatformSetting::getPlans();
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
        $isPlatformAdmin = (bool) auth()->user()?->is_platform_admin;

        /** @var Gym|null $gym */
        $gym = Filament::getTenant();

        $plans = PlatformSetting::getPlans();
        $gcash = PlatformSetting::getGcashDetails();

        $payments = $gym
            ? $gym->gymPayments()->latest()->take(10)->get()
            : collect();

        $latestPending = $gym
            ? $gym->gymPayments()->where('status', PaymentState::Pending->value)->latest()->first()
            : null;

        $adminStats = [];
        if ($isPlatformAdmin) {
            $adminStats = [
                'totalGyms' => Gym::count(),
                'activeGyms' => Gym::where('is_active', true)->count(),
                'pendingGyms' => Gym::where('subscription_status', 'pending_approval')->count(),
                'pendingPayments' => GymPayment::where('status', PaymentState::Pending->value)->count(),
                'totalRevenue' => GymPayment::where('status', PaymentState::Paid->value)->sum('amount'),
            ];
        }

        return [
            'isPlatformAdmin' => $isPlatformAdmin,
            'gym' => $gym,
            'plans' => $plans,
            'gcash' => $gcash,
            'payments' => $payments,
            'latestPending' => $latestPending,
            'hasActiveAccess' => $gym?->hasActiveAccess() ?? false,
            'isOnTrial' => $gym?->isOnTrial() ?? false,
            'isSubscribed' => $gym?->isSubscribed() ?? false,
            'isPendingApproval' => $gym?->subscription_status === 'pending_approval',
            'daysRemaining' => $gym?->daysRemaining() ?? 0,
            'expiryDate' => $gym?->accessExpiryDate(),
            'statusLabel' => $gym?->accessStatusLabel() ?? 'Unknown',
            'adminStats' => $adminStats,
        ];
    }
}
