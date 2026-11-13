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
        'checkout_url',
        'paid_at',
        'period_starts_at',
        'period_ends_at',
        'payload',
    ];

    protected $attributes = [
        'provider' => 'paymongo',
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
                $plans = config('fitledger.saas.plans', []);
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
