<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements FilamentUser, HasTenants, HasAvatar
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'gym_id',
        'name',
        'email',
        'avatar_path',
        'password',
        'is_platform_admin',
        'is_active',
    ];

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_path ? Storage::disk('public')->url($this->avatar_path) : null;
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_platform_admin' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return (bool) $this->is_active;
    }

    public function getTenants(Panel $panel): Collection
    {
        if ($this->is_platform_admin) {
            return Gym::query()->orderBy('name')->get();
        }

        return Gym::query()
            ->where('owner_email', $this->email)
            ->orWhere('id', $this->gym_id)
            ->orderBy('name')
            ->get();
    }

    public function canAccessTenant(Model $tenant): bool
    {
        if ($this->is_platform_admin) {
            return true;
        }

        if (! ($tenant instanceof Gym)) {
            return false;
        }

        return $tenant->owner_email === $this->email
            || (int) $this->gym_id === (int) $tenant->getKey();
    }

    public function isPlatformAdmin(): bool
    {
        return $this->is_platform_admin;
    }

    public function getRoleName(): string
    {
        if ($this->is_platform_admin) {
            return 'Platform Admin';
        }

        if (Gym::where('owner_email', $this->email)->exists()) {
            return 'Gym Owner';
        }

        if ($this->gym_id !== null) {
            return 'Staff';
        }

        return 'User';
    }
}
