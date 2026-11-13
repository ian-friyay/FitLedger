<x-filament-panels::page>
    <div class="space-y-6 max-w-7xl mx-auto">

        @if ($isPlatformAdmin)
            <!-- ========================================== -->
            <!-- PLATFORM ADMIN PRICING & CONFIGURATION VIEW -->
            <!-- ========================================== -->

            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-white/5">
                <div>
                    <h2 class="font-athletic text-3xl sm:text-4xl font-black text-white uppercase tracking-tight">
                        Platform Pricing & SaaS Setup
                    </h2>
                    <p class="text-xs sm:text-sm text-zinc-400 mt-1">
                        Configure all-access subscription rates, trial durations, and destination GCash account details.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ url('/admin/gyms') }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold text-zinc-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 transition-colors flex items-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Review Gyms ({{ $adminStats['pendingGyms'] ?? 0 }})
                    </a>
                    <a href="{{ url('/admin/platform-payments') }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold text-zinc-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 transition-colors flex items-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Verify Receipts ({{ $adminStats['pendingPayments'] ?? 0 }})
                    </a>
                </div>
            </div>

            <!-- Compact Executive KPI Ribbon (Streamlined, uncrowded) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 rounded-2xl bg-[#12151b] border border-white/10 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400">Total Facilities</span>
                        <div class="font-athletic text-2xl sm:text-3xl font-black text-white mt-0.5">{{ $adminStats['totalGyms'] ?? 0 }}</div>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/20 font-mono">
                        {{ $adminStats['activeGyms'] ?? 0 }} Active
                    </span>
                </div>

                <div class="p-4 rounded-2xl bg-[#12151b] border border-white/10 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400">Pending Approvals</span>
                        <div class="font-athletic text-2xl sm:text-3xl font-black {{ ($adminStats['pendingGyms'] ?? 0) > 0 ? 'text-amber-400' : 'text-zinc-400' }} mt-0.5">
                            {{ $adminStats['pendingGyms'] ?? 0 }}
                        </div>
                    </div>
                    @if (($adminStats['pendingGyms'] ?? 0) > 0)
                        <a href="{{ url('/admin/gyms') }}" class="text-[11px] text-orange-400 hover:underline font-bold font-mono">Action Needed &rarr;</a>
                    @else
                        <span class="text-[10px] text-zinc-500 font-mono">All Clear</span>
                    @endif
                </div>

                <div class="p-4 rounded-2xl bg-[#12151b] border border-white/10 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400">Pending GCash</span>
                        <div class="font-athletic text-2xl sm:text-3xl font-black {{ ($adminStats['pendingPayments'] ?? 0) > 0 ? 'text-amber-400' : 'text-zinc-400' }} mt-0.5">
                            {{ $adminStats['pendingPayments'] ?? 0 }}
                        </div>
                    </div>
                    @if (($adminStats['pendingPayments'] ?? 0) > 0)
                        <a href="{{ url('/admin/platform-payments') }}" class="text-[11px] text-orange-400 hover:underline font-bold font-mono">Verify &rarr;</a>
                    @else
                        <span class="text-[10px] text-zinc-500 font-mono">Verified</span>
                    @endif
                </div>

                <div class="p-4 rounded-2xl bg-[#12151b] border border-white/10 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400">SaaS Revenue</span>
                        <div class="font-athletic text-xl sm:text-2xl font-black text-emerald-400 mt-0.5">
                            PHP {{ number_format($adminStats['totalRevenue'] ?? 0, 2) }}
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white/5 text-zinc-400 font-mono">Verified</span>
                </div>
            </div>

            <!-- Professional Tab Navigation -->
            <div class="flex items-center gap-2 border-b border-white/10 pb-3">
                <button type="button" wire:click="$set('adminActiveTab', 'rates')"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $adminActiveTab === 'rates' ? 'bg-[#FF7A00] text-black shadow-md shadow-orange-500/20' : 'bg-white/5 text-zinc-300 hover:text-white hover:bg-white/10' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>1. SaaS Subscription Rates</span>
                </button>

                <button type="button" wire:click="$set('adminActiveTab', 'gcash')"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $adminActiveTab === 'gcash' ? 'bg-[#FF7A00] text-black shadow-md shadow-orange-500/20' : 'bg-white/5 text-zinc-300 hover:text-white hover:bg-white/10' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>2. GCash Payment Setup</span>
                </button>

                <button type="button" wire:click="$set('adminActiveTab', 'preview')"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $adminActiveTab === 'preview' ? 'bg-[#FF7A00] text-black shadow-md shadow-orange-500/20' : 'bg-white/5 text-zinc-300 hover:text-white hover:bg-white/10' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>3. Live Plan Preview</span>
                </button>
            </div>

            <!-- Form Content -->
            <form wire:submit="savePricingSettings" class="space-y-6">

                <!-- TAB 1: SAAS RATES & TRIAL -->
                @if ($adminActiveTab === 'rates')
                    <div class="p-6 rounded-2xl bg-[#12151b] border border-white/10 space-y-6 animate-in fade-in duration-150">
                        <div class="border-b border-white/5 pb-3">
                            <h3 class="font-athletic text-xl font-bold text-white uppercase tracking-wider">
                                Subscription Fees & Free Trial
                            </h3>
                            <p class="text-xs text-zinc-400 mt-0.5">
                                Set the flat monthly and annual rates charged to gym owners across the platform.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Monthly Price -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300">
                                    Monthly Pass (PHP) <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs font-bold text-zinc-500">₱</span>
                                    <input type="number" step="0.01" wire:model="monthlyPrice"
                                        class="w-full pl-8 pr-4 py-2.5 rounded-xl text-sm font-bold text-white bg-[#0a0c10] border border-white/10 focus:border-orange-500 focus:ring-0 transition"
                                        placeholder="1499.00" required>
                                </div>
                                <span class="text-[11px] text-zinc-500">Billed every 30 days per facility.</span>
                                @error('monthlyPrice') <span class="text-xs text-rose-400 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Annual Price -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300">
                                    Annual Pass (PHP) <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs font-bold text-zinc-500">₱</span>
                                    <input type="number" step="0.01" wire:model="annualPrice"
                                        class="w-full pl-8 pr-4 py-2.5 rounded-xl text-sm font-bold text-white bg-[#0a0c10] border border-white/10 focus:border-orange-500 focus:ring-0 transition"
                                        placeholder="14999.00" required>
                                </div>
                                <span class="text-[11px] text-zinc-500">Billed every 365 days (~17% discount).</span>
                                @error('annualPrice') <span class="text-xs text-rose-400 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Trial Days -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300">
                                    Free Trial Duration (Days) <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" min="1" max="365" wire:model="trialDays"
                                    class="w-full px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-[#0a0c10] border border-white/10 focus:border-orange-500 focus:ring-0 transition"
                                    placeholder="14" required>
                                <span class="text-[11px] text-zinc-500">Free days granted when gym registers.</span>
                                @error('trialDays') <span class="text-xs text-rose-400 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="pt-4 border-t border-white/5 flex items-center justify-between">
                            <span class="text-xs text-zinc-400">Pricing changes apply to all subsequent gym renewals.</span>
                            <button type="submit"
                                class="px-6 py-2.5 rounded-xl font-athletic text-sm font-black uppercase tracking-wider text-white bg-gradient-to-r from-[#FF6D00] to-[#FF3D00] hover:from-[#FF7D1A] hover:to-[#FF501A] shadow-md transition transform hover:scale-[1.02]">
                                Save Pricing Settings
                            </button>
                        </div>
                    </div>
                @endif

                <!-- TAB 2: GCASH COORDINATES -->
                @if ($adminActiveTab === 'gcash')
                    <div class="p-6 rounded-2xl bg-[#12151b] border border-white/10 space-y-6 animate-in fade-in duration-150">
                        <div class="border-b border-white/5 pb-3">
                            <h3 class="font-athletic text-xl font-bold text-white uppercase tracking-wider">
                                Platform GCash Payment Coordinates
                            </h3>
                            <p class="text-xs text-zinc-400 mt-0.5">
                                The receiving account coordinates shown to gym owners during platform subscription checkout.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300">
                                    Platform GCash Number <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" wire:model="adminGcashNumber"
                                    class="w-full px-4 py-2.5 rounded-xl text-sm font-mono text-white bg-[#0a0c10] border border-white/10 focus:border-orange-500 focus:ring-0 transition"
                                    placeholder="0917-888-9999" required>
                                <span class="text-[11px] text-zinc-500">The mobile number gym owners send payment to.</span>
                                @error('adminGcashNumber') <span class="text-xs text-rose-400 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300">
                                    GCash Registered Account Name <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" wire:model="adminGcashName"
                                    class="w-full px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-[#0a0c10] border border-white/10 focus:border-orange-500 focus:ring-0 transition"
                                    placeholder="FitLedger Admin" required>
                                <span class="text-[11px] text-zinc-500">Verified account name displayed on the payment modal.</span>
                                @error('adminGcashName') <span class="text-xs text-rose-400 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300">
                                Custom Payment Instructions / Notice for Gym Owners
                            </label>
                            <textarea wire:model="adminPaymentInstructions" rows="3"
                                class="w-full px-4 py-2.5 rounded-xl text-xs text-zinc-200 bg-[#0a0c10] border border-white/10 focus:border-orange-500 focus:ring-0 transition leading-relaxed"
                                placeholder="Send payment via GCash Express Send. Copy reference number and upload receipt screenshot."></textarea>
                            @error('adminPaymentInstructions') <span class="text-xs text-rose-400 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-4 border-t border-white/5 flex items-center justify-between">
                            <span class="text-xs text-zinc-400">Coordinates are shown immediately upon gym owner checkout.</span>
                            <button type="submit"
                                class="px-6 py-2.5 rounded-xl font-athletic text-sm font-black uppercase tracking-wider text-white bg-gradient-to-r from-[#FF6D00] to-[#FF3D00] hover:from-[#FF7D1A] hover:to-[#FF501A] shadow-md transition transform hover:scale-[1.02]">
                                Save GCash Coordinates
                            </button>
                        </div>
                    </div>
                @endif

                <!-- TAB 3: PLAN PREVIEW -->
                @if ($adminActiveTab === 'preview')
                    <div class="p-6 rounded-2xl bg-[#12151b] border border-white/10 space-y-6 animate-in fade-in duration-150">
                        <div class="border-b border-white/5 pb-3 flex items-center justify-between">
                            <div>
                                <h3 class="font-athletic text-xl font-bold text-white uppercase tracking-wider">
                                    Gym Owner View Preview
                                </h3>
                                <p class="text-xs text-zinc-400 mt-0.5">
                                    Live display of subscription options as seen by gym owners.
                                </p>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-mono">
                                Live In Sync
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($plans as $key => $plan)
                                <div class="p-6 rounded-2xl bg-[#0a0c10] border border-white/10 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-athletic text-2xl font-bold text-white uppercase">{{ $plan['name'] }}</h4>
                                            @if (!empty($plan['badge']))
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-orange-500/20 text-orange-400 border border-orange-500/30">
                                                    {{ $plan['badge'] }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="my-3 p-3 rounded-xl bg-white/5 border border-white/5 flex items-center justify-between">
                                            <span class="text-xs font-mono text-zinc-400 uppercase">Duration</span>
                                            <span class="font-athletic text-2xl font-black text-[#FF7A00]">+{{ $plan['days'] }} DAYS</span>
                                        </div>

                                        <div class="font-athletic text-3xl font-black text-white">PHP {{ number_format($plan['price'], 2) }}</div>
                                        <p class="text-xs text-zinc-400 mt-1">Complete unrestricted access to all FitLedger features.</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </form>

        @else
            <!-- ========================================== -->
            <!-- GYM OWNER PLATFORM SUBSCRIPTION VIEW       -->
            <!-- ========================================== -->

            <!-- Pending Admin Approval Alert Banner -->
            @if ($isPendingApproval)
                <div class="rounded-2xl p-5 bg-amber-500/10 border border-amber-500/30 text-amber-200">
                    <div class="flex items-start gap-4">
                        <div class="p-2.5 rounded-xl bg-amber-500/20 text-amber-400 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-athletic text-xl font-bold text-white uppercase tracking-wide">
                                Gym Registration Under Platform Review
                            </h4>
                            <p class="text-xs text-amber-200/90 mt-1 leading-relaxed">
                                <strong>{{ $gym?->name }}</strong> is awaiting platform confirmation. Operational features (Members, Check-ins, Classes, Turnstile scanner) will unlock upon confirmation.
                            </p>
                            @if (! $latestPending && in_array($gym?->subscription_plan, ['monthly', 'annual']))
                                <button type="button" wire:click="openPaymentModal('{{ $gym->subscription_plan }}')"
                                    class="mt-3 px-4 py-2 rounded-xl font-athletic text-xs font-black uppercase text-white bg-gradient-to-r from-[#FF6D00] to-[#FF3D00]">
                                    Submit GCash Payment Proof
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Pending Review Notice -->
            @if ($latestPending)
                <div class="rounded-2xl p-4 bg-amber-500/10 border border-amber-500/30 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-amber-400 animate-ping shrink-0"></div>
                        <div class="text-xs text-zinc-300">
                            Payment submitted (<strong class="font-mono text-white">{{ $latestPending->reference_number }}</strong> &bull; PHP {{ number_format($latestPending->amount, 2) }}). Awaiting platform verification.
                        </div>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-500 text-black font-mono shrink-0">
                        Pending Verification
                    </span>
                </div>
            @endif

            <!-- Clean, Dignified Gym Access Card -->
            <div class="p-6 sm:p-7 rounded-3xl bg-[#12151b] border border-white/10 shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-orange-400">Current Gym Facility</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold font-mono {{ $hasActiveAccess ? 'bg-emerald-500/15 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/15 text-rose-400 border border-rose-500/20' }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <h2 class="font-athletic text-3xl sm:text-4xl font-black text-white uppercase tracking-tight">
                        {{ $gym?->name }}
                    </h2>

                    <p class="text-xs sm:text-sm text-zinc-400 max-w-xl">
                        @if ($isSubscribed || $isOnTrial)
                            Access valid until <strong class="text-white">{{ $expiryDate?->format('F j, Y') }}</strong> ({{ $daysRemaining }} days remaining).
                        @else
                            Platform access expired. Select a plan below and submit GCash payment to renew access.
                        @endif
                    </p>

                    <div class="pt-2 flex flex-wrap items-center gap-3">
                        <button type="button" wire:click="quickRenew"
                            class="px-5 py-2.5 rounded-xl font-athletic text-sm font-black uppercase tracking-wider text-white bg-gradient-to-r from-[#FF6D00] to-[#FF3D00] shadow-md transition transform hover:scale-[1.02] flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>Renew Access / Pay Again</span>
                        </button>
                        <button type="button" wire:click="togglePlanOffers"
                            class="px-5 py-2.5 rounded-xl font-athletic text-sm font-bold uppercase tracking-wider text-zinc-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 transition flex items-center gap-2">
                            <span>{{ $showPlanOffers ? 'Close Plan Selector ▴' : 'Change Subscription Plan ▾' }}</span>
                        </button>
                    </div>
                </div>

                <!-- Days Left Ring/Metric Tile -->
                <div class="p-5 rounded-2xl bg-[#0a0c10] border border-white/10 text-center min-w-[150px] shrink-0">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-zinc-400 block font-mono">Access Status</span>
                    <span class="font-athletic text-5xl font-black block my-1 {{ $daysRemaining > 5 ? 'text-[#FF7A00]' : ($daysRemaining > 0 ? 'text-amber-400' : 'text-rose-500') }}">
                        {{ $daysRemaining }}
                    </span>
                    <span class="text-xs text-zinc-500 font-mono block">Days Left</span>
                </div>
            </div>

            <!-- Clean, Professional Dropdown Plan Selector (Uncrowded, proper controls) -->
            @if ($showPlanOffers)
                <div class="p-6 sm:p-7 rounded-3xl bg-[#12151b] border border-white/10 space-y-6 animate-in fade-in duration-150">
                    <div>
                        <h3 class="font-athletic text-2xl font-black text-white uppercase tracking-wider">
                            Choose Subscription Plan
                        </h3>
                        <p class="text-xs text-zinc-400 mt-0.5">
                            Select a renewal duration. Every plan includes 100% unrestricted access to all FitLedger features with zero member fees.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Plan Selector Dropdown / Option 1 -->
                        <div wire:click="selectOffer('monthly')"
                            class="p-5 rounded-2xl border cursor-pointer transition {{ $selectedOffer === 'monthly' ? 'bg-[#FF7A00]/10 border-[#FF7A00]' : 'bg-[#0a0c10] border-white/10 hover:border-white/20' }}">
                            <div class="flex items-center justify-between">
                                <span class="font-athletic text-xl font-bold text-white uppercase">Monthly All-Access Pass</span>
                                <input type="radio" name="plan" value="monthly" {{ $selectedOffer === 'monthly' ? 'checked' : '' }} class="text-orange-500 focus:ring-0">
                            </div>
                            <div class="mt-2 flex items-baseline gap-2">
                                <span class="font-athletic text-3xl font-black text-white">PHP {{ number_format($plans['monthly']['price'], 2) }}</span>
                                <span class="text-xs text-zinc-400 font-mono">+30 DAYS</span>
                            </div>
                            <span class="text-xs text-zinc-400 mt-2 block">Most flexible option for monthly operational budget.</span>
                        </div>

                        <!-- Plan Selector Dropdown / Option 2 -->
                        <div wire:click="selectOffer('annual')"
                            class="p-5 rounded-2xl border cursor-pointer transition {{ $selectedOffer === 'annual' ? 'bg-[#FF7A00]/10 border-[#FF7A00]' : 'bg-[#0a0c10] border-white/10 hover:border-white/20' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="font-athletic text-xl font-bold text-white uppercase">Annual All-Access Pass</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-orange-500 text-black font-mono">Save 17%</span>
                                </div>
                                <input type="radio" name="plan" value="annual" {{ $selectedOffer === 'annual' ? 'checked' : '' }} class="text-orange-500 focus:ring-0">
                            </div>
                            <div class="mt-2 flex items-baseline gap-2">
                                <span class="font-athletic text-3xl font-black text-[#FF7A00]">PHP {{ number_format($plans['annual']['price'], 2) }}</span>
                                <span class="text-xs text-zinc-400 font-mono">+365 DAYS</span>
                            </div>
                            <span class="text-xs text-zinc-400 mt-2 block">Best value. Full year of turnstile passes, rosters, and kiosk scans.</span>
                        </div>
                    </div>

                    <!-- Selected Plan Action Confirmation -->
                    <div class="pt-4 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-xs text-zinc-300">
                            Selected: <strong class="text-white uppercase">{{ $plans[$selectedOffer]['name'] ?? 'Monthly Pass' }}</strong> 
                            &bull; Duration: <strong class="text-[#FF7A00]">+{{ $plans[$selectedOffer]['days'] ?? 30 }} Days</strong>
                        </div>

                        <button type="button" wire:click="openPaymentModal('{{ $selectedOffer }}')"
                            class="w-full sm:w-auto px-8 py-3 rounded-xl font-athletic text-sm font-black uppercase tracking-wider text-white shadow-lg transition transform hover:scale-[1.02] flex items-center justify-center gap-2"
                            style="background: linear-gradient(135deg, #FF6D00 0%, #FF3D00 100%);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>Proceed with GCash (PHP {{ number_format($plans[$selectedOffer]['price'], 2) }})</span>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Payment & Subscription History Table -->
            <div class="rounded-3xl bg-[#12151b] border border-white/10 overflow-hidden shadow-xl">
                <div class="p-5 border-b border-white/10 flex items-center justify-between">
                    <div>
                        <h3 class="font-athletic text-xl font-bold text-white uppercase tracking-wide">
                            Payment & Subscription History
                        </h3>
                        <p class="text-xs text-zinc-400 mt-0.5">Filtered strictly to {{ $gym?->name ?? 'this gym' }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-[#0e1117] text-zinc-400 uppercase font-mono border-b border-white/5">
                            <tr>
                                <th class="py-3 px-5">Reference No.</th>
                                <th class="py-3 px-5">Plan</th>
                                <th class="py-3 px-5">Amount</th>
                                <th class="py-3 px-5">Status</th>
                                <th class="py-3 px-5">Submitted</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-zinc-300">
                            @forelse ($paymentHistory as $payment)
                                <tr class="hover:bg-white/[0.02] transition">
                                    <td class="py-3 px-5 font-mono font-bold text-white">{{ $payment->reference_number }}</td>
                                    <td class="py-3 px-5 capitalize">{{ $payment->plan ?? $payment->subscription_plan }}</td>
                                    <td class="py-3 px-5 font-bold text-white">PHP {{ number_format($payment->amount, 2) }}</td>
                                    <td class="py-3 px-5">
                                        @php
                                            $st = is_object($payment->status) ? $payment->status->value : (string) $payment->status;
                                        @endphp
                                        @if ($st === 'paid')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/20 font-mono">Verified (Paid)</span>
                                        @elseif ($st === 'pending')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/15 text-amber-400 border border-amber-500/20 font-mono">Pending Review</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/15 text-rose-400 border border-rose-500/20 font-mono">Rejected</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-5 font-mono text-zinc-400">{{ $payment->created_at?->format('M j, Y g:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-zinc-500 font-mono">
                                        No subscription payments recorded for this facility yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- GCash Payment Submission Modal -->
            @if ($showPaymentModal)
                <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm animate-in fade-in duration-200">
                    <div class="w-full max-w-lg rounded-3xl bg-[#12151b] border border-white/10 p-6 sm:p-7 shadow-2xl space-y-5">
                        <div class="flex items-center justify-between border-b border-white/5 pb-3">
                            <h3 class="font-athletic text-2xl font-bold text-white uppercase">
                                GCash Subscription Payment
                            </h3>
                            <button type="button" wire:click="closePaymentModal" class="text-zinc-400 hover:text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <!-- GCash Destination Details Box -->
                        <div class="p-4 rounded-2xl bg-[#0a0c10] border border-orange-500/30 space-y-2">
                            <div class="text-[10px] font-mono font-bold uppercase text-orange-400 tracking-wider">Send Payment via GCash Express Send</div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-zinc-400">GCash Mobile:</span>
                                <strong class="font-mono text-white text-base">{{ $adminGcashNumber }}</strong>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-zinc-400">Account Name:</span>
                                <strong class="text-white">{{ $adminGcashName }}</strong>
                            </div>
                            <div class="flex items-center justify-between text-sm pt-1 border-t border-white/5">
                                <span class="text-zinc-400">Total Due:</span>
                                <strong class="text-emerald-400 text-lg font-athletic">PHP {{ number_format($plans[$selectedPlan]['price'] ?? 1499, 2) }}</strong>
                            </div>
                        </div>

                        <form wire:submit="submitPayment" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300 mb-1.5">
                                    13-Digit GCash Reference Number <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" wire:model="referenceNumber" maxlength="13" required
                                    placeholder="1000123456789"
                                    class="w-full px-4 py-2.5 rounded-xl font-mono text-white bg-[#0a0c10] border border-white/10 focus:border-orange-500 focus:ring-0 transition">
                                @error('referenceNumber') <span class="text-xs text-rose-400 block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300 mb-1.5">
                                    Attach Receipt Screenshot (Optional)
                                </label>
                                <input type="file" wire:model="proofImage" accept="image/*"
                                    class="w-full text-xs text-zinc-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-white/10 file:text-white hover:file:bg-white/20 cursor-pointer">
                                @error('proofImage') <span class="text-xs text-rose-400 block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="pt-2 flex items-center justify-end gap-3">
                                <button type="button" wire:click="closePaymentModal" class="px-4 py-2 text-xs font-bold text-zinc-400 hover:text-white">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-6 py-2.5 rounded-xl font-athletic text-sm font-black uppercase text-white bg-gradient-to-r from-[#FF6D00] to-[#FF3D00] shadow-md transition transform hover:scale-[1.02]">
                                    Submit for Verification
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

        @endif

    </div>
</x-filament-panels::page>
