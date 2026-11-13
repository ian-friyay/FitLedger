<x-filament-panels::page>
    <div class="space-y-8">

        @if ($isPlatformAdmin)
            <!-- ========================================== -->
            <!-- PLATFORM ADMIN PRICING & CONFIGURATION VIEW -->
            <!-- ========================================== -->

            <!-- Admin Platform Overview Metrics -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-5 rounded-2xl shadow-lg"
                    style="background-color: #12151b; border: 1px solid rgba(255, 255, 255, 0.08);">
                    <span class="text-xs font-bold uppercase tracking-wider text-zinc-400">Total Gyms</span>
                    <div class="mt-2 flex items-baseline justify-between">
                        <span class="font-athletic text-3xl font-black text-white">{{ $adminStats['totalGyms'] ?? 0 }}</span>
                        <span class="text-xs text-emerald-400 font-medium">{{ $adminStats['activeGyms'] ?? 0 }} Active</span>
                    </div>
                </div>

                <div class="p-5 rounded-2xl shadow-lg"
                    style="background-color: #12151b; border: 1px solid rgba(255, 255, 255, 0.08);">
                    <span class="text-xs font-bold uppercase tracking-wider text-zinc-400">Pending Gym Approvals</span>
                    <div class="mt-2 flex items-baseline justify-between">
                        <span class="font-athletic text-3xl font-black {{ ($adminStats['pendingGyms'] ?? 0) > 0 ? 'text-amber-400' : 'text-zinc-400' }}">
                            {{ $adminStats['pendingGyms'] ?? 0 }}
                        </span>
                        <a href="{{ url('/admin/gyms') }}" class="text-xs text-orange-400 hover:underline">Review Gyms &rarr;</a>
                    </div>
                </div>

                <div class="p-5 rounded-2xl shadow-lg"
                    style="background-color: #12151b; border: 1px solid rgba(255, 255, 255, 0.08);">
                    <span class="text-xs font-bold uppercase tracking-wider text-zinc-400">Pending GCash Verifications</span>
                    <div class="mt-2 flex items-baseline justify-between">
                        <span class="font-athletic text-3xl font-black {{ ($adminStats['pendingPayments'] ?? 0) > 0 ? 'text-amber-400' : 'text-zinc-400' }}">
                            {{ $adminStats['pendingPayments'] ?? 0 }}
                        </span>
                        <a href="{{ url('/admin/platform-payments') }}" class="text-xs text-orange-400 hover:underline">Review Receipts &rarr;</a>
                    </div>
                </div>

                <div class="p-5 rounded-2xl shadow-lg"
                    style="background-color: #12151b; border: 1px solid rgba(255, 255, 255, 0.08);">
                    <span class="text-xs font-bold uppercase tracking-wider text-zinc-400">Total SaaS Revenue</span>
                    <div class="mt-2 flex items-baseline justify-between">
                        <span class="font-athletic text-2xl sm:text-3xl font-black text-emerald-400">
                            PHP {{ number_format($adminStats['totalRevenue'] ?? 0, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Platform Pricing Configuration Form -->
            <div class="p-6 sm:p-8 rounded-2xl shadow-2xl"
                style="background-color: #12151b; border: 1px solid rgba(255, 255, 255, 0.08);">
                <div class="border-b border-white/5 pb-5 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 rounded-xl" style="background-color: rgba(255, 87, 34, 0.15); color: #FF5722;">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-athletic text-2xl font-black text-white uppercase tracking-wider">
                                Platform Subscription Pricing & Payment Setup
                            </h3>
                            <p class="text-xs text-zinc-400">
                                As Platform SuperAdmin, configure the fees charged to gym owners and set your receiving GCash account details.
                            </p>
                        </div>
                    </div>
                </div>

                <form wire:submit="savePricingSettings" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Monthly Price -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300">
                                Monthly Subscription Price (PHP) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs font-bold text-zinc-500">₱</span>
                                <input type="number" step="0.01" wire:model="monthlyPrice"
                                    class="w-full pl-8 pr-4 py-2.5 rounded-xl text-sm font-bold text-white placeholder-zinc-500 focus:ring-2 focus:ring-orange-500 transition-colors"
                                    style="background-color: #0a0c10; border: 1px solid rgba(255, 255, 255, 0.12);"
                                    placeholder="1499.00" required>
                            </div>
                            <span class="text-[11px] text-zinc-500">Billed every 30 days per gym.</span>
                            @error('monthlyPrice') <span class="text-xs text-rose-400 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Annual Price -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300">
                                Annual Subscription Price (PHP) <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs font-bold text-zinc-500">₱</span>
                                <input type="number" step="0.01" wire:model="annualPrice"
                                    class="w-full pl-8 pr-4 py-2.5 rounded-xl text-sm font-bold text-white placeholder-zinc-500 focus:ring-2 focus:ring-orange-500 transition-colors"
                                    style="background-color: #0a0c10; border: 1px solid rgba(255, 255, 255, 0.12);"
                                    placeholder="14999.00" required>
                            </div>
                            <span class="text-[11px] text-zinc-500">Billed every 365 days per gym.</span>
                            @error('annualPrice') <span class="text-xs text-rose-400 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Trial Days -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300">
                                Free Trial Duration (Days) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" min="1" max="365" wire:model="trialDays"
                                class="w-full px-4 py-2.5 rounded-xl text-sm font-bold text-white placeholder-zinc-500 focus:ring-2 focus:ring-orange-500 transition-colors"
                                style="background-color: #0a0c10; border: 1px solid rgba(255, 255, 255, 0.12);"
                                placeholder="14" required>
                            <span class="text-[11px] text-zinc-500">Free days granted when a gym is approved on Trial.</span>
                            @error('trialDays') <span class="text-xs text-rose-400 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-white/5">
                        <!-- Admin GCash Mobile Number -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300">
                                Platform GCash Mobile Number <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" wire:model="adminGcashNumber"
                                class="w-full px-4 py-2.5 rounded-xl text-sm font-bold text-white placeholder-zinc-500 focus:ring-2 focus:ring-orange-500 transition-colors"
                                style="background-color: #0a0c10; border: 1px solid rgba(255, 255, 255, 0.12);"
                                placeholder="0917-888-9999" required>
                            <span class="text-[11px] text-zinc-500">The destination mobile number displayed to gym owners.</span>
                            @error('adminGcashNumber') <span class="text-xs text-rose-400 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Admin GCash Account Name -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300">
                                Platform GCash Account Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" wire:model="adminGcashName"
                                class="w-full px-4 py-2.5 rounded-xl text-sm font-bold text-white placeholder-zinc-500 focus:ring-2 focus:ring-orange-500 transition-colors"
                                style="background-color: #0a0c10; border: 1px solid rgba(255, 255, 255, 0.12);"
                                placeholder="FitLedger Admin / Adrian" required>
                            <span class="text-[11px] text-zinc-500">Account name shown to owners for verification.</span>
                            @error('adminGcashName') <span class="text-xs text-rose-400 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Payment Instructions -->
                    <div class="space-y-1.5 pt-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300">
                            Custom Payment Instructions / Notes for Gym Owners
                        </label>
                        <textarea wire:model="adminPaymentInstructions" rows="3"
                            class="w-full px-4 py-2.5 rounded-xl text-sm text-zinc-200 placeholder-zinc-500 focus:ring-2 focus:ring-orange-500 transition-colors"
                            style="background-color: #0a0c10; border: 1px solid rgba(255, 255, 255, 0.12);"
                            placeholder="Send payment via GCash Express Send. Include your Gym Name in the message."></textarea>
                        @error('adminPaymentInstructions') <span class="text-xs text-rose-400 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/5">
                        <button type="submit"
                            class="px-6 py-3 rounded-xl font-athletic text-sm font-black uppercase tracking-wider text-white shadow-lg transition-transform hover:scale-105 active:scale-95 flex items-center gap-2"
                            style="background: linear-gradient(135deg, #FF6D00 0%, #FF3D00 100%);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Platform Pricing & Payment Details
                        </button>
                    </div>
                </form>
            </div>

            <!-- Live Preview of Pricing Tiers -->
            <div class="space-y-4 pt-4">
                <div class="flex items-center justify-between">
                    <h4 class="font-athletic text-xl font-bold text-white uppercase tracking-wider">
                        Live Preview (What Gym Owners See)
                    </h4>
                    <span class="text-xs text-zinc-400">Updated in real-time</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl">
                    @foreach ($plans as $key => $plan)
                        <div class="p-6 rounded-2xl"
                            style="background-color: #12151b; border: {{ $key === 'annual' ? '2px solid #FF5722' : '1px solid rgba(255, 255, 255, 0.08)' }};">
                            <div class="flex items-center justify-between mb-2">
                                <h5 class="font-athletic text-2xl font-bold text-white uppercase">{{ $plan['name'] }}</h5>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase text-white"
                                    style="background: linear-gradient(135deg, #FF6D00 0%, #FF3D00 100%);">
                                    {{ $plan['badge'] }}
                                </span>
                            </div>
                            <p class="text-xs text-zinc-400 mb-4">{{ $plan['description'] }}</p>
                            <div class="flex items-baseline gap-2">
                                <span class="font-athletic text-4xl font-black text-white">PHP {{ number_format($plan['price'], 2) }}</span>
                                <span class="text-xs text-zinc-400 font-medium">/ {{ $plan['days'] }} days</span>
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

            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto space-y-1.5 pt-2">
                <h3 class="font-athletic text-3xl font-black text-white uppercase tracking-wider">
                    All-in-One Platform Pricing
                </h3>
                <p class="text-xs text-zinc-400">
                    Pay one flat fee for this gym location. Every plan includes 100% of all FitLedger features with zero hidden add-ons or per-member surcharges.
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

                        <div class="mt-8 pt-6 border-t border-white/5">
                            <button type="button" wire:click="openPaymentModal('{{ $key }}')"
                                class="w-full py-3.5 px-6 rounded-xl font-athletic text-sm font-black uppercase tracking-wider text-white shadow-lg transition-transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2"
                                style="background: {{ $key === 'annual' ? 'linear-gradient(135deg, #FF6D00 0%, #FF3D00 100%)' : '#27272a' }};">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                Pay via GCash (PHP {{ number_format($plan['price'], 2) }})
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

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
                                    <p class="text-xs text-zinc-400">
                                        Plan: <strong class="text-white">{{ $plans[$selectedPlan]['name'] ?? 'Subscription' }}</strong> &bull;
                                        Amount: <strong class="text-emerald-400">PHP {{ number_format($plans[$selectedPlan]['price'] ?? 0, 2) }}</strong>
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
                                        style="background-color: #0a0c10; border: 1px solid rgba(255, 255, 255, 0.12);">
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
