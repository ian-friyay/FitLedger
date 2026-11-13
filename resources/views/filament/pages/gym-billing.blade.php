<x-filament-panels::page>
    <div class="space-y-8">

        <!-- Pending Payment Alert Banner -->
        @if ($latestPending)
            <div class="rounded-2xl p-5 bg-amber-500/10 border border-amber-500/30 text-amber-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-lg animate-in fade-in duration-200">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-xl bg-amber-500/20 text-amber-400 flex-shrink-0">
                        <svg class="w-6 h-6 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-amber-100 text-sm">Payment Under Admin Verification</h4>
                        <p class="text-xs text-amber-300/80 mt-0.5">
                            Reference: <strong class="font-mono text-white">{{ $latestPending->reference_number }}</strong> &bull; Amount: <strong class="text-white">PHP {{ number_format($latestPending->amount, 2) }}</strong> (Submitted on {{ $latestPending->created_at->format('M j, Y h:i A') }}).
                            The Platform Admin is reviewing your transaction.
                        </p>
                    </div>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-500 text-zinc-950 flex-shrink-0">
                    Pending Review
                </span>
            </div>
        @endif

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
                            You are currently on a free trial valid until 
                            <strong class="text-white">{{ $expiryDate?->format('F j, Y') }}</strong>
                            ({{ $daysRemaining }} {{ Str::plural('day', $daysRemaining) }} remaining).
                        @else
                            <span class="text-rose-400 font-semibold">Your platform access is currently expired.</span>
                            Submit your GCash payment below to have the Platform Admin verify and unlock full system access.
                        @endif
                    </p>
                </div>

                <div class="flex sm:flex-col items-center sm:items-end justify-between border-t border-zinc-800 sm:border-0 pt-4 sm:pt-0">
                    <span class="text-xs text-zinc-500 font-medium">Days Left</span>
                    <span class="text-4xl sm:text-5xl font-black text-white tracking-tight">
                        {{ $daysRemaining }}
                    </span>
                    <span class="text-xs text-zinc-400">until renewal</span>
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
                                <span><strong>Classes & Group Booking Management</strong></span>
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
                                <span><strong>CSV Export for Attendance & Revenue Reports</strong></span>
                            </li>
                        </ul>
                    </div>

                    <div class="mt-8">
                        <button
                            type="button"
                            wire:click="openPaymentModal('{{ $key }}')"
                            class="w-full py-3.5 px-4 rounded-xl font-bold text-sm tracking-wide transition-all flex items-center justify-center gap-2
                                {{ $key === 'annual'
                                    ? 'bg-orange-600 hover:bg-orange-500 text-white shadow-lg shadow-orange-600/30'
                                    : 'bg-zinc-800 hover:bg-zinc-700 text-white' }}">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Submit GCash Payment (PHP {{ number_format($plan['price'], 2) }})
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
                                <th class="pb-3 font-semibold">Status</th>
                                <th class="pb-3 font-semibold">Receipt</th>
                                <th class="pb-3 font-semibold">Date Submitted</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800 text-zinc-300">
                            @foreach ($payments as $p)
                                <tr>
                                    <td class="py-3 font-mono text-zinc-400 font-bold">{{ $p->reference_number }}</td>
                                    <td class="py-3 font-semibold capitalize">{{ $p->plan }}</td>
                                    <td class="py-3 font-bold text-white">PHP {{ number_format($p->amount, 2) }}</td>
                                    <td class="py-3">
                                        @if ($p->status->value === 'paid')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-500/20 text-emerald-400">Approved</span>
                                        @elseif ($p->status->value === 'pending')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-500/20 text-amber-400">Pending Review</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-rose-500/20 text-rose-400">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        @if ($p->proof_image_path)
                                            <a href="{{ $p->getProofUrl() }}" target="_blank" class="text-orange-400 hover:underline font-semibold flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                View Receipt
                                            </a>
                                        @else
                                            <span class="text-zinc-600">—</span>
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

        <!-- Manual Payment Submission Modal -->
        @if ($showPaymentModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm animate-in fade-in duration-200">
                <div class="relative w-full max-w-lg rounded-2xl bg-zinc-900 border border-zinc-700 shadow-2xl p-6 sm:p-8 space-y-6">
                    
                    <div class="flex items-center justify-between border-b border-zinc-800 pb-4">
                        <div>
                            <h3 class="text-lg font-black text-white">Manual GCash Payment</h3>
                            <p class="text-xs text-zinc-400 mt-0.5">
                                Subscribing to <strong class="text-orange-400 capitalize">{{ $selectedPlan }} Pass</strong> (PHP {{ number_format($plans[$selectedPlan]['price'] ?? 0, 2) }})
                            </p>
                        </div>
                        <button type="button" wire:click="closePaymentModal" class="p-1 rounded-lg text-zinc-400 hover:text-white hover:bg-zinc-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Platform GCash Account Info Card -->
                    <div class="rounded-xl p-4 bg-blue-500/10 border border-blue-500/20 text-blue-200 space-y-2 text-xs">
                        <div class="font-bold uppercase tracking-wider text-blue-400 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                            Admin GCash Payment Details
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-zinc-300 pt-1">
                            <div>
                                <span class="text-zinc-400 block text-[11px]">Account Name:</span>
                                <strong class="text-white text-sm">Adrian Viernes (FitLedger)</strong>
                            </div>
                            <div>
                                <span class="text-zinc-400 block text-[11px]">GCash Number:</span>
                                <strong class="text-white text-sm font-mono">0917-123-4567</strong>
                            </div>
                        </div>
                        <p class="text-[11px] text-zinc-400 pt-1">
                            1. Send exact amount via GCash &bull; 2. Take a screenshot &bull; 3. Enter Reference # below.
                        </p>
                    </div>

                    <!-- Payment Form -->
                    <form wire:submit.prevent="submitManualPayment" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1">
                                GCash Reference Number <span class="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="referenceNumber"
                                placeholder="e.g. 1010 4920 1823"
                                class="w-full rounded-xl bg-zinc-950 border border-zinc-800 px-3.5 py-2.5 text-sm text-white placeholder-zinc-500 focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
                                required
                            />
                            @error('referenceNumber')
                                <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1">
                                Attach Receipt Screenshot (Optional)
                            </label>
                            <input
                                type="file"
                                wire:model="proofImage"
                                accept="image/*"
                                class="w-full text-xs text-zinc-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-zinc-800 file:text-white hover:file:bg-zinc-700 cursor-pointer"
                            />
                            <div wire:loading wire:target="proofImage" class="text-xs text-orange-400 mt-1">Uploading receipt...</div>
                            @error('proofImage')
                                <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-zinc-300 mb-1">
                                Notes (Optional)
                            </label>
                            <textarea
                                wire:model="senderNotes"
                                rows="2"
                                placeholder="e.g. Sent from 0917xxxxxxx via BPI to GCash"
                                class="w-full rounded-xl bg-zinc-950 border border-zinc-800 px-3.5 py-2 text-xs text-white placeholder-zinc-500 focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
                            ></textarea>
                            @error('senderNotes')
                                <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-zinc-800">
                            <button
                                type="button"
                                wire:click="closePaymentModal"
                                class="px-4 py-2 rounded-xl text-xs font-semibold text-zinc-400 hover:text-white hover:bg-zinc-800"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="px-5 py-2.5 rounded-xl text-xs font-bold bg-orange-600 hover:bg-orange-500 text-white shadow-lg shadow-orange-600/30 flex items-center gap-2"
                            >
                                <span wire:loading.remove wire:target="submitManualPayment">Submit for Verification</span>
                                <span wire:loading wire:target="submitManualPayment">Submitting...</span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        @endif

    </div>
</x-filament-panels::page>
