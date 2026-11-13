<x-filament-panels::page>
    <div class="space-y-8">

        <!-- Status Overview Card -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-zinc-900 to-zinc-950 p-6 sm:p-8 border border-zinc-800 shadow-2xl">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-orange-500">Platform Access Status</span>
                        @if ($hasActiveAccess)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                {{ $statusLabel }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                {{ $statusLabel }}
                            </span>
                        @endif
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-black text-white mt-2">
                        {{ $gym?->name }}
                    </h2>

                    <p class="text-sm text-zinc-400 mt-1 max-w-xl">
                        @if ($isSubscribed)
                            Your gym has an active all-access platform subscription valid until 
                            <strong class="text-white">{{ $expiryDate?->format('F j, Y') }}</strong>
                            ({{ $daysRemaining }} {{ Str::plural('day', $daysRemaining) }} remaining).
                        @elseif ($isOnTrial)
                            You are currently enjoying a free trial valid until 
                            <strong class="text-white">{{ $expiryDate?->format('F j, Y') }}</strong>
                            ({{ $daysRemaining }} {{ Str::plural('day', $daysRemaining) }} remaining).
                        @else
                            <span class="text-rose-400 font-semibold">Your platform access is currently expired.</span>
                            Please subscribe to one of our all-access plans below to restore full dashboard, scanner, and portal access.
                        @endif
                    </p>
                </div>

                <div class="flex sm:flex-col items-center sm:items-end justify-between border-t border-zinc-800 sm:border-0 pt-4 sm:pt-0">
                    <span class="text-xs text-zinc-500 font-medium">Days Left</span>
                    <span class="text-4xl sm:text-5xl font-black text-white tracking-tight">
                        {{ $daysRemaining }}
                    </span>
                    <span class="text-xs text-zinc-400">until next renewal</span>
                </div>
            </div>
        </div>

        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto space-y-2">
            <h3 class="text-2xl font-black text-white tracking-tight">
                Simple, Transparent All-in-One Pricing
            </h3>
            <p class="text-sm text-zinc-400">
                Pay one flat fee for your entire gym. Every plan includes 100% of all FitLedger features with zero hidden add-ons or per-member surcharges.
            </p>
        </div>

        <!-- Pricing Plans Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
            @foreach ($plans as $key => $plan)
                <div class="relative flex flex-col justify-between rounded-2xl p-6 sm:p-8 bg-zinc-900 border {{ $key === 'annual' ? 'border-orange-500 shadow-orange-500/10 shadow-2xl' : 'border-zinc-800' }} transition-all hover:border-zinc-700">
                    
                    @if (!empty($plan['badge']))
                        <div class="absolute -top-3 right-6">
                            <span class="px-3 py-1 rounded-full text-xs font-black tracking-wide uppercase bg-orange-600 text-white shadow-md">
                                {{ $plan['badge'] }}
                            </span>
                        </div>
                    @endif

                    <div>
                        <h4 class="text-xl font-bold text-white">{{ $plan['name'] }}</h4>
                        <p class="text-xs text-zinc-400 mt-1 min-h-[32px]">{{ $plan['description'] }}</p>

                        <div class="mt-6 flex items-baseline gap-2">
                            <span class="text-4xl font-black text-white">PHP {{ number_format($plan['price'], 2) }}</span>
                            <span class="text-xs text-zinc-500 font-medium">/ {{ $plan['days'] }} days</span>
                        </div>

                        <!-- Feature Checklist -->
                        <ul class="mt-6 space-y-3 text-xs text-zinc-300">
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span><strong>Unlimited Members & Subscriptions</strong></span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span><strong>Turnstile & Front-Desk QR Scanner API</strong></span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span><strong>Client Self-Service Digital Pass Portal</strong></span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span><strong>Integrated GCash E-Wallet Automation</strong></span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span><strong>Semaphore SMS Expiry & Due Notifications</strong></span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span><strong>CSV Export for Attendance & Revenue Reports</strong></span>
                            </li>
                        </ul>
                    </div>

                    <div class="mt-8">
                        <button
                            type="button"
                            wire:click="subscribe('{{ $key }}')"
                            wire:loading.attr="disabled"
                            class="w-full py-3.5 px-4 rounded-xl font-bold text-sm tracking-wide transition-all flex items-center justify-center gap-2
                                {{ $key === 'annual'
                                    ? 'bg-orange-600 hover:bg-orange-500 text-white shadow-lg shadow-orange-600/30'
                                    : 'bg-zinc-800 hover:bg-zinc-700 text-white' }} disabled:opacity-50">
                            <span wire:loading.remove wire:target="subscribe('{{ $key }}')">
                                Pay with GCash (PHP {{ number_format($plan['price'], 2) }})
                            </span>
                            <span wire:loading wire:target="subscribe('{{ $key }}')" class="inline-flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Preparing GCash Checkout...
                            </span>
                        </button>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Payment History Table -->
        <div class="rounded-2xl bg-zinc-900 border border-zinc-800 p-6">
            <h4 class="text-lg font-bold text-white mb-4">FitLedger Payment History</h4>

            @if ($payments->isEmpty())
                <p class="text-xs text-zinc-500 italic py-4">No platform subscription payments recorded yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-zinc-800 text-zinc-400">
                                <th class="pb-3 font-semibold">Reference</th>
                                <th class="pb-3 font-semibold">Plan</th>
                                <th class="pb-3 font-semibold">Amount</th>
                                <th class="pb-3 font-semibold">Method</th>
                                <th class="pb-3 font-semibold">Status</th>
                                <th class="pb-3 font-semibold">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800 text-zinc-300">
                            @foreach ($payments as $p)
                                <tr>
                                    <td class="py-3 font-mono text-zinc-400">{{ $p->reference_number }}</td>
                                    <td class="py-3 font-semibold capitalize">{{ $p->plan }}</td>
                                    <td class="py-3 font-bold text-white">PHP {{ number_format($p->amount, 2) }}</td>
                                    <td class="py-3 uppercase">{{ $p->payment_method }}</td>
                                    <td class="py-3">
                                        @if ($p->status->value === 'paid')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-500/20 text-emerald-400">Paid</span>
                                        @elseif ($p->status->value === 'pending')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-500/20 text-amber-400">Pending</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-rose-500/20 text-rose-400">{{ $p->status->value }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-zinc-400">{{ $p->created_at->format('M j, Y h:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</x-filament-panels::page>
