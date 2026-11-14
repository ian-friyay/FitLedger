@php
    $data = $this->getViewData();
    $user = $data['user'];
    $tenant = $data['tenant'];
@endphp

<x-filament-widgets::widget>
    <div class="fi-account-widget space-y-7 w-full max-w-7xl mx-auto font-sans">

        <!-- Top Header: Soft Greeting & Floating Action Pills -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 pb-1">
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-none">
                        {{ $data['heading'] }} <span class="text-[#22c55e]">{{ $user?->name ?? 'Admin' }}</span>
                    </h1>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-semibold bg-[#1e2634] text-slate-300 shadow-sm">
                        {{ now()->format('l') }} &bull; {{ now()->format('M d, Y') }}
                    </span>
                </div>
                <p class="text-sm text-slate-400 mt-2 font-normal">
                    {{ $data['subheading'] }}
                </p>
            </div>

            <!-- Right Action Pill Buttons (Soft Bubbly Pill Shapes) -->
            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ url('/admin/reports/attendance') }}" 
                    class="fl-soft-pill-btn px-5 py-2.5 text-xs font-semibold text-slate-300 hover:text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span>Export</span>
                </a>

                @if (! $data['isPlatformAdmin'] && $tenant)
                    <a href="{{ url('/admin/' . $tenant->slug . '/scan-qr-code') }}"
                        class="fl-soft-pill-btn px-5 py-2.5 text-xs font-semibold text-slate-300 hover:text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        <span>Scanner</span>
                    </a>

                    <a href="{{ url('/admin/' . $tenant->slug . '/members/create') }}"
                        class="fl-soft-pill-btn-green px-5 py-2.5 text-xs font-extrabold tracking-wide uppercase flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>+ New Member</span>
                    </a>
                @else
                    <a href="{{ url('/admin/gyms') }}"
                        class="fl-soft-pill-btn-green px-5 py-2.5 text-xs font-extrabold tracking-wide uppercase flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>+ Review Gyms</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Main Dashboard Grid (Left 63% Hero + Subcards, Right 37% Plans + Radial + Kiosk) -->
        <div class="fitledger-dashboard-grid">

            <!-- ============================================ -->
            <!-- LEFT COLUMN: Hero Revenue Bar Chart + 2 Metric Cards -->
            <!-- ============================================ -->
            <div class="space-y-7">

                <!-- Hero Revenue & Activity Bar Chart Card (Soft Floating Elevated) -->
                <div class="fl-soft-card p-8 sm:p-9 flex flex-col justify-between">
                    <div>
                        <!-- Top Metric Header -->
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <span class="text-sm font-medium text-slate-400 block">{{ $data['mainMetricLabel'] }}</span>
                                <div class="mt-2 flex items-baseline gap-3">
                                    <span class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight leading-none">
                                        {{ $data['mainMetricValue'] }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-[#22c55e]/15 text-[#22c55e] border border-emerald-500/20">
                                        {{ $data['mainMetricBadge'] }}
                                    </span>
                                </div>
                            </div>

                            <!-- Duration Selector (Soft Pill Dropdown) -->
                            <div class="relative">
                                <select wire:model.live="duration" class="fl-soft-pill-btn px-4 py-2.5 text-xs font-semibold text-slate-300 focus:ring-0 focus:outline-none transition cursor-pointer appearance-none pr-9">
                                    <option value="3m" class="bg-[#161c26] text-white">Select Duration: 3M</option>
                                    <option value="6m" class="bg-[#161c26] text-white">Select Duration: 6M</option>
                                    <option value="12m" class="bg-[#161c26] text-white">Select Duration: 12M</option>
                                </select>
                                <svg class="w-4 h-4 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>

                        <!-- Thick, Rounded-Top Dual-Tone Bar Chart (Memberstack Style) -->
                        <div class="mt-10 pt-4">
                            <div class="flex items-end justify-between gap-3 sm:gap-5 h-48 px-2">
                                @foreach ($data['chartMonths'] as $bar)
                                    <div class="flex-1 flex flex-col items-center gap-3 h-full justify-end group">
                                        <!-- Hover Tooltip -->
                                        <div class="opacity-0 group-hover:opacity-100 transition text-[11px] font-mono text-emerald-400 py-0.5">
                                            ₱{{ number_format($bar['value'], 0) }}
                                        </div>
                                        <!-- Thick Dual-Tone Rounded Pill Bar -->
                                        <div class="w-full max-w-[56px] rounded-2xl overflow-hidden flex flex-col justify-end transition-all duration-300 group-hover:scale-105 shadow-md"
                                            style="height: {{ max(30, $bar['percentage']) }}%;">
                                            <!-- Top Accent Segment (Solid Neon Green) -->
                                            <div class="h-2/5 w-full bg-[#22c55e] rounded-t-2xl shadow-sm"></div>
                                            <!-- Lower Translucent Segment (Soft Emerald Tint) -->
                                            <div class="h-3/5 w-full bg-[#22c55e]/25 rounded-b-2xl"></div>
                                        </div>
                                        <!-- Month Label -->
                                        <span class="text-xs font-semibold text-slate-400 group-hover:text-white transition mt-1">
                                            {{ $bar['label'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Two Sub-Cards Row (Doubled Internal Padding & Thick Solid Pill Progress Bars) -->
                <div class="fitledger-subcards-grid gap-7">

                    <!-- Card 1: Active Members (Left Sub-Card) -->
                    <div class="fl-soft-card p-8 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-slate-400">
                                <span class="text-sm font-medium">{{ $data['stat1Label'] }}</span>
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M17 7H7M17 7V17"/></svg>
                            </div>
                            <div class="mt-3 flex items-baseline gap-3">
                                <span class="text-4xl font-extrabold text-white tracking-tight leading-none">
                                    {{ $data['stat1Value'] }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#22c55e]/15 text-[#22c55e] border border-emerald-500/20">
                                    {{ $data['stat1Badge'] }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-8">
                            <span class="text-xs text-slate-400 font-medium block mb-2.5">{{ $data['stat1TargetLabel'] }}</span>
                            <!-- Thick, Solid, Pill-Shaped Progress Bar (Neon Green) -->
                            <div class="w-full h-9 rounded-full bg-[#10141d] p-1 flex items-center overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-[#22c55e] to-[#10b981] flex items-center pl-3.5 text-xs font-black text-slate-950 shadow-sm transition-all duration-500"
                                    style="width: {{ max(28, $data['stat1Target']) }}%;">
                                    {{ $data['stat1Target'] }}%
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Check-Ins Today (Right Sub-Card) -->
                    <div class="fl-soft-card p-8 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-slate-400">
                                <span class="text-sm font-medium">{{ $data['stat2Label'] }}</span>
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M17 7H7M17 7V17"/></svg>
                            </div>
                            <div class="mt-3 flex items-baseline gap-3">
                                <span class="text-4xl font-extrabold text-white tracking-tight leading-none">
                                    {{ $data['stat2Value'] }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#22c55e]/15 text-[#22c55e] border border-emerald-500/20">
                                    {{ $data['stat2Badge'] }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-8">
                            <span class="text-xs text-slate-400 font-medium block mb-2.5">{{ $data['stat2Sub'] }}</span>
                            <!-- Thick, Solid, Pill-Shaped Progress Bar (Neon Green to Teal) -->
                            <div class="w-full h-9 rounded-full bg-[#10141d] p-1 flex items-center overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-[#22c55e] to-[#14b8a6] flex items-center pl-3.5 text-xs font-black text-slate-950 shadow-sm transition-all duration-500"
                                    style="width: {{ max(28, $data['stat2Target']) }}%;">
                                    {{ $data['stat2Target'] }}%
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- ============================================ -->
            <!-- RIGHT COLUMN: Top Plans + Radial Ring + Feature Banner -->
            <!-- ============================================ -->
            <div class="space-y-7">

                <!-- Card 1: Top Membership Plans (Doubled Padding & Floating Inner Cards) -->
                <div class="fl-soft-card p-8">
                    <h3 class="text-base font-bold text-white mb-5 tracking-tight">
                        {{ $data['isPlatformAdmin'] ? 'Platform Subscription Tiers' : 'Top Membership Plans' }}
                    </h3>

                    <!-- Two side-by-side soft floating inner cards -->
                    <div class="fitledger-top-plans-grid gap-4">
                        @foreach ($data['topItems'] as $idx => $item)
                            <div class="fl-soft-inner-card p-5 flex flex-col justify-between">
                                <div>
                                    <!-- Circular Icon Badge (Neon Green & Soft Orange) -->
                                    <div class="w-11 h-11 rounded-full flex items-center justify-center mb-4 shadow-md {{ $idx === 0 ? 'bg-[#22c55e]/15 text-[#22c55e]' : 'bg-[#FF7A00]/15 text-[#FF7A00]' }}">
                                        @if ($idx === 0)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                    </div>
                                    <span class="text-xs font-semibold text-slate-400 block">{{ $item['name'] }}</span>
                                    <span class="text-2xl font-extrabold text-white block mt-1 tracking-tight">{{ $item['value'] }}</span>
                                </div>
                                <div class="mt-5 pt-3 border-t border-white/[0.04] flex items-center justify-between text-xs text-slate-400 font-medium">
                                    <span>{{ $item['desc'] }}</span>
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M17 7H7M17 7V17"/></svg>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Card 2: Collection Progress & Thick Radial Gauge (Saving in Reference) -->
                <div class="fl-soft-card p-8 flex items-center justify-between gap-6">
                    <div>
                        <span class="text-sm font-medium text-slate-400 block">{{ $data['ringLabel'] }}</span>
                        <span class="text-3xl font-extrabold text-white block mt-2 tracking-tight leading-none">
                            {{ $data['ringValue'] }}
                        </span>
                        <span class="text-xs text-slate-400 font-medium block mt-1.5">
                            {{ $data['ringSubtitle'] }}
                        </span>
                    </div>

                    <!-- Thick Circular Radial Gauge Ring (Neon Green Stroke) -->
                    <div class="relative w-24 h-24 shrink-0 flex items-center justify-center">
                        <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                            <!-- Background Track (Thick & Subtle) -->
                            <path class="text-white/[0.08]" stroke-width="5.5" stroke="currentColor" fill="none"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <!-- Active Arc (Thick Neon Green) -->
                            <path stroke-linecap="round" stroke-width="5.5" stroke="#22c55e" fill="none"
                                stroke-dasharray="{{ max(5, $data['ringPercentage']) }}, 100"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <span class="absolute text-lg font-extrabold text-white font-sans">
                            {{ $data['ringPercentage'] }}%
                        </span>
                    </div>
                </div>

                <!-- Card 3: Banner Feature Card (Turnstile Kiosk Station) -->
                <div class="fl-soft-card p-8 relative overflow-hidden flex flex-col justify-between min-h-[190px]"
                    style="background: linear-gradient(145deg, #182622 0%, #161c26 60%, #131720 100%);">
                    <!-- Soft Ambient Emerald Glow -->
                    <div class="pointer-events-none absolute -right-8 -top-8 w-44 h-44 rounded-full bg-[#22c55e]/15 blur-3xl"></div>
                    <div class="pointer-events-none absolute -left-8 -bottom-8 w-36 h-36 rounded-full bg-[#FF7A00]/10 blur-3xl"></div>

                    <div class="relative z-10">
                        <h4 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight max-w-xs">
                            {{ $data['bannerTitle'] }}
                        </h4>
                        <p class="text-xs text-slate-300 mt-2 max-w-xs leading-relaxed font-normal">
                            {{ $data['bannerSubtitle'] }}
                        </p>
                    </div>

                    <div class="mt-6 relative z-10">
                        <a href="{{ $data['bannerButtonUrl'] }}"
                            class="inline-block px-6 py-3 rounded-full text-xs font-bold text-slate-950 bg-white hover:bg-slate-100 transition shadow-lg">
                            {{ $data['bannerButtonText'] }}
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-filament-widgets::widget>
