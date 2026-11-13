<x-filament-panels::page>
    <div class="space-y-8 max-w-7xl mx-auto">

        @if ($isPlatformAdmin)
            <!-- ========================================== -->
            <!-- PLATFORM ADMIN PRICING & CONFIGURATION VIEW -->
            <!-- ========================================== -->

            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-white/5">
                <div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-orange-500 animate-pulse"></span>
                        <span class="text-xs font-bold uppercase tracking-widest text-orange-400">Platform SuperAdmin Controls</span>
                    </div>
                    <h2 class="font-athletic text-3xl sm:text-4xl font-black text-white uppercase tracking-tight mt-1">
                        Platform Pricing & SaaS Setup
                    </h2>
                    <p class="text-xs sm:text-sm text-zinc-400 mt-1 max-w-2xl">
                        Manage all-access subscription rates, free trial allowances, and GCash receiving coordinates across all gym tenants.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ url('/admin/gyms') }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold text-zinc-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Manage Gyms
                    </a>
                    <a href="{{ url('/admin/platform-payments') }}"
                        class="px-4 py-2 rounded-xl text-xs font-bold text-zinc-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Verify Receipts
                    </a>
                </div>
            </div>

            <!-- Executive KPI Metric Row (Clean 4-column cards) -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem;">
                
                <!-- Total Gyms -->
                <div class="p-5 rounded-2xl shadow-xl flex flex-col justify-between transition-transform hover:-translate-y-0.5"
                    style="background: linear-gradient(180deg, #161a22 0%, #101318 100%); border: 1px solid rgba(255, 255, 255, 0.08);">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-zinc-400">Total Gyms</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-500/15 text-emerald-400 border border-emerald-500/20">
                            {{ $adminStats['activeGyms'] ?? 0 }} Active
                        </span>
                    </div>
                    <div class="mt-3">
                        <span class="font-athletic text-3xl sm:text-4xl font-black text-white block leading-none">
                            {{ $adminStats['totalGyms'] ?? 0 }}
                        </span>
                        <span class="text-[11px] text-zinc-500 mt-1 block">Registered gym branches</span>
                    </div>
                </div>

                <!-- Pending Gym Approvals -->
                <div class="p-5 rounded-2xl shadow-xl flex flex-col justify-between transition-transform hover:-translate-y-0.5"
                    style="background: linear-gradient(180deg, #161a22 0%, #101318 100%); border: 1px solid rgba(255, 255, 255, 0.08);">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-zinc-400">Pending Approvals</span>
                        @if (($adminStats['pendingGyms'] ?? 0) > 0)
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-500/20 text-amber-300 border border-amber-500/30 animate-pulse">
                                Action Needed
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-zinc-800 text-zinc-400">
                                Clear
                            </span>
                        @endif
                    </div>
                    <div class="mt-3 flex items-baseline justify-between">
                        <span class="font-athletic text-3xl sm:text-4xl font-black {{ ($adminStats['pendingGyms'] ?? 0) > 0 ? 'text-amber-400' : 'text-zinc-400' }} block leading-none">
                            {{ $adminStats['pendingGyms'] ?? 0 }}
                        </span>
                        <a href="{{ url('/admin/gyms') }}" class="text-xs text-orange-400 hover:underline font-semibold">Review &rarr;</a>
                    </div>
                </div>

                <!-- Pending GCash Verifications -->
                <div class="p-5 rounded-2xl shadow-xl flex flex-col justify-between transition-transform hover:-translate-y-0.5"
                    style="background: linear-gradient(180deg, #161a22 0%, #101318 100%); border: 1px solid rgba(255, 255, 255, 0.08);">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-zinc-400">GCash Receipts</span>
                        @if (($adminStats['pendingPayments'] ?? 0) > 0)
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-500/20 text-amber-300 border border-amber-500/30 animate-pulse">
                                {{ $adminStats['pendingPayments'] }} Unverified
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-zinc-800 text-zinc-400">
                                Up to Date
                            </span>
                        @endif
                    </div>
                    <div class="mt-3 flex items-baseline justify-between">
                        <span class="font-athletic text-3xl sm:text-4xl font-black {{ ($adminStats['pendingPayments'] ?? 0) > 0 ? 'text-amber-400' : 'text-zinc-400' }} block leading-none">
                            {{ $adminStats['pendingPayments'] ?? 0 }}
                        </span>
                        <a href="{{ url('/admin/platform-payments') }}" class="text-xs text-orange-400 hover:underline font-semibold">Verify &rarr;</a>
                    </div>
                </div>

                <!-- Total SaaS Revenue -->
                <div class="p-5 rounded-2xl shadow-xl flex flex-col justify-between transition-transform hover:-translate-y-0.5"
                    style="background: linear-gradient(180deg, #161a22 0%, #101318 100%); border: 1px solid rgba(255, 255, 255, 0.08);">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-zinc-400">Total SaaS Revenue</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-500/15 text-emerald-400 border border-emerald-500/20">
                            PHP
                        </span>
                    </div>
                    <div class="mt-3">
                        <span class="font-athletic text-2xl sm:text-3xl font-black text-emerald-400 block leading-none">
                            PHP {{ number_format($adminStats['totalRevenue'] ?? 0, 2) }}
                        </span>
                        <span class="text-[11px] text-zinc-500 mt-1 block">Lifetime verified collections</span>
                    </div>
                </div>

            </div>

            <!-- Configuration Form -->
            <form wire:submit="savePricingSettings" class="space-y-6">
                
                <!-- Section 1: SaaS Subscription Tiers -->
                <div class="p-6 sm:p-8 rounded-2xl shadow-xl"
                    style="background-color: #12151b; border: 1px solid rgba(255, 255, 255, 0.08);">
                    
                    <div class="flex items-center gap-3 pb-5 mb-6 border-b border-white/5">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm text-white"
                            style="background: linear-gradient(135deg, #FF6D00 0%, #FF3D00 100%);">
                            1
                        </div>
                        <div>
                            <h3 class="font-athletic text-xl sm:text-2xl font-bold text-white uppercase tracking-wider">
                                SaaS Subscription Tiers & Trial Period
                            </h3>
                            <p class="text-xs text-zinc-400">
                                Define the flat fee and free trial allowance charged per gym location.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Monthly Price -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300">
                                Monthly Subscription (PHP) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs font-bold text-zinc-500">₱</span>
                                <input type="number" step="0.01" wire:model="monthlyPrice"
                                    class="w-full pl-8 pr-4 py-3 rounded-xl text-sm font-bold text-white placeholder-zinc-500 focus:ring-2 focus:ring-orange-500 transition-colors"
                                    style="background-color: #0a0c10; border: 1px solid rgba(255, 255, 255, 0.12);"
                                    placeholder="1499.00" required>
                            </div>
                            <span class="text-[11px] text-zinc-500 block">Recurring every 30 days per gym.</span>
                            @error('monthlyPrice') <span class="text-xs text-rose-400 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Annual Price -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300">
                                Annual Subscription (PHP) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs font-bold text-zinc-500">₱</span>
                                <input type="number" step="0.01" wire:model="annualPrice"
                                    class="w-full pl-8 pr-4 py-3 rounded-xl text-sm font-bold text-white placeholder-zinc-500 focus:ring-2 focus:ring-orange-500 transition-colors"
                                    style="background-color: #0a0c10; border: 1px solid rgba(255, 255, 255, 0.12);"
                                    placeholder="14999.00" required>
                            </div>
                            <span class="text-[11px] text-zinc-500 block">Recurring every 365 days (~17% annual discount).</span>
                            @error('annualPrice') <span class="text-xs text-rose-400 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Trial Days -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300">
                                Free Trial Duration (Days) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" min="1" max="365" wire:model="trialDays"
                                class="w-full px-4 py-3 rounded-xl text-sm font-bold text-white placeholder-zinc-500 focus:ring-2 focus:ring-orange-500 transition-colors"
                                style="background-color: #0a0c10; border: 1px solid rgba(255, 255, 255, 0.12);"
                                placeholder="14" required>
                            <span class="text-[11px] text-zinc-500 block">Free days granted when a gym is approved on Trial.</span>
                            @error('trialDays') <span class="text-xs text-rose-400 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: GCash Receiving Coordinates -->
                <div class="p-6 sm:p-8 rounded-2xl shadow-xl"
                    style="background-color: #12151b; border: 1px solid rgba(255, 255, 255, 0.08);">
                    
                    <div class="flex items-center gap-3 pb-5 mb-6 border-b border-white/5">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm text-white"
                            style="background: linear-gradient(135deg, #FF6D00 0%, #FF3D00 100%);">
                            2
                        </div>
                        <div>
                            <h3 class="font-athletic text-xl sm:text-2xl font-bold text-white uppercase tracking-wider">
                                Platform GCash Payment Coordinates
                            </h3>
                            <p class="text-xs text-zinc-400">
                                The destination account information displayed to gym owners during checkout.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Admin GCash Mobile Number -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300">
                                Platform GCash Mobile Number <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" wire:model="adminGcashNumber"
                                class="w-full px-4 py-3 rounded-xl text-sm font-mono text-white placeholder-zinc-500 focus:ring-2 focus:ring-orange-500 transition-colors"
                                style="background-color: #0a0c10; border: 1px solid rgba(255, 255, 255, 0.12);"
                                placeholder="0917-888-9999" required>
                            <span class="text-[11px] text-zinc-500 block">Primary GCash number where gym owners transfer funds.</span>
                            @error('adminGcashNumber') <span class="text-xs text-rose-400 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Admin GCash Account Name -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300">
                                Platform GCash Account Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" wire:model="adminGcashName"
                                class="w-full px-4 py-3 rounded-xl text-sm font-bold text-white placeholder-zinc-500 focus:ring-2 focus:ring-orange-500 transition-colors"
                                style="background-color: #0a0c10; border: 1px solid rgba(255, 255, 255, 0.12);"
                                placeholder="FitLedger Admin / Adrian" required>
                            <span class="text-[11px] text-zinc-500 block">Registered GCash account name shown for verification.</span>
                            @error('adminGcashName') <span class="text-xs text-rose-400 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Payment Instructions -->
                    <div class="space-y-2 mt-6 pt-5 border-t border-white/5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300">
                            Custom Payment Instructions / Notice for Gym Owners
                        </label>
                        <textarea wire:model="adminPaymentInstructions" rows="3"
                            class="w-full px-4 py-3 rounded-xl text-xs text-zinc-200 placeholder-zinc-500 focus:ring-2 focus:ring-orange-500 transition-colors leading-relaxed"
                            style="background-color: #0a0c10; border: 1px solid rgba(255, 255, 255, 0.12);"
                            placeholder="Send payment via GCash Express Send. Copy the reference number and attach screenshot receipt."></textarea>
                        @error('adminPaymentInstructions') <span class="text-xs text-rose-400 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="p-5 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xl"
                    style="background-color: #12151b; border: 1px solid rgba(255, 255, 255, 0.08);">
                    <div class="text-xs text-zinc-400 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Pricing changes take effect immediately on all new subscriptions and renewals.</span>
                    </div>

                    <button type="submit"
                        class="px-8 py-3.5 rounded-xl font-athletic text-sm font-black uppercase tracking-wider text-white shadow-xl transition-all hover:scale-105 active:scale-95 flex items-center gap-2 flex-shrink-0"
                        style="background: linear-gradient(135deg, #FF6D00 0%, #FF3D00 100%);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Platform Pricing & Payment Details
                    </button>
                </div>

            </form>

            <!-- Section 3: Live Preview of Pricing Tiers -->
            <div class="space-y-4 pt-4">
                <div class="flex items-center justify-between pb-2 border-b border-white/5">
                    <div>
                        <h4 class="font-athletic text-xl font-bold text-white uppercase tracking-wider">
                            Live Pricing Preview
                        </h4>
                        <p class="text-xs text-zinc-400">Real-time preview of how subscription options appear to gym owners.</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-emerald-400 bg-emerald-500/10 border border-emerald-500/20">
                        Synchronized
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($plans as $key => $plan)
                        <div class="p-6 sm:p-7 rounded-2xl flex flex-col justify-between"
                            style="background-color: #12151b; border: 1px solid rgba(255, 255, 255, 0.08); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);">
                            
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <h5 class="font-athletic text-2xl font-bold text-white uppercase">{{ $plan['name'] }}</h5>
                                    @if (!empty($plan['badge']))
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase text-white shadow-md"
                                            style="background: linear-gradient(135deg, #FF6D00 0%, #FF3D00 100%);">
                                            {{ $plan['badge'] }}
                                        </span>
                                    @endif
                                </div>

                                <div class="my-4 p-4 rounded-xl flex items-center justify-between"
                                    style="background-color: #0e1117; border: 1px solid rgba(255, 255, 255, 0.05);">
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-widest text-[#FF7A00] block">Duration</span>
                                        <span class="font-athletic text-4xl font-black text-white leading-none block mt-0.5">
                                            +{{ $plan['days'] }} DAYS
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400 block">Access Window</span>
                                        <span class="font-athletic text-lg font-black text-orange-400 block">
                                            {{ $key === 'annual' ? '365 Days Access' : '30 Days Access' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-baseline gap-2">
                                    <span class="font-athletic text-3xl font-black text-white">PHP {{ number_format($plan['price'], 2) }}</span>
                                    <span class="text-xs text-zinc-400 font-medium">/ {{ $plan['days'] }} days</span>
                                </div>
                                <p class="text-xs text-zinc-400 mt-2">Complete unrestricted access to all FitLedger features.</p>
                            </div>

                            <div class="mt-6 pt-4 border-t border-white/5">
                                <span class="text-[11px] text-zinc-500 block text-center">Owner Action: Pay via GCash</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        @else
            <!-- ========================================== -->
            <!-- GYM OWNER PLATFORM SUBSCRIPTION VIEW       -->
            <!-- ========================================== -->

            <!-- Pending Admin Approval Alert Banner -->
            @if ($isPendingApproval)
                <div class="rounded-2xl p-6 shadow-xl animate-in fade-in duration-200"
                    style="background-color: rgba(245, 158, 11, 0.12); border: 2px solid rgba(245, 158, 11, 0.4); color: #fef3c7;">
                    <div class="flex items-start gap-4">
                        <div class="p-3 rounded-xl flex-shrink-0" style="background-color: rgba(245, 158, 11, 0.25); color: #fbbf24;">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <h4 class="font-athletic text-2xl font-bold text-white uppercase tracking-wider">
                                    Gym Registration Pending Platform Admin Approval
                                </h4>
                                <span class="px-3 py-0.5 rounded-full text-xs font-black uppercase tracking-wider bg-amber-500 text-zinc-950">
                                    Under Review
                                </span>
                            </div>
                            <p class="text-sm text-amber-200/90 mt-2 leading-relaxed">
                                Thank you for registering <strong>{{ $gym?->name }}</strong>! Your gym is currently awaiting confirmation from the Platform Administrator.
                                Selected plan: <strong class="text-white uppercase">{{ $gym?->subscription_plan ?: 'Trial' }}</strong>.
                                Operational features (Members, Check-ins, Classes, Turnstile scanner) will be unlocked once confirmed.
                            </p>
                            @if (! $latestPending && in_array($gym?->subscription_plan, ['monthly', 'annual']))
                                <div class="mt-4 flex items-center gap-3">
                                    <button type="button" wire:click="openPaymentModal('{{ $gym->subscription_plan }}')"
                                        class="px-5 py-2.5 rounded-xl font-athletic text-xs font-black uppercase tracking-wider text-white shadow-lg transition-transform hover:scale-105 active:scale-95"
                                        style="background: linear-gradient(135deg, #FF6D00 0%, #FF3D00 100%);">
                                        Submit GCash Payment Proof
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Pending Payment Review Banner -->
            @if ($latestPending)
                <div class="rounded-2xl p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-lg animate-in fade-in duration-200"
                    style="background-color: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); color: #fef3c7;">
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

            <!-- Status Overview Card for Current Gym -->
            <div class="relative overflow-hidden p-6 sm:p-8 shadow-2xl"
                style="background-color: #12151b; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 1.25rem;">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold uppercase tracking-wider text-orange-400">Current Gym Platform Access</span>
                            @if ($hasActiveAccess)
                                <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-semibold"
                                    style="background-color: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3);">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    {{ $statusLabel }}
                                </span>
                            @elseif ($isPendingApproval)
                                <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-semibold"
                                    style="background-color: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3);">
                                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                    Pending Admin Approval
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
                                This gym has an active all-access platform subscription valid until 
                                <strong class="text-white">{{ $expiryDate?->format('F j, Y') }}</strong>
                                ({{ $daysRemaining }} {{ Str::plural('day', $daysRemaining) }} remaining).
                            @elseif ($isOnTrial)
                                This gym is currently on a free trial valid until 
                                <strong class="text-white">{{ $expiryDate?->format('F j, Y') }}</strong>
                                ({{ $daysRemaining }} {{ Str::plural('day', $daysRemaining) }} remaining).
                            @elseif ($isPendingApproval)
                                Registration submitted. Your subscription will be activated upon admin confirmation.
                            @else
                                <span class="text-rose-400 font-semibold">Your platform access is currently expired.</span>
                                Submit your GCash payment below so the Platform Admin can verify and restore full access for this gym.
                            @endif
                        </p>

                        <!-- Action Buttons: Quick Renew & Change Plan -->
                        <div class="mt-6 flex flex-wrap items-center gap-3">
                            <button type="button" wire:click="quickRenew"
                                class="px-6 py-3 rounded-xl font-athletic text-sm font-black uppercase tracking-wider text-white shadow-lg transition-transform hover:scale-105 active:scale-95 flex items-center gap-2"
                                style="background: linear-gradient(135deg, #FF6D00 0%, #FF3D00 100%);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span>{{ $isSubscribed ? 'Renew Subscription / Pay Again' : ($isOnTrial ? 'Renew Access / Pay Again' : 'Renew Access / Pay Dues') }}</span>
                            </button>

                            <button type="button" wire:click="togglePlanOffers"
                                class="px-5 py-3 rounded-xl font-athletic text-sm font-bold uppercase tracking-wider text-zinc-300 hover:text-white transition-all flex items-center gap-2 bg-white/5 border border-white/10 hover:border-white/20">
                                <svg class="w-4 h-4 text-[#FF7A00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                                <span>{{ $showPlanOffers ? 'Hide Subscription Offers ▴' : 'Change Subscription Plan ▾' }}</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex-shrink-0" style="display: flex; flex-direction: column; align-items: center; justify-content: center; background-color: #0a0c10; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 1.25rem; padding: 1.25rem 2rem; min-width: 160px; text-align: center;">
                        <span style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8; display: block;">Days Left</span>
                        <span class="font-athletic" style="font-size: 3.5rem; font-weight: 900; line-height: 1; color: {{ $daysRemaining > 5 ? '#FF7A00' : ($daysRemaining > 0 ? '#f59e0b' : '#ef4444') }}; margin: 0.35rem 0; display: block;">
                            {{ $daysRemaining }}
                        </span>
                        <span style="font-size: 0.75rem; color: #64748b; display: block;">for this gym</span>
                    </div>
                </div>
            </div>

            <!-- Expandable Platform Pricing Offers Section (Hidden by default when active to prevent clutter) -->
            @if ($showPlanOffers)
                <!-- Section Header -->
                <div class="text-center max-w-2xl mx-auto space-y-1.5 pt-2 animate-in fade-in duration-200">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-[#FF7A00] block">Subscription Options</span>
                    <h3 class="font-athletic text-3xl font-black text-white uppercase tracking-wider">
                        All-in-One Platform Pricing
                    </h3>
                    <p class="text-xs text-zinc-400">
                        Select a duration to expand and proceed to payment. Both tiers include 100% unrestricted access to every FitLedger feature.
                    </p>
                </div>

                <!-- Interactive Selectable Pricing Cards (No permanent highlight on one card; expands on click) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto animate-in fade-in duration-200">
                    @foreach ($plans as $key => $plan)
                        @php $isSelected = ($selectedOffer === $key); @endphp
                        <div wire:click="selectOffer('{{ $key }}')"
                            class="relative flex flex-col justify-between p-6 sm:p-7 rounded-2xl transition-all duration-200 cursor-pointer select-none"
                            style="background-color: #12151b;
                                   border: {{ $isSelected ? '2px solid #FF7A00' : '1px solid rgba(255, 255, 255, 0.08)' }};
                                   box-shadow: {{ $isSelected ? '0 10px 30px -10px rgba(255, 122, 0, 0.35)' : '0 10px 25px -5px rgba(0, 0, 0, 0.4)' }};
                                   transform: {{ $isSelected ? 'scale(1.01)' : 'scale(1)' }};">
                            
                            <div>
                                <!-- Plan Title & Dynamic Selection Badge -->
                                <div class="flex items-center justify-between gap-3 mb-2">
                                    <h4 class="font-athletic text-2xl font-bold text-white uppercase tracking-wider">{{ $plan['name'] }}</h4>
                                    @if ($isSelected)
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black tracking-widest uppercase text-white shadow-md flex items-center gap-1"
                                            style="background: linear-gradient(135deg, #FF6D00 0%, #FF3D00 100%);">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                            Selected
                                        </span>
                                    @elseif (!empty($plan['badge']))
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black tracking-widest uppercase text-zinc-300 bg-white/5 border border-white/10">
                                            {{ $plan['badge'] }}
                                        </span>
                                    @endif
                                </div>

                                <!-- HERO FOCUS ON DAYS (Deal breaker duration) -->
                                <div class="my-4 p-4 rounded-xl flex items-center justify-between transition-colors"
                                    style="background: {{ $isSelected ? 'linear-gradient(135deg, rgba(255, 122, 0, 0.12) 0%, rgba(255, 61, 0, 0.05) 100%)' : '#0e1117' }}; border: 1px solid {{ $isSelected ? 'rgba(255, 122, 0, 0.3)' : 'rgba(255, 255, 255, 0.05)' }};">
                                    <div>
                                        <span class="text-[10px] font-bold uppercase tracking-widest text-[#FF7A00] block">Duration</span>
                                        <span class="font-athletic text-4xl sm:text-5xl font-black text-white tracking-tight leading-none block mt-0.5">
                                            +{{ $plan['days'] }} DAYS
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400 block">Access Window</span>
                                        <span class="font-athletic text-lg font-black text-orange-400 block">
                                            {{ $key === 'annual' ? '365 Days Access' : '30 Days Access' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Price -->
                                <div class="flex items-baseline gap-2">
                                    <span class="font-athletic text-3xl sm:text-4xl font-black text-white">PHP {{ number_format($plan['price'], 2) }}</span>
                                    <span class="text-xs text-zinc-400 font-medium">/ {{ $plan['days'] }} days</span>
                                </div>

                                <!-- Clean Label (No feature checklist clutter) -->
                                <p class="text-xs text-zinc-400 mt-2 leading-relaxed">
                                    Complete, unrestricted access to all FitLedger features for {{ $plan['days'] }} days.
                                </p>
                            </div>

                            <!-- Expandable Payment Action -->
                            <div class="mt-6 pt-5 border-t border-white/5">
                                @if ($isSelected)
                                    <button type="button" wire:click.stop="openPaymentModal('{{ $key }}')"
                                        class="w-full py-3.5 px-6 rounded-xl font-athletic text-sm font-black uppercase tracking-wider text-white shadow-lg transition-transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 animate-in fade-in duration-150"
                                        style="background: linear-gradient(135deg, #FF6D00 0%, #FF3D00 100%);">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        <span>Pay via GCash (PHP {{ number_format($plan['price'], 2) }})</span>
                                    </button>
                                @else
                                    <div class="flex items-center justify-between text-xs text-zinc-400 hover:text-white transition-colors py-2">
                                        <span class="font-medium">Click card to select this {{ $plan['days'] }}-day plan</span>
                                        <span class="text-[#FF7A00] font-bold">&rarr;</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Single Shared Features Accordion (Zero redundant clutter) -->
                <div x-data="{ showFeatures: false }" class="max-w-4xl mx-auto pt-1 animate-in fade-in duration-200">
                    <div class="text-center">
                        <button type="button" @click="showFeatures = !showFeatures"
                            class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-zinc-400 hover:text-white transition-colors py-2 px-4 rounded-xl bg-white/[0.03] border border-white/5 hover:border-white/10">
                            <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span x-text="showFeatures ? 'Hide All-Inclusive Feature Details ▴' : 'View What\'s Included in All Plans ▾'"></span>
                        </button>
                    </div>

                    <div x-show="showFeatures" x-collapse x-cloak class="mt-4 p-5 rounded-2xl bg-white/[0.02] border border-white/5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3.5 text-xs text-zinc-300">
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>Unlimited Members & Passes</span>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>Turnstile & QR Scanner Station</span>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>Gym Classes & Group Session Bookings</span>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>Member Digital Pass & Self-Service Portal</span>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>Attendance Logs & Financial Analytics</span>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>Zero Per-Member Surcharges</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Payment Submission Modal -->
            @if ($showPaymentModal)
                <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm animate-in fade-in duration-150">
                    <div class="relative w-full max-w-lg p-6 sm:p-8 rounded-2xl shadow-2xl text-left"
                        style="background-color: #12151b; border: 1px solid rgba(255, 255, 255, 0.12);">
                        
                        <!-- Modal Close Button -->
                        <button type="button" wire:click="closePaymentModal"
                            class="absolute top-5 right-5 text-zinc-400 hover:text-white p-1 rounded-lg hover:bg-white/5 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>

                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2.5 rounded-xl" style="background-color: rgba(255, 87, 34, 0.15); color: #FF5722;">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-athletic text-2xl font-black text-white uppercase tracking-wider">
                                        Submit GCash Payment
                                    </h4>
                                    <p class="text-xs text-zinc-400 mt-0.5">
                                        Plan: <strong class="text-white">{{ $plans[$selectedPlan]['name'] ?? 'Subscription' }}</strong> &bull;
                                        Amount: <strong class="text-emerald-400">PHP {{ number_format($plans[$selectedPlan]['price'] ?? 0, 2) }}</strong> &bull;
                                        <span class="text-[#FF7A00] font-bold">+{{ $plans[$selectedPlan]['days'] ?? 30 }} Days to {{ $gym?->name }}</span>
                                    </p>
                                </div>
                            </div>

                            <!-- GCash Transfer Instructions Card -->
                            <div class="p-4 rounded-xl space-y-2"
                                style="background-color: #0a0c10; border: 1px dashed rgba(255, 255, 255, 0.15);">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-orange-400">GCash Recipient Details</span>
                                <div class="grid grid-cols-2 gap-3 text-xs">
                                    <div>
                                        <span class="text-zinc-500 block">GCash Number</span>
                                        <span class="font-mono font-bold text-white text-sm select-all">{{ $gcash['number'] }}</span>
                                    </div>
                                    <div>
                                        <span class="text-zinc-500 block">Account Name</span>
                                        <span class="font-bold text-white text-sm">{{ $gcash['name'] }}</span>
                                    </div>
                                </div>
                                <p class="text-[11px] text-zinc-400 pt-1 leading-relaxed border-t border-white/5">
                                    {{ $gcash['instructions'] }}
                                </p>
                            </div>

                            <form wire:submit="submitManualPayment" class="space-y-4 pt-2">
                                <!-- Reference Number -->
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300">
                                        GCash Reference Number <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" wire:model="referenceNumber"
                                        placeholder="e.g. 1002 4829 1928"
                                        class="w-full px-4 py-2.5 rounded-xl text-sm font-mono text-white placeholder-zinc-500 focus:ring-2 focus:ring-orange-500 transition-colors"
                                        style="background-color: #0a0c10; border: 1px solid rgba(255, 255, 255, 0.12);"
                                        required>
                                    <span class="text-[11px] text-zinc-500">Copy the reference code from your GCash SMS or transaction receipt.</span>
                                    @error('referenceNumber') <span class="text-xs text-rose-400 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Receipt Screenshot Upload -->
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300">
                                        Upload Proof of Payment (Screenshot)
                                    </label>
                                    <input type="file" wire:model="proofImage" accept="image/*"
                                        class="w-full px-3 py-2 text-xs text-zinc-400 rounded-xl cursor-pointer focus:outline-none file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-orange-500/20 file:text-orange-400 hover:file:bg-orange-500/30"
                                        style="background-color: #0a0c10; border: 1px solid rgba(255, 255, 255, 0.12);"
                                        required>
                                    <div wire:loading wire:target="proofImage" class="text-xs text-orange-400 mt-1">Uploading receipt...</div>
                                    @error('proofImage') <span class="text-xs text-rose-400 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Sender Notes -->
                                <div class="space-y-1">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300">
                                        Additional Notes (Optional)
                                    </label>
                                    <input type="text" wire:model="senderNotes"
                                        placeholder="e.g. Paid via Maria Santos's GCash"
                                        class="w-full px-4 py-2 rounded-xl text-xs text-white placeholder-zinc-500 focus:ring-2 focus:ring-orange-500 transition-colors"
                                        style="background-color: #0a0c10; border: 1px solid rgba(255, 255, 255, 0.12);">
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/5">
                                    <button type="button" wire:click="closePaymentModal"
                                        class="px-4 py-2.5 rounded-xl text-xs font-bold text-zinc-400 hover:text-white transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="px-6 py-2.5 rounded-xl font-athletic text-xs font-black uppercase tracking-wider text-white shadow-lg transition-transform hover:scale-105 active:scale-95 flex items-center gap-2"
                                        style="background: linear-gradient(135deg, #FF6D00 0%, #FF3D00 100%);">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Confirm & Submit Proof
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Payment History for Current Gym -->
            <div class="space-y-4 pt-4">
                <div class="flex items-center justify-between">
                    <h4 class="font-athletic text-2xl font-bold text-white uppercase tracking-wider">
                        Payment & Subscription History ({{ $gym?->name }})
                    </h4>
                    <span class="text-xs text-zinc-400">Filtered strictly to this gym location</span>
                </div>

                @if ($payments->isEmpty())
                    <div class="p-8 text-center rounded-2xl"
                        style="background-color: #12151b; border: 1px dashed rgba(255, 255, 255, 0.08);">
                        <p class="text-xs text-zinc-400">No payment records found for this gym location yet.</p>
                    </div>
                @else
                    <div class="overflow-x-auto rounded-2xl shadow-xl"
                        style="background-color: #12151b; border: 1px solid rgba(255, 255, 255, 0.08);">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-zinc-400 uppercase tracking-wider border-b border-white/5"
                                    style="background-color: rgba(255, 255, 255, 0.02);">
                                    <th class="px-5 py-3 font-bold">Reference #</th>
                                    <th class="px-5 py-3 font-bold">Plan</th>
                                    <th class="px-5 py-3 font-bold">Amount</th>
                                    <th class="px-5 py-3 font-bold">Status</th>
                                    <th class="px-5 py-3 font-bold">Submitted Date</th>
                                    <th class="px-5 py-3 font-bold">Admin Notes</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach ($payments as $payment)
                                    <tr class="hover:bg-white/[0.02] transition-colors">
                                        <td class="px-5 py-3 font-mono font-bold text-white">
                                            {{ $payment->reference_number }}
                                        </td>
                                        <td class="px-5 py-3 capitalize text-zinc-300">
                                            {{ $payment->plan }}
                                        </td>
                                        <td class="px-5 py-3 font-bold text-white">
                                            PHP {{ number_format($payment->amount, 2) }}
                                        </td>
                                        <td class="px-5 py-3">
                                            @if ($payment->status->value === 'paid')
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider"
                                                    style="background-color: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3);">
                                                    Approved
                                                </span>
                                            @elseif ($payment->status->value === 'pending')
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider"
                                                    style="background-color: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3);">
                                                    Pending Review
                                                </span>
                                            @else
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider"
                                                    style="background-color: rgba(244, 63, 94, 0.15); color: #fb7185; border: 1px solid rgba(244, 63, 94, 0.3);">
                                                    {{ ucfirst($payment->status->value) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3 text-zinc-400">
                                            {{ $payment->created_at->format('M j, Y h:i A') }}
                                        </td>
                                        <td class="px-5 py-3 text-zinc-400 italic">
                                            {{ $payment->admin_notes ?: '—' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endif

    </div>
</x-filament-panels::page>
