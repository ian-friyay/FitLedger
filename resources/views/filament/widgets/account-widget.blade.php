@php
    $data = $this->getViewData();
    $user = $data['user'];
    $tenant = $data['tenant'];
@endphp

<x-filament-widgets::widget>
    <div class="fi-account-widget space-y-6 w-full max-w-7xl mx-auto font-sans">

        <!-- Top Header: Soft Greeting & Action Pills -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-0.5">
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-none">
                        {{ $data['heading'] }} <span class="text-[#22c55e]">{{ $user?->name ?? 'Admin' }}</span>
                    </h1>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#1e2634] text-slate-300 shadow-sm">
                        {{ now()->format('l') }} &bull; {{ now()->format('M d, Y') }}
                    </span>
                </div>
                <p class="text-xs sm:text-sm text-slate-400 mt-1.5 font-normal">
                    {{ $data['subheading'] }}
                </p>
            </div>

            <!-- Right Action Pill Buttons (High Contrast + New Member) -->
            <div class="flex items-center gap-2.5 flex-wrap">
                <a href="{{ url('/admin/reports/attendance') }}" 
                    class="fl-soft-pill-btn px-4 py-2 text-xs font-semibold text-slate-300 hover:text-white flex items-center gap-1.5 shadow-sm">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span>Export</span>
                </a>

                @if (! $data['isPlatformAdmin'] && $tenant)
                    <a href="{{ url('/admin/' . $tenant->slug . '/scan-qr-code') }}"
                        class="fl-soft-pill-btn px-4 py-2 text-xs font-semibold text-slate-300 hover:text-white flex items-center gap-1.5 shadow-sm">
                        <svg class="w-3.5 h-3.5 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        <span>Scanner</span>
                    </a>

                    <a href="{{ url('/admin/' . $tenant->slug . '/members/create') }}"
                        class="px-5 py-2 rounded-full text-xs font-black uppercase tracking-wider text-black bg-[#22c55e] hover:bg-emerald-400 shadow-md shadow-emerald-500/20 transition-all hover:scale-105 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span class="text-black font-black">New Member</span>
                    </a>
                @else
                    <a href="{{ url('/admin/gyms') }}"
                        class="px-5 py-2 rounded-full text-xs font-black uppercase tracking-wider text-black bg-[#22c55e] hover:bg-emerald-400 shadow-md shadow-emerald-500/20 transition-all hover:scale-105 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span class="text-black font-black">Review Gyms</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Main Dashboard Grid (Left 62% Hero + Subcards, Right 38% Plans + Radial + Kiosk) -->
        <div class="fitledger-dashboard-grid">

            <!-- ============================================ -->
            <!-- LEFT COLUMN: Hero Revenue Bar Chart + 2 Metric Cards -->
            <!-- ============================================ -->
            <div class="fl-dashboard-col">

                <!-- Hero Revenue & Activity Bar Chart Card (Structured & Tightened) -->
                <div class="fl-soft-card p-6 sm:p-7 flex flex-col justify-between">
                    <div>
                        <!-- Top Metric Header -->
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <span class="text-xs font-medium text-slate-400 block">{{ $data['mainMetricLabel'] }}</span>
                                <div class="mt-1.5 flex items-baseline gap-2.5">
                                    <span class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-none">
                                        {{ $data['mainMetricValue'] }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#22c55e]/15 text-[#22c55e] border border-emerald-500/20">
                                        {{ $data['mainMetricBadge'] }}
                                    </span>
                                </div>
                            </div>

                            <!-- Duration Selector (Soft Pill Dropdown) -->
                            <div class="relative">
                                <select wire:model.live="duration" class="fl-soft-pill-btn px-3.5 py-2 text-xs font-semibold text-slate-300 focus:ring-0 focus:outline-none transition cursor-pointer appearance-none pr-8">
                                    <option value="3m" class="bg-[#161c26] text-white">Select Duration: 3M</option>
                                    <option value="6m" class="bg-[#161c26] text-white">Select Duration: 6M</option>
                                    <option value="12m" class="bg-[#161c26] text-white">Select Duration: 12M</option>
                                </select>
                                <svg class="w-3.5 h-3.5 text-slate-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>

                        <!-- Chunky Rounded-Top Dual-Tone Bar Chart Canvas -->
                        <div class="mt-8 pt-2">
                            <!-- Fixed Height Bar Canvas (130px) -->
                            <div style="height: 130px; display: flex; align-items: flex-end; justify-content: space-between; gap: 0.75rem; width: 100%; padding: 0 0.25rem;">
                                @foreach ($data['chartMonths'] as $bar)
                                    @php
                                        $barHeight = max(28, min(100, (int) ($bar['percentage'] ?? 45)));
                                    @endphp
                                    <div style="flex: 1; display: flex; justify-content: center; align-items: flex-end; height: 100%; position: relative;" class="group">
                                        <!-- Tooltip Amount Floating Above Bar -->
                                        <div style="position: absolute; top: -1.4rem;" class="opacity-0 group-hover:opacity-100 transition text-[10px] font-mono text-emerald-400 pointer-events-none">
                                            ₱{{ number_format($bar['value'], 0) }}
                                        </div>
                                        <!-- Chunky Dual-Toned Rounded Pill Bar -->
                                        <div style="width: 100%; max-width: 44px; height: {{ $barHeight }}%; min-height: 28px; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; justify-content: flex-end; box-shadow: 0 4px 10px rgba(0,0,0,0.3); transition: transform 0.2s;" class="group-hover:scale-105">
                                            <!-- Top Segment: Solid Neon Green -->
                                            <div style="height: 38%; width: 100%; background-color: #22c55e;"></div>
                                            <!-- Lower Segment: Soft Translucent Green Tint -->
                                            <div style="height: 62%; width: 100%; background: linear-gradient(180deg, rgba(34, 197, 94, 0.35) 0%, rgba(34, 197, 94, 0.12) 100%);"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Dedicated Month Labels Row Below Chart -->
                            <div style="display: flex; justify-content: space-between; gap: 0.75rem; width: 100%; padding: 0 0.25rem; margin-top: 0.65rem;">
                                @foreach ($data['chartMonths'] as $bar)
                                    <div style="flex: 1; text-align: center; font-size: 0.75rem; font-weight: 600; color: #94a3b8;">
                                        {{ $bar['label'] }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Two Sub-Cards Row (Enforced Strict Gap & Solid Pill Progress Bars) -->
                <div class="fitledger-subcards-grid gap-5">

                    <!-- Card 1: Active Members (Left Sub-Card) -->
                    <div class="fl-soft-card p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-slate-400">
                                <span class="text-xs font-semibold uppercase tracking-wider">{{ $data['stat1Label'] }}</span>
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M17 7H7M17 7V17"/></svg>
                            </div>
                            <div class="mt-2.5 flex items-baseline gap-2.5">
                                <span class="text-3xl font-extrabold text-white tracking-tight leading-none">
                                    {{ $data['stat1Value'] }}
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-[#22c55e]/15 text-[#22c55e] border border-emerald-500/20">
                                    {{ $data['stat1Badge'] }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="flex items-center justify-between text-xs text-slate-400 mb-2 font-medium">
                                <span>{{ $data['stat1TargetLabel'] }}</span>
                                <span class="text-emerald-400 font-bold font-mono">{{ $data['stat1Target'] }}%</span>
                            </div>
                            <!-- Thick, Solid, Pill-Shaped Progress Bar (Neon Green) -->
                            <div class="w-full h-7 rounded-full bg-[#10141d] p-0.5 flex items-center overflow-hidden border border-white/[0.04]">
                                <div class="h-full rounded-full bg-gradient-to-r from-[#22c55e] to-[#10b981] flex items-center justify-start pl-2.5 text-[11px] font-black text-slate-950 shadow-sm transition-all duration-500"
                                    style="width: {{ max(16, $data['stat1Target']) }}%; min-width: 38px;">
                                    {{ $data['stat1Target'] }}%
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Check-Ins Today (Right Sub-Card) -->
                    <div class="fl-soft-card p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-slate-400">
                                <span class="text-xs font-semibold uppercase tracking-wider">{{ $data['stat2Label'] }}</span>
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M17 7H7M17 7V17"/></svg>
                            </div>
                            <div class="mt-2.5 flex items-baseline gap-2.5">
                                <span class="text-3xl font-extrabold text-white tracking-tight leading-none">
                                    {{ $data['stat2Value'] }}
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-[#22c55e]/15 text-[#22c55e] border border-emerald-500/20">
                                    {{ $data['stat2Badge'] }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="flex items-center justify-between text-xs text-slate-400 mb-2 font-medium">
                                <span>{{ $data['stat2Sub'] }}</span>
                                <span class="text-emerald-400 font-bold font-mono">{{ $data['stat2Target'] }}%</span>
                            </div>
                            <!-- Thick, Solid, Pill-Shaped Progress Bar (Neon Green to Teal) -->
                            <div class="w-full h-7 rounded-full bg-[#10141d] p-0.5 flex items-center overflow-hidden border border-white/[0.04]">
                                <div class="h-full rounded-full bg-gradient-to-r from-[#22c55e] to-[#14b8a6] flex items-center justify-start pl-2.5 text-[11px] font-black text-slate-950 shadow-sm transition-all duration-500"
                                    style="width: {{ max(16, $data['stat2Target']) }}%; min-width: 38px;">
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
            <div class="fl-dashboard-col">

                <!-- Card 1: Top Membership Plans -->
                <div class="fl-soft-card p-6">
                    <h3 class="text-sm font-bold text-white mb-4 tracking-tight uppercase">
                        {{ $data['isPlatformAdmin'] ? 'Platform Subscription Tiers' : 'Top Membership Plans' }}
                    </h3>

                    <!-- Two side-by-side soft floating inner cards -->
                    <div class="fitledger-top-plans-grid gap-3.5">
                        @foreach ($data['topItems'] as $idx => $item)
                            <div class="fl-soft-inner-card p-4 flex flex-col justify-between">
                                <div>
                                    <!-- Circular Icon Badge -->
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center mb-3 shadow-sm {{ $idx === 0 ? 'bg-[#22c55e]/15 text-[#22c55e]' : 'bg-[#FF7A00]/15 text-[#FF7A00]' }}">
                                        @if ($idx === 0)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                    </div>
                                    <span class="text-[11px] font-semibold text-slate-400 block">{{ $item['name'] }}</span>
                                    <span class="text-xl font-extrabold text-white block mt-0.5 tracking-tight">{{ $item['value'] }}</span>
                                </div>
                                <div class="mt-4 pt-2.5 border-t border-white/[0.04] flex items-center justify-between text-[11px] text-slate-400 font-medium">
                                    <span>{{ $item['desc'] }}</span>
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M17 7H7M17 7V17"/></svg>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Card 2: Collection Progress & Scaled-Down Radial Gauge (Contained & Halved Stroke) -->
                <div class="fl-soft-card p-6 flex items-center justify-between gap-4 overflow-hidden">
                    <div class="flex-1 min-w-0 pr-3">
                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block">{{ $data['ringLabel'] }}</span>
                        <span class="text-xl sm:text-2xl font-extrabold text-white block mt-1 tracking-tight leading-tight truncate">
                            {{ $data['ringValue'] }}
                        </span>
                        <span class="text-xs text-slate-400 font-medium block mt-1">
                            {{ $data['ringSubtitle'] }}
                        </span>
                    </div>

                    <!-- Compact Radial Gauge Ring (Stroke Thickness Halved to 2.8) -->
                    <div class="relative w-16 h-16 shrink-0 flex items-center justify-center">
                        <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                            <!-- Background Track (Halved Stroke) -->
                            <path class="text-white/[0.08]" stroke-width="2.8" stroke="currentColor" fill="none"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <!-- Active Arc (Neon Green, Halved Stroke) -->
                            <path stroke-linecap="round" stroke-width="2.8" stroke="#22c55e" fill="none"
                                stroke-dasharray="{{ max(5, $data['ringPercentage']) }}, 100"
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <span class="absolute text-xs font-extrabold text-white font-sans">
                            {{ $data['ringPercentage'] }}%
                        </span>
                    </div>
                </div>

                <!-- Card 3: Banner Feature Card (Turnstile Kiosk Station) -->
                <div class="fl-soft-card p-6 relative overflow-hidden flex flex-col justify-between min-h-[170px]"
                    style="background: linear-gradient(145deg, #182622 0%, #161c26 60%, #131720 100%);">
                    <!-- Soft Ambient Emerald Glow -->
                    <div class="pointer-events-none absolute -right-8 -top-8 w-40 h-40 rounded-full bg-[#22c55e]/15 blur-3xl"></div>
                    <div class="pointer-events-none absolute -left-8 -bottom-8 w-32 h-32 rounded-full bg-[#FF7A00]/10 blur-3xl"></div>

                    <div class="relative z-10">
                        <h4 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight leading-tight max-w-xs">
                            {{ $data['bannerTitle'] }}
                        </h4>
                        <p class="text-xs text-slate-300 mt-1.5 max-w-xs leading-relaxed font-normal">
                            {{ $data['bannerSubtitle'] }}
                        </p>
                    </div>

                    <div class="mt-5 relative z-10">
                        <a href="{{ $data['bannerButtonUrl'] }}"
                            class="inline-block px-5 py-2.5 rounded-full text-xs font-bold text-slate-950 bg-white hover:bg-slate-100 transition shadow-md">
                            {{ $data['bannerButtonText'] }}
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-filament-widgets::widget>
