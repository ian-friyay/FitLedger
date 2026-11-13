@php
    $data = $this->getViewData();
    $user = $data['user'];
    $tenant = $data['tenant'];
@endphp

<div class="fi-account-widget space-y-6">

    <!-- Top Header: Greeting & Action Pill Buttons (Exact mirror of reference) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-1">
        <div>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="font-athletic text-4xl sm:text-5xl font-black text-white tracking-tight leading-none uppercase">
                    {{ $data['heading'] }} <span class="text-[#FF7A00]">{{ $user?->name ?? 'Admin' }}</span>
                </h1>
                <span class="px-3 py-1 rounded-full text-[11px] font-mono font-bold uppercase tracking-wider bg-white/5 text-orange-400 border border-orange-500/20">
                    {{ now()->format('l') }} &bull; {{ now()->format('M d, Y') }}
                </span>
            </div>
            <p class="text-xs sm:text-sm text-zinc-400 mt-1 font-medium">
                {{ $data['subheading'] }}
            </p>
        </div>

        <!-- Right Action Pill Buttons -->
        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ url('/admin/reports/attendance') }}" 
                class="px-4 py-2 rounded-full text-xs font-bold text-zinc-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 transition-colors flex items-center gap-1.5 shadow-sm">
                <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Export
            </a>

            @if (! $data['isPlatformAdmin'] && $tenant)
                <a href="{{ url('/admin/' . $tenant->slug . '/scan-qr-code') }}"
                    class="px-4 py-2 rounded-full text-xs font-bold text-zinc-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 transition-colors flex items-center gap-1.5 shadow-sm">
                    <svg class="w-3.5 h-3.5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    Scanner
                </a>

                <a href="{{ url('/admin/' . $tenant->slug . '/members/create') }}"
                    class="px-5 py-2 rounded-full text-xs font-black uppercase tracking-wider text-black bg-[#FF7A00] hover:bg-orange-400 shadow-md shadow-orange-500/20 transition-transform hover:scale-105 active:scale-95 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    + New Member
                </a>
            @else
                <a href="{{ url('/admin/gyms') }}"
                    class="px-5 py-2 rounded-full text-xs font-black uppercase tracking-wider text-black bg-[#FF7A00] hover:bg-orange-400 shadow-md shadow-orange-500/20 transition-transform hover:scale-105 active:scale-95 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    + Review Gyms
                </a>
            @endif
        </div>
    </div>

    <!-- Main Asymmetric Dashboard Grid (Exact placement of reference image) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- ============================================ -->
        <!-- LEFT COLUMN (7 COLS): Hero Card + 2 Metric Cards -->
        <!-- ============================================ -->
        <div class="lg:col-span-7 space-y-6">

            <!-- Hero Revenue & Activity Bar Chart Card -->
            <div class="rounded-3xl p-6 sm:p-7 shadow-xl border border-white/10 flex flex-col justify-between"
                style="background-color: #12151b;">
                <div>
                    <!-- Top Metric Header -->
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <span class="text-xs font-medium text-zinc-400 block">{{ $data['mainMetricLabel'] }}</span>
                            <div class="mt-1 flex items-baseline gap-3">
                                <span class="font-athletic text-3xl sm:text-5xl font-black text-white tracking-tight leading-none">
                                    {{ $data['mainMetricValue'] }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/20 font-mono">
                                    {{ $data['mainMetricBadge'] }}
                                </span>
                            </div>
                        </div>

                        <!-- Dropdown Filter (Select Duration) -->
                        <div class="relative">
                            <select wire:model.live="duration" class="px-3 py-1.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-semibold text-zinc-300 focus:ring-1 focus:ring-[#FF7A00] focus:border-[#FF7A00] transition cursor-pointer appearance-none pr-8">
                                <option value="3m" class="bg-[#12151b] text-white">Last 3 Months</option>
                                <option value="6m" class="bg-[#12151b] text-white">Last 6 Months</option>
                                <option value="12m" class="bg-[#12151b] text-white">Last 12 Months</option>
                            </select>
                            <svg class="w-3.5 h-3.5 text-zinc-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    <!-- 6-Month Dual-Toned Bar Chart (Exact visual shape of reference) -->
                    <div class="mt-8 pt-4">
                        <div class="flex items-end justify-between gap-2 sm:gap-4 h-44 px-2">
                            @foreach ($data['chartMonths'] as $bar)
                                <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end group">
                                    <!-- Hover Tooltip -->
                                    <div class="opacity-0 group-hover:opacity-100 transition text-[10px] font-mono text-zinc-400 py-0.5">
                                        ₱{{ number_format($bar['value'], 0) }}
                                    </div>
                                    <!-- Two-Tone Stacked Pill Bar -->
                                    <div class="w-full max-w-[48px] rounded-2xl overflow-hidden flex flex-col justify-end transition-all duration-300 group-hover:scale-105"
                                        style="height: {{ $bar['percentage'] }}%;">
                                        <!-- Top Accent Segment -->
                                        <div class="h-2/5 w-full bg-[#FF5722] rounded-t-2xl"></div>
                                        <!-- Lower Translucent Segment -->
                                        <div class="h-3/5 w-full bg-gradient-to-b from-[#FF7A00]/50 to-[#FF7A00]/20 rounded-b-2xl"></div>
                                    </div>
                                    <!-- Month Label -->
                                    <span class="text-xs font-semibold text-zinc-400 group-hover:text-white transition mt-1">
                                        {{ $bar['label'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Sub-Cards Row (Income & Expenses in reference -> Members & Check-ins) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <!-- Card 1: Active Members (Left sub-card) -->
                <div class="rounded-3xl p-6 shadow-xl border border-white/10 flex flex-col justify-between"
                    style="background-color: #12151b;">
                    <div>
                        <div class="flex items-center justify-between text-zinc-400">
                            <span class="text-xs font-medium">{{ $data['stat1Label'] }}</span>
                            <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M17 7H7M17 7V17"/></svg>
                        </div>
                        <div class="mt-2 flex items-baseline gap-2.5">
                            <span class="font-athletic text-3xl sm:text-4xl font-black text-white leading-none">
                                {{ $data['stat1Value'] }}
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-500/15 text-orange-400 border border-orange-500/20 font-mono">
                                {{ $data['stat1Badge'] }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <span class="text-[11px] text-zinc-400 font-medium block mb-2">{{ $data['stat1TargetLabel'] }}</span>
                        <!-- Rounded Progress Bar with Gradient (Emerald / Orange) -->
                        <div class="w-full h-8 rounded-2xl bg-black/40 p-1 overflow-hidden border border-white/5">
                            <div class="h-full rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 flex items-center justify-start pl-3 text-[11px] font-bold text-black font-mono transition-all duration-500"
                                style="width: {{ max(25, $data['stat1Target']) }}%;">
                                {{ $data['stat1Target'] }}%
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Check-Ins Today (Right sub-card) -->
                <div class="rounded-3xl p-6 shadow-xl border border-white/10 flex flex-col justify-between"
                    style="background-color: #12151b;">
                    <div>
                        <div class="flex items-center justify-between text-zinc-400">
                            <span class="text-xs font-medium">{{ $data['stat2Label'] }}</span>
                            <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M17 7H7M17 7V17"/></svg>
                        </div>
                        <div class="mt-2 flex items-baseline gap-2.5">
                            <span class="font-athletic text-3xl sm:text-4xl font-black text-white leading-none">
                                {{ $data['stat2Value'] }}
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/15 text-emerald-400 border border-emerald-500/20 font-mono">
                                {{ $data['stat2Badge'] }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <span class="text-[11px] text-zinc-400 font-medium block mb-2">{{ $data['stat2Sub'] }}</span>
                        <!-- Rounded Progress Bar with Gradient (Orange / Flame) -->
                        <div class="w-full h-8 rounded-2xl bg-black/40 p-1 overflow-hidden border border-white/5">
                            <div class="h-full rounded-xl bg-gradient-to-r from-[#FF7A00] to-[#FF3D00] flex items-center justify-start pl-3 text-[11px] font-bold text-black font-mono transition-all duration-500"
                                style="width: {{ max(25, $data['stat2Target']) }}%;">
                                {{ $data['stat2Target'] }}%
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- ============================================ -->
        <!-- RIGHT COLUMN (5 COLS): Top Plans + Radial Ring + Feature Banner -->
        <!-- ============================================ -->
        <div class="lg:col-span-5 space-y-6">

            <!-- Card 1: Top Plans (Most Spending in reference) -->
            <div class="rounded-3xl p-6 sm:p-7 shadow-xl border border-white/10"
                style="background-color: #12151b;">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">
                    {{ $data['isPlatformAdmin'] ? 'Platform Subscription Tiers' : 'Top Membership Plans' }}
                </h3>

                <!-- Two side-by-side inner cards with rounded corners -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($data['topItems'] as $idx => $item)
                        <div class="p-4 rounded-2xl border border-white/5 transition hover:border-orange-500/30 flex flex-col justify-between"
                            style="background-color: #0e1117;">
                            <div>
                                <!-- Circular Icon Badge (FitLedger athletic colors) -->
                                <div class="w-10 h-10 rounded-full flex items-center justify-center mb-3 shadow-md {{ $idx === 0 ? 'bg-orange-500/20 text-[#FF7A00]' : 'bg-emerald-500/20 text-emerald-400' }}">
                                    @if ($idx === 0)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                </div>
                                <span class="text-xs font-bold text-zinc-400 block">{{ $item['name'] }}</span>
                                <span class="font-athletic text-xl font-black text-white block mt-0.5">{{ $item['value'] }}</span>
                            </div>
                            <div class="mt-4 pt-2 border-t border-white/5 flex items-center justify-between text-[11px] text-zinc-500 font-mono">
                                <span>{{ $item['desc'] }}</span>
                                <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M17 7H7M17 7V17"/></svg>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Card 2: Collection Progress & Radial Gauge (Saving in reference) -->
            <div class="rounded-3xl p-6 sm:p-7 shadow-xl border border-white/10 flex items-center justify-between gap-6"
                style="background-color: #12151b;">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-zinc-400 block">{{ $data['ringLabel'] }}</span>
                    <span class="font-athletic text-2xl sm:text-3xl font-black text-white block mt-1.5 leading-none">
                        {{ $data['ringValue'] }}
                    </span>
                    <span class="text-[11px] text-zinc-500 font-medium block mt-1">
                        {{ $data['ringSubtitle'] }}
                    </span>
                </div>

                <!-- Circular Radial Gauge Ring (Purple / Flame gradient) -->
                <div class="relative w-20 h-20 shrink-0 flex items-center justify-center">
                    <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                        <!-- Background Circle -->
                        <path class="text-white/10" stroke-width="3.5" stroke="currentColor" fill="none"
                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <!-- Progress Arc -->
                        <path stroke-linecap="round" stroke-width="3.8" stroke="url(#flameGradient)" fill="none"
                            stroke-dasharray="{{ $data['ringPercentage'] }}, 100"
                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <defs>
                            <linearGradient id="flameGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#FF5722" />
                                <stop offset="100%" stop-color="#FF7A00" />
                            </linearGradient>
                        </defs>
                    </svg>
                    <span class="absolute font-athletic text-lg font-black text-white font-mono">
                        {{ $data['ringPercentage'] }}%
                    </span>
                </div>
            </div>

            <!-- Card 3: Banner Feature Card ("How to manage..." in reference) -->
            <div class="rounded-3xl p-7 shadow-xl border border-white/10 relative overflow-hidden flex flex-col justify-between min-h-[160px]"
                style="background: linear-gradient(135deg, #1f1208 0%, #17100b 50%, #12151b 100%);">
                <!-- Ambient Glow Decoration -->
                <div class="pointer-events-none absolute -right-8 -top-8 w-40 h-40 rounded-full bg-orange-500/15 blur-2xl"></div>
                <div class="pointer-events-none absolute -left-8 -bottom-8 w-32 h-32 rounded-full bg-[#FF3D00]/10 blur-2xl"></div>

                <div class="relative z-10">
                    <h4 class="font-athletic text-2xl sm:text-3xl font-black text-white uppercase tracking-tight leading-tight max-w-xs">
                        {{ $data['bannerTitle'] }}
                    </h4>
                    <p class="text-xs text-zinc-300 mt-2 max-w-xs leading-relaxed">
                        {{ $data['bannerSubtitle'] }}
                    </p>
                </div>

                <div class="mt-6 relative z-10">
                    <a href="{{ $data['bannerButtonUrl'] }}"
                        class="inline-block px-5 py-2.5 rounded-full text-xs font-black uppercase tracking-wider text-black bg-[#FF7A00] hover:bg-orange-400 transition shadow-lg shadow-orange-500/20">
                        {{ $data['bannerButtonText'] }}
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>
