<x-filament-panels::page>
    <div class="space-y-8">

        <!-- Pending Payment Alert Banner -->
        @if ($latestPending)
            <div class="rounded-2xl p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-lg animate-in fade-in duration-200"
                style="background-color: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 1.25rem; color: #fef3c7;">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-xl flex-shrink-0" style="background-color: rgba(245, 158, 11, 0.2); color: #fbbf24;">
                        <svg class="w-6 h-6 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-white flex items-center gap-2">
                            <span>Payment Submitted — Awaiting Admin Review</span>
                        </h4>
                        <p class="text-xs text-amber-200/80 mt-0.5">
                            Reference: <strong class="font-mono text-white">{{ $latestPending->reference_number }}</strong> &bull; Amount: <strong class="text-white">PHP {{ number_format($latestPending->amount, 2) }}</strong> (Submitted on {{ $latestPending->created_at->format('M j, Y h:i A') }}).
                            Your gym platform will unlock as soon as the Admin verifies the GCash receipt.
                        </p>
                    </div>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider flex-shrink-0"
                    style="background-color: #f59e0b; color: #0a0c10;">
                    Pending Verification
                </span>
            </div>
        @endif

        <!-- Status Overview Card -->
        <div class="relative overflow-hidden p-6 sm:p-8 shadow-2xl"
            style="background-color: #12151b; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 1.25rem;">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-orange-400">Platform Access Status</span>
                        @if ($hasActiveAccess)
                            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-semibold"
                                style="background-color: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3);">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                {{ $statusLabel }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-semibold"
                                style="background-color: rgba(244, 63, 94, 0.15); color: #fb7185; border: 1px solid rgba(244, 63, 94, 0.3);">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                {{ $statusLabel }}
                            </span>
                        @endif
                    </div>

                    <h2 class="font-athletic text-3xl sm:text-4xl font-black text-white uppercase tracking-tight mt-2">
                        {{ $gym?->name }}
                    </h2>

                    <p class="text-xs sm:text-sm text-zinc-400 mt-1 max-w-xl leading-relaxed">
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
                            Submit your GCash payment below so the Platform Admin can verify and restore full dashboard, scanner, and portal access.
                        @endif
                    </p>
                </div>

                <div class="flex-shrink-0" style="display: flex; flex-direction: column; align-items: center; justify-content: center; background-color: #0a0c10; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 1.25rem; padding: 1.25rem 2rem; min-width: 160px; text-align: center;">
                    <span style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8; display: block;">Days Left</span>
                    <span class="font-athletic" style="font-size: 3.5rem; font-weight: 900; line-height: 1; color: {{ $daysRemaining > 5 ? '#FF7A00' : '#f59e0b' }}; margin: 0.35rem 0; display: block;">
                        {{ $daysRemaining }}
                    </span>
                    <span style="font-size: 0.75rem; color: #64748b; display: block;">until renewal</span>
                </div>
            </div>
        </div>

        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto space-y-1.5 pt-2">
            <h3 class="font-athletic text-3xl font-black text-white uppercase tracking-wider">
                All-in-One Platform Pricing
            </h3>
            <p class="text-xs text-zinc-400">
                Pay one flat fee for your entire gym. Every plan includes 100% of all FitLedger features with zero hidden add-ons or per-member surcharges.
            </p>
        </div>

        <!-- Pricing Plans Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
            @foreach ($plans as $key => $plan)
                <div class="relative flex flex-col justify-between p-6 sm:p-8 transition-all hover:scale-[1.01]"
                    style="background-color: #12151b;
                           border: {{ $key === 'annual' ? '2px solid #FF5722' : '1px solid rgba(255, 255, 255, 0.08)' }};
                           border-radius: 1.25rem;
                           box-shadow: {{ $key === 'annual' ? '0 10px 30px -10px rgba(255, 87, 34, 0.35)' : '0 10px 25px -5px rgba(0, 0, 0, 0.4)' }};">
                    
                    <div>
                        <div class="flex items-center justify-between gap-3 mb-2">
                            <h4 class="font-athletic text-2xl font-bold text-white uppercase tracking-wider">{{ $plan['name'] }}</h4>
                            @if (!empty($plan['badge']))
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black tracking-widest uppercase text-white shadow-md flex-shrink-0"
                                    style="background: linear-gradient(135deg, #FF6D00 0%, #FF3D00 100%);">
                                    {{ $plan['badge'] }}
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-zinc-400 min-h-[32px]">{{ $plan['description'] }}</p>

                        <div class="mt-5 flex items-baseline gap-2">
                            <span class="font-athletic text-4xl sm:text-5xl font-black text-white">PHP {{ number_format($plan['price'], 2) }}</span>
                            <span class="text-xs text-zinc-400 font-medium">/ {{ $plan['days'] }} days</span>
                        </div>

                        <!-- Feature Checklist -->
                        <ul class="mt-6 space-y-2.5 text-xs text-zinc-300">
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span><strong>Unlimited Members & Pass Subscriptions</strong></span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span><strong>Turnstile & Front-Desk QR Scanner Engine</strong></span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span><strong>Gym Classes & Group Booking Management</strong></span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span><strong>Member Digital Pass & Self-Service Portal</strong></span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span><strong>Attendance Logs & Revenue Analytics Export</strong></span>
                            </li>
                        </ul>
                    </div>

                    <div class="mt-8">
                        <button
                            type="button"
                            wire:click="openPaymentModal('{{ $key }}')"
                            class="w-full py-3.5 px-4 rounded-xl font-bold text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-2 text-white shadow-lg active:scale-[0.98]"
                            style="background: {{ $key === 'annual' ? 'linear-gradient(135deg, #FF6D00 0%, #FF3D00 100%)' : '#27272a' }}; border: {{ $key === 'annual' ? 'none' : '1px solid rgba(255,255,255,0.1)' }};">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Submit GCash Payment (PHP {{ number_format($plan['price'], 2) }})</span>
                        </button>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Payment History Table -->
        <div class="rounded-2xl p-6 shadow-xl"
            style="background-color: #12151b; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 1.25rem;">
            <h4 class="font-athletic text-2xl font-bold text-white uppercase tracking-wider mb-4">FitLedger Payment History</h4>

            @if ($payments->isEmpty())
                <p class="text-xs text-zinc-500 italic py-4">No platform subscription payments recorded yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-zinc-800 text-zinc-400">
                                <th class="pb-3 font-semibold uppercase tracking-wider text-[11px]">Reference #</th>
                                <th class="pb-3 font-semibold uppercase tracking-wider text-[11px]">Plan</th>
                                <th class="pb-3 font-semibold uppercase tracking-wider text-[11px]">Amount</th>
                                <th class="pb-3 font-semibold uppercase tracking-wider text-[11px]">Status</th>
                                <th class="pb-3 font-semibold uppercase tracking-wider text-[11px]">Receipt</th>
                                <th class="pb-3 font-semibold uppercase tracking-wider text-[11px]">Date Submitted</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800 text-zinc-300">
                            @foreach ($payments as $p)
                                <tr>
                                    <td class="py-3 font-mono text-zinc-300 font-bold">{{ $p->reference_number }}</td>
                                    <td class="py-3 font-semibold capitalize">{{ $p->plan }}</td>
                                    <td class="py-3 font-bold text-white">PHP {{ number_format($p->amount, 2) }}</td>
                                    <td class="py-3">
                                        @if ($p->status->value === 'paid')
                                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase"
                                                style="background-color: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3);">
                                                Approved
                                            </span>
                                        @elseif ($p->status->value === 'pending')
                                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase"
                                                style="background-color: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3);">
                                                Pending Review
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase"
                                                style="background-color: rgba(244, 63, 94, 0.15); color: #fb7185; border: 1px solid rgba(244, 63, 94, 0.3);">
                                                Rejected
                                            </span>
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
                <div class="relative w-full max-w-lg p-6 sm:p-8 space-y-6 shadow-2xl"
                    style="background-color: #12151b; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 1.5rem;">
                    
                    <div class="flex items-center justify-between border-b border-zinc-800 pb-4">
                        <div>
                            <h3 class="font-athletic text-2xl font-black text-white uppercase tracking-wider">Manual GCash Payment</h3>
                            <p class="text-xs text-zinc-400 mt-0.5">
                                Subscribing to <strong class="text-orange-400 capitalize">{{ $selectedPlan }} Pass</strong> (PHP {{ number_format($plans[$selectedPlan]['price'] ?? 0, 2) }})
                            </p>
                        </div>
                        <button type="button" wire:click="closePaymentModal" class="p-1.5 rounded-xl text-zinc-400 hover:text-white hover:bg-zinc-800 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Platform GCash Account Info Card -->
                    <div class="rounded-xl p-4 text-xs space-y-2"
                        style="background-color: rgba(255, 87, 34, 0.08); border: 1px solid rgba(255, 87, 34, 0.25);">
                        <div class="font-athletic text-sm uppercase tracking-wider text-orange-400 flex items-center gap-1.5 font-bold">
                            <span class="w-2 h-2 rounded-full bg-orange-400"></span>
                            Admin GCash Payment Details
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-zinc-300 pt-1">
                            <div>
                                <span class="text-zinc-400 block text-[11px]">Account Name:</span>
                                <strong class="text-white text-sm">Adrian Viernes (FitLedger)</strong>
                            </div>
                            <div>
                                <span class="text-zinc-400 block text-[11px]">GCash Number:</span>
                                <strong class="text-white text-sm font-mono text-orange-400">0917-123-4567</strong>
                            </div>
                        </div>
                        <p class="text-[11px] text-zinc-400 pt-1">
                            1. Open GCash &bull; 2. Send exact amount to above number &bull; 3. Enter Reference # below.
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
                                class="w-full rounded-xl px-3.5 py-2.5 text-sm text-white placeholder-zinc-500 focus:outline-none transition font-mono"
                                style="background-color: #0a0c10; border: 1px solid rgba(255, 255, 255, 0.12);"
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
                                class="w-full rounded-xl px-3.5 py-2 text-xs text-white placeholder-zinc-500 focus:outline-none transition"
                                style="background-color: #0a0c10; border: 1px solid rgba(255, 255, 255, 0.12);"
                            ></textarea>
                            @error('senderNotes')
                                <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-zinc-800">
                            <button
                                type="button"
                                wire:click="closePaymentModal"
                                class="px-4 py-2 rounded-xl text-xs font-semibold text-zinc-400 hover:text-white hover:bg-zinc-800 transition"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="px-5 py-2.5 rounded-xl text-xs font-bold text-white shadow-lg transition"
                                style="background: linear-gradient(135deg, #FF6D00 0%, #FF3D00 100%);"
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
