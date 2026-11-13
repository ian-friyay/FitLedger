<?php

namespace App\Models;

use Database\Factories\GymFactory;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasCurrentTenantLabel;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Gym extends Model implements HasName, HasCurrentTenantLabel, HasAvatar
{
    /** @use HasFactory<GymFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo_path',
        'address',
        'city',
        'province',
        'phone',
        'email',
        'owner_name',
        'owner_email',
        'owner_phone',
        'is_active',
        'trial_ends_at',
        'subscription_ends_at',
        'subscription_plan',
        'subscription_status',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'trial_ends_at' => 'date',
            'subscription_ends_at' => 'date',
            'settings' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Gym $gym): void {
            if (blank($gym->slug)) {
                $gym->slug = static::uniqueSlug($gym->name);
            }

            if (blank($gym->scanner_key)) {
                $gym->scanner_key = 'FLSCAN-'.Str::upper(Str::random(40));
            }
        });
    }

    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'gym';
        $slug = $base;
        $suffix = 2;

        while (static::query()
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    public function getFilamentName(): string
    {
        return $this->name;
    }

    public function getCurrentTenantLabel(): string
    {
        return 'Location • Switch ▾';
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->getLogoUrl();
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function checkIns(): HasMany
    {
        return $this->hasMany(CheckIn::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function classBookings(): HasMany
    {
        return $this->hasMany(ClassBooking::class);
    }

    public function gymClasses(): HasMany
    {
        return $this->hasMany(GymClass::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getOperatingSchedule(): array
    {
        return $this->settings['schedule'] ?? [
            'opening_time' => config('fitledger.schedules.default_opening_time', '06:00'),
            'closing_time' => config('fitledger.schedules.default_closing_time', '22:00'),
            'operating_days' => config('fitledger.schedules.operating_days', []),
        ];
    }

    /**
     * Get the public URL for the gym's custom logo.
     */
    public function getLogoUrl(): ?string
    {
        if (blank($this->logo_path)) {
            return null;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->logo_path);
    }

    public function isOpenNow(): bool
    {
        $schedule = $this->getOperatingSchedule();
        $currentDay = now()->format('l');

        if (! in_array($currentDay, $schedule['operating_days'] ?? [])) {
            return false;
        }

        $currentTime = now()->format('H:i');
        return $currentTime >= $schedule['opening_time'] && $currentTime <= $schedule['closing_time'];
    }

    public function gymPayments(): HasMany
    {
        return $this->hasMany(GymPayment::class);
    }

    /**
     * Check if the gym is currently running on an active free trial.
     */
    public function isOnTrial(): bool
    {
        return $this->trial_ends_at !== null && $this->trial_ends_at->isFuture();
    }

    /**
     * Check if the gym currently has an active paid SaaS platform subscription.
     */
    public function isSubscribed(): bool
    {
        return $this->subscription_ends_at !== null && $this->subscription_ends_at->isFuture();
    }

    /**
     * Check if the gym has active platform access (either trial or paid subscription).
     */
    public function hasActiveAccess(): bool
    {
        if (in_array($this->subscription_status, ['pending_approval', 'rejected', 'suspended'])) {
            return false;
        }

        return $this->is_active && ($this->isOnTrial() || $this->isSubscribed());
    }

    /**
     * Human-readable label for the gym's platform access status.
     */
    public function accessStatusLabel(): string
    {
        if ($this->subscription_status === 'pending_approval') {
            return 'Pending Admin Approval';
        }

        if ($this->subscription_status === 'rejected') {
            return 'Registration Rejected';
        }

        if ($this->subscription_status === 'suspended') {
            return 'Suspended / Revoked';
        }

        if (! $this->is_active) {
            return 'Suspended';
        }

        if ($this->isSubscribed()) {
            return 'Active (Subscribed)';
        }

        if ($this->isOnTrial()) {
            return 'Active (Trial)';
        }

        if ($this->trial_ends_at && $this->trial_ends_at->isPast()) {
            return 'Trial Expired';
        }

        return 'Subscription Expired';
    }

    /**
     * Get the expiration date of the current active period (trial or subscription).
     */
    public function accessExpiryDate(): ?\Illuminate\Support\Carbon
    {
        if ($this->isSubscribed()) {
            return $this->subscription_ends_at;
        }

        return $this->trial_ends_at;
    }

    /**
     * Days remaining until the gym's access expires.
     */
    public function daysRemaining(): int
    {
        $expiry = $this->accessExpiryDate();

        if (! $expiry) {
            return 0;
        }

        return max(0, (int) today()->diffInDays($expiry, false));
    }
}
