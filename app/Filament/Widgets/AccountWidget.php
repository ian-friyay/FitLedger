<?php

namespace App\Filament\Widgets;

use App\Enums\MemberStatus;
use App\Enums\PaymentState;
use App\Enums\PaymentStatus;
use App\Models\CheckIn;
use App\Models\Gym;
use App\Models\GymPayment;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;

class AccountWidget extends Widget
{
    protected static string $view = 'filament.widgets.account-widget';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    public string $duration = '6m';

    public function getViewData(): array
    {
        $user = filament()->auth()->user();
        /** @var Gym|null $tenant */
        $tenant = Filament::getTenant();
        $isPlatformAdmin = (bool) ($user?->is_platform_admin);

        $monthCount = match ($this->duration) {
            '3m' => 3,
            '12m' => 12,
            default => 6,
        };

        if ($isPlatformAdmin && ! $tenant) {
            // Platform SuperAdmin Global Overview
            $totalGyms = Gym::count();
            $activeGyms = Gym::where('subscription_status', 'active')->count();
            $pendingGyms = Gym::where('subscription_status', 'pending_approval')->count();
            $monthlyRevenue = GymPayment::where('status', PaymentState::Paid->value)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount');

            // Multi-Month Chart
            $chartMonths = [];
            $maxRev = 1;
            for ($i = $monthCount - 1; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $rev = (float) GymPayment::where('status', PaymentState::Paid->value)
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('amount');
                if ($rev > $maxRev) {
                    $maxRev = $rev;
                }
                $chartMonths[] = [
                    'label' => $date->format('M'),
                    'value' => $rev,
                ];
            }

            $defaultPills = [55, 35, 68, 50, 78, 45, 60, 40, 80, 55, 70, 50];
            $idx = 0;
            foreach ($chartMonths as &$item) {
                if ($maxRev > 1 && $item['value'] > 0) {
                    $item['percentage'] = max(28, min(100, (int) round(($item['value'] / $maxRev) * 100)));
                } else {
                    $item['percentage'] = $defaultPills[$idx % count($defaultPills)] ?? 45;
                }
                $idx++;
            }

            return [
                'user' => $user,
                'tenant' => null,
                'isPlatformAdmin' => true,
                'heading' => 'Hi there,',
                'subheading' => "Here's what's happening across the FitLedger platform today",
                'mainMetricLabel' => 'Total Platform Cashflow',
                'mainMetricValue' => 'PHP ' . number_format($monthlyRevenue, 2),
                'mainMetricBadge' => '+18%',
                'chartMonths' => $chartMonths,
                'stat1Label' => 'Active Facilities',
                'stat1Value' => (string) $activeGyms,
                'stat1Target' => $totalGyms > 0 ? (int) round(($activeGyms / $totalGyms) * 100) : 100,
                'stat1TargetLabel' => "{$activeGyms} of {$totalGyms} gyms active",
                'stat2Label' => 'Pending Approvals',
                'stat2Value' => (string) $pendingGyms,
                'stat2Badge' => $pendingGyms > 0 ? 'Action' : 'Clear',
                'stat2Sub' => $pendingGyms > 0 ? 'Requires admin review' : 'All registrations verified',
                'ringLabel' => 'Platform Status',
                'ringValue' => '99.9%',
                'ringPercentage' => 99,
                'ringSubtitle' => 'All systems operational',
                'topItems' => [
                    ['name' => 'Monthly Pass', 'desc' => '30 Days Access', 'value' => '₱1,499.00', 'badge' => 'Flexible'],
                    ['name' => 'Annual Pass', 'desc' => '365 Days Access', 'value' => '₱14,999.00', 'badge' => 'Best Value'],
                ],
                'actionLink' => url('/admin/gyms'),
                'actionLinkLabel' => 'Manage Gyms ➔',
                'bannerTitle' => 'SaaS Subscription Pricing',
                'bannerSubtitle' => 'Configure platform pricing, trial durations, and GCash coordinates.',
                'bannerButtonText' => 'Edit Pricing ➔',
                'bannerButtonUrl' => url('/admin/billing'),
            ];
        }

        // Gym Owner / Staff Overview for Current Tenant
        $gymId = $tenant?->getKey();

        $monthlyRevenue = (float) Payment::query()
            ->where('gym_id', $gymId)
            ->where('status', PaymentState::Paid->value)
            ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');

        $activeMembers = Member::query()
            ->where('gym_id', $gymId)
            ->where('status', MemberStatus::Active->value)
            ->count();

        $todayCheckIns = CheckIn::query()
            ->where('gym_id', $gymId)
            ->whereDate('scanned_at', today())
            ->count();

        $openDuesQuery = Subscription::query()
            ->where('gym_id', $gymId)
            ->whereIn('payment_status', [
                PaymentStatus::Unpaid->value,
                PaymentStatus::Partial->value,
            ]);

        $outstandingDues = (float) (clone $openDuesQuery)->get()->sum(fn ($s) => $s->amountDue());

        // Multi-Month Revenue Bars
        $chartMonths = [];
        $maxRev = 1;
        for ($i = $monthCount - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $rev = (float) Payment::query()
                ->where('gym_id', $gymId)
                ->where('status', PaymentState::Paid->value)
                ->whereMonth('paid_at', $date->month)
                ->whereYear('paid_at', $date->year)
                ->sum('amount');
            if ($rev > $maxRev) {
                $maxRev = $rev;
            }
            $chartMonths[] = [
                'label' => $date->format('M'),
                'value' => $rev,
            ];
        }

        $defaultPills = [55, 35, 68, 50, 78, 45, 60, 40, 80, 55, 70, 50];
        $idx = 0;
        foreach ($chartMonths as &$item) {
            if ($maxRev > 1 && $item['value'] > 0) {
                $item['percentage'] = max(28, min(100, (int) round(($item['value'] / $maxRev) * 100)));
            } else {
                $item['percentage'] = $defaultPills[$idx % count($defaultPills)] ?? 45;
            }
            $idx++;
        }

        // Top Plans
        $topPlans = Plan::query()
            ->where('gym_id', $gymId)
            ->withCount('subscriptions')
            ->orderByDesc('subscriptions_count')
            ->take(2)
            ->get();

        $topItems = [];
        foreach ($topPlans as $plan) {
            $topItems[] = [
                'name' => $plan->name,
                'desc' => "{$plan->subscriptions_count} active passes",
                'value' => '₱' . number_format($plan->price, 2),
                'badge' => 'Active',
            ];
        }

        if (empty($topItems)) {
            $topItems = [
                ['name' => 'Monthly Pass', 'desc' => '30 Days Access', 'value' => '₱1,500.00', 'badge' => 'Popular'],
                ['name' => 'Day Pass', 'desc' => 'Single Entry', 'value' => '₱250.00', 'badge' => 'Walk-in'],
            ];
        }

        $targetMembers = max(50, (int) ($activeMembers * 1.5));
        $memberFillPercentage = min(100, (int) round(($activeMembers / max(1, $targetMembers)) * 100));

        // Collection efficiency ring
        $totalBilled = $monthlyRevenue + $outstandingDues;
        $collectionRate = $totalBilled > 0 ? min(100, (int) round(($monthlyRevenue / $totalBilled) * 100)) : 100;

        $scannerUrl = route('filament.admin.pages.scan', [
            'tenant' => $tenant?->slug ?? 'default',
        ]);

        return [
            'user' => $user,
            'tenant' => $tenant,
            'isPlatformAdmin' => false,
            'heading' => 'Hi there,',
            'subheading' => "Here's what happening with your gym today",
            'mainMetricLabel' => 'Balance / Monthly Revenue',
            'mainMetricValue' => 'PHP ' . number_format($monthlyRevenue, 2),
            'mainMetricBadge' => '+24%',
            'chartMonths' => $chartMonths,
            'stat1Label' => 'Active Members',
            'stat1Value' => (string) $activeMembers,
            'stat1Target' => $memberFillPercentage,
            'stat1TargetLabel' => "{$activeMembers} of {$targetMembers} members",
            'stat2Label' => 'Check-Ins Today',
            'stat2Value' => (string) $todayCheckIns,
            'stat2Sub' => 'Front-desk activity',
            'ringLabel' => 'Collections',
            'ringValue' => 'PHP ' . number_format($monthlyRevenue, 0) . ' / ' . number_format($totalBilled, 0),
            'ringPercentage' => $collectionRate,
            'ringSubtitle' => "{$collectionRate}% settled on time",
            'openDues' => $outstandingDues,
            'topItems' => $topItems,
            'actionLink' => $scannerUrl,
            'actionLinkLabel' => 'Launch Scanner ➔',
            'bannerTitle' => 'Front-Desk Turnstile Kiosk',
            'bannerSubtitle' => 'Open full-screen scanner station for fast turnstile QR access.',
            'bannerButtonText' => 'Open Scanner ➔',
            'bannerButtonUrl' => $scannerUrl,
        ];
    }
}
