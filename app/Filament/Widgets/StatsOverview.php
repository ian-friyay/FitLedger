<?php

namespace App\Filament\Widgets;

use App\Enums\MemberStatus;
use App\Enums\PaymentState;
use App\Enums\PaymentStatus;
use App\Models\CheckIn;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Subscription;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    public static function canView(): bool
    {
        return false;
    }

    protected static ?int $sort = 2;

    protected static ?string $pollingInterval = '60s';

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $tenant = Filament::getTenant();

        if (! $tenant) {
            return [];
        }

        $activeMembers = Member::query()
            ->where('gym_id', $tenant->getKey())
            ->where('status', MemberStatus::Active->value)
            ->count();

        $monthlyRevenue = Payment::query()
            ->where('gym_id', $tenant->getKey())
            ->where('status', PaymentState::Paid->value)
            ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');

        $openDuesQuery = Subscription::query()
            ->where('gym_id', $tenant->getKey())
            ->whereIn('payment_status', [
                PaymentStatus::Unpaid->value,
                PaymentStatus::Partial->value,
            ])
            ->whereDate('ends_at', '>=', today()->subDays((int) config('fitledger.dues.reminder_grace_days', 7)));

        $openDues = (clone $openDuesQuery)->count();
        $outstanding = (clone $openDuesQuery)
            ->get()
            ->sum(fn (Subscription $subscription) => $subscription->amountDue());

        $todayCheckIns = CheckIn::query()
            ->where('gym_id', $tenant->getKey())
            ->whereDate('scanned_at', today())
            ->count();

        return [
            Stat::make('Active members', number_format($activeMembers))
                ->description('Active member records')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart([3, 5, 7, 8, 6, 9, max(1, $activeMembers)])
                ->color('primary'),
            Stat::make('Revenue this month', 'PHP '.number_format((float) $monthlyRevenue, 2))
                ->description(now()->format('F Y'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([4, 6, 9, 7, 11, 14, 18])
                ->color('success'),
            Stat::make('Open dues', number_format($openDues))
                ->description('PHP '.number_format((float) $outstanding, 2).' outstanding')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->chart([8, 6, 5, 4, 3, 2, max(0, $openDues)])
                ->color($openDues > 0 ? 'warning' : 'gray'),
            Stat::make('Check-ins today', number_format($todayCheckIns))
                ->description(today()->format('M j, Y'))
                ->descriptionIcon('heroicon-m-qr-code')
                ->chart([2, 5, 8, 12, 15, 10, max(1, $todayCheckIns)])
                ->color('primary'),
        ];
    }
}
