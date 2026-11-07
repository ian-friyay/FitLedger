<?php

namespace App\Filament\Pages;

use App\Enums\CheckInStatus;
use App\Enums\MemberStatus;
use App\Enums\PaymentStatus;
use App\Models\CheckIn;
use App\Models\Member;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ScanQrCode extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-camera';

    protected static ?string $navigationGroup = 'Front desk';

    protected static ?string $navigationLabel = 'QR Scanner';

    protected static ?string $title = 'Front-Desk QR Scanner';

    protected static ?string $slug = 'scan';

    protected static ?int $navigationSort = 0;

    protected static string $view = 'filament.pages.scan-qr-code';

    public ?string $manualCode = '';

    public ?array $lastResult = null;

    public function processScan(?string $input = null): void
    {
        $code = trim((string) ($input ?: $this->manualCode));

        if (blank($code)) {
            return;
        }

        // If a full pass URL was scanned, extract the member identifier
        if (str_contains($code, '/pass/')) {
            $path = parse_url($code, PHP_URL_PATH) ?? $code;
            $qrCode = basename(rtrim($path, '/'));
        } else {
            $qrCode = $code;
        }

        $gym = Filament::getTenant();

        $member = Member::query()
            ->with(['latestSubscription.plan'])
            ->where('gym_id', $gym?->getKey())
            ->where('qr_code', $qrCode)
            ->first();

        if (! $member) {
            $this->lastResult = [
                'success' => false,
                'status' => 'not_found',
                'color' => 'danger',
                'color_hex' => '#dc2626',
                'title' => 'Member Not Found',
                'message' => 'No member matching "' . htmlspecialchars($qrCode) . '" was found in this gym.',
                'member' => null,
                'timestamp' => now()->format('h:i:s A'),
            ];

            Notification::make()
                ->title('Member not found')
                ->danger()
                ->send();

            $this->manualCode = '';
            return;
        }

        if ($member->status !== MemberStatus::Active) {
            $status = CheckInStatus::Inactive;
            $subscription = null;
            $message = 'Member account is ' . strtolower($member->status->getLabel()) . '. Please see desk.';
        } else {
            $subscription = $member->subscriptions()
                ->whereDate('starts_at', '<=', today())
                ->whereDate('ends_at', '>=', today())
                ->where('payment_status', '!=', PaymentStatus::Refunded->value)
                ->latest('ends_at')
                ->first();

            if (! $subscription) {
                $status = CheckInStatus::Expired;
                $latest = $member->latestSubscription;
                $message = $latest
                    ? "Subscription expired on {$latest->ends_at->format('M j, Y')}."
                    : 'No subscription on file. Please enroll or renew.';
            } else {
                $status = $subscription->accessStatus();
                $message = match ($status) {
                    CheckInStatus::Active => "Welcome, {$member->name}! Valid until {$subscription->ends_at->format('M j, Y')}.",
                    CheckInStatus::Due => $subscription->payment_status !== PaymentStatus::Paid
                        ? 'Payment due: PHP ' . number_format($subscription->amountDue(), 2) . ' outstanding.'
                        : "Expiring soon: renew before {$subscription->ends_at->format('M j, Y')}.",
                    default => 'Subscription expired.',
                };
            }
        }

        $checkIn = CheckIn::create([
            'gym_id' => $gym->id,
            'member_id' => $member->id,
            'subscription_id' => $subscription?->id,
            'status' => $status,
            'message' => $message,
            'ip_address' => request()->ip(),
            'scanned_at' => now(),
        ]);

        $this->lastResult = [
            'success' => $status->allowsEntry(),
            'status' => $status->value,
            'color' => match ($status) {
                CheckInStatus::Active => 'success',
                CheckInStatus::Due => 'warning',
                default => 'danger',
            },
            'color_hex' => $status->colorHex(),
            'title' => match ($status) {
                CheckInStatus::Active => 'Entry Approved',
                CheckInStatus::Due => 'Payment Due / Expiring Soon',
                default => 'Entry Denied',
            },
            'message' => $message,
            'member' => [
                'id' => $member->id,
                'name' => $member->name,
                'phone' => $member->phone,
                'qr_code' => $member->qr_code,
                'pass_url' => $member->passUrl(),
            ],
            'subscription' => $subscription ? [
                'plan' => $subscription->plan?->name,
                'ends_at' => $subscription->ends_at->format('M j, Y'),
                'amount_due' => $subscription->amountDue(),
            ] : null,
            'timestamp' => $checkIn->scanned_at->format('h:i:s A'),
        ];

        Notification::make()
            ->title($this->lastResult['title'])
            ->body($message)
            ->status($this->lastResult['color'])
            ->send();

        $this->manualCode = '';
    }

    public function clearResult(): void
    {
        $this->lastResult = null;
        $this->manualCode = '';
    }

    public function getRecentCheckInsProperty()
    {
        $gym = Filament::getTenant();

        if (! $gym) {
            return collect();
        }

        return CheckIn::query()
            ->where('gym_id', $gym->id)
            ->with(['member', 'subscription.plan'])
            ->latest('scanned_at')
            ->limit(8)
            ->get();
    }
}
