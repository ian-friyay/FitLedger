<?php

namespace App\Models;

use App\Enums\PaymentState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GymPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'gym_id',
        'user_id',
        'plan',
        'provider',
        'provider_checkout_id',
        'provider_payment_id',
        'reference_number',
        'amount',
        'currency',
        'status',
        'payment_method',
        'proof_image_path',
        'notes',
        'admin_notes',
        'approved_at',
        'approved_by',
        'checkout_url',
        'paid_at',
        'period_starts_at',
        'period_ends_at',
        'payload',
    ];

    protected $attributes = [
        'provider' => 'manual',
        'status' => 'pending',
        'currency' => 'PHP',
        'payment_method' => 'gcash',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'status' => PaymentState::class,
            'paid_at' => 'datetime',
            'approved_at' => 'datetime',
            'period_starts_at' => 'date',
            'period_ends_at' => 'date',
            'payload' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (GymPayment $payment): void {
            $payment->reference_number ??= static::generateReferenceNumber();
        });
    }

    public static function generateReferenceNumber(): string
    {
        return 'SAAS-FL-'.now()->format('YmdHis').'-'.Str::upper(Str::random(6));
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getProofUrl(): ?string
    {
        if (blank($this->proof_image_path)) {
            return null;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->proof_image_path);
    }

    /**
     * Platform Admin approves this manual payment and activates the gym.
     */
    public function markApproved(User $admin, ?string $adminNotes = null): void
    {
        DB::transaction(function () use ($admin, $adminNotes): void {
            $this->status = PaymentState::Paid;
            $this->paid_at = now();
            $this->approved_at = now();
            $this->approved_by = $admin->id;

            if ($adminNotes !== null) {
                $this->admin_notes = $adminNotes;
            }

            $gym = $this->gym()->lockForUpdate()->first();

            if ($gym) {
                $plans = PlatformSetting::getPlans();
                $planDetails = $plans[$this->plan] ?? null;
                $days = (int) ($planDetails['days'] ?? 30);

                $currentEnd = $gym->subscription_ends_at && $gym->subscription_ends_at->isFuture()
                    ? $gym->subscription_ends_at->copy()
                    : today();

                $newStart = $currentEnd->copy();
                $newEnd = $currentEnd->copy()->addDays($days);

                $this->period_starts_at = $newStart;
                $this->period_ends_at = $newEnd;

                $gym->update([
                    'is_active' => true,
                    'subscription_plan' => $this->plan,
                    'subscription_status' => 'active',
                    'subscription_ends_at' => $newEnd,
                ]);
            }

            $this->save();
        });
    }

    /**
     * Platform Admin rejects this manual payment transaction.
     */
    public function markRejected(User $admin, ?string $reason = null): void
    {
        $this->status = PaymentState::Failed;
        $this->approved_at = now();
        $this->approved_by = $admin->id;
        $this->admin_notes = $reason ?? 'Rejected by platform admin.';
        $this->save();
    }

    /**
     * Mark this SaaS payment as paid and activate/extend the gym's subscription.
     */
    public function markPaid(
        ?float $amount = null,
        ?string $providerPaymentId = null,
        ?string $method = 'gcash',
        ?array $payload = null,
    ): void {
        if ($this->status === PaymentState::Paid) {
            return;
        }

        DB::transaction(function () use ($amount, $providerPaymentId, $method, $payload): void {
            $this->status = PaymentState::Paid;
            $this->paid_at = now();
            $this->payment_method = $method ?? $this->payment_method;
            $this->provider_payment_id = $providerPaymentId ?? $this->provider_payment_id;

            if ($amount !== null) {
                $this->amount = $amount;
            }

            if ($payload !== null) {
                $this->payload = $payload;
            }

            $gym = $this->gym()->lockForUpdate()->first();

            if ($gym) {
                $plans = PlatformSetting::getPlans();
                $planDetails = $plans[$this->plan] ?? null;
                $days = (int) ($planDetails['days'] ?? 30);

                // If gym already has a future subscription, extend from that end date; otherwise start from today
                $currentEnd = $gym->subscription_ends_at && $gym->subscription_ends_at->isFuture()
                    ? $gym->subscription_ends_at->copy()
                    : today();

                $newStart = $currentEnd->copy();
                $newEnd = $currentEnd->copy()->addDays($days);

                $this->period_starts_at = $newStart;
                $this->period_ends_at = $newEnd;

                $gym->update([
                    'is_active' => true,
                    'subscription_plan' => $this->plan,
                    'subscription_status' => 'active',
                    'subscription_ends_at' => $newEnd,
                ]);
            }

            $this->save();
        });
    }

    public function markFailed(string $status = 'failed', ?array $payload = null): void
    {
        $this->status = PaymentState::from($status);
        $this->payload = $payload ?? $this->payload;
        $this->save();
    }
}
