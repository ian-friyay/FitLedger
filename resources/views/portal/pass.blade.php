@extends('portal.layout')

@section('title', $member->name . ' - Digital Pass')

@section('content')
<div class="space-y-6">

    <!-- Top Navigation & Gym Status Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('portal.lookup') }}" class="text-xs font-bold text-slate-400 hover:text-white transition flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/5 border border-white/5 hover:border-white/10">
            <svg class="w-4 h-4 text-[#FF7A00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Change Member</span>
        </a>

        @if ($gym)
            @php $schedule = $gym->getOperatingSchedule(); @endphp
            @if ($gym->isOpenNow())
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Gym Open ({{ $schedule['opening_time'] }} - {{ $schedule['closing_time'] }})</span>
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-black uppercase tracking-wider bg-slate-800 text-slate-400 border border-white/5">
                    <span class="w-2 h-2 rounded-full bg-slate-500"></span>
                    <span>Gym Closed</span>
                </span>
            @endif
        @endif
    </div>

    <!-- Responsive Grid: Stacks on mobile, 2 columns on laptop/desktop -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- LEFT COLUMN: The Physical-style Digital Pass Card (lg:col-span-5) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="relative overflow-hidden rounded-3xl p-6 sm:p-7 shadow-2xl transition transform hover:scale-[1.01]"
                style="background: linear-gradient(165deg, #181b22 0%, #101217 100%); border: 1px solid rgba(255, 255, 255, 0.1); box-shadow: 0 20px 40px -15px rgba(0,0,0,0.7);">
                
                <!-- Holographic top accent glow -->
                <div class="absolute -top-16 -right-16 w-36 h-36 rounded-full blur-2xl pointer-events-none" style="background: rgba(255, 87, 34, 0.25);"></div>

                <!-- Card Header -->
                <div class="flex items-center justify-between pb-4 border-b border-white/10 relative z-10">
                    <div class="flex items-center gap-3">
                        @if ($gym?->getLogoUrl())
                            <img src="{{ $gym->getLogoUrl() }}" alt="{{ $gym->name }}" class="w-10 h-10 rounded-xl object-cover bg-white/5 border border-white/10 shadow flex-shrink-0">
                        @else
                            <div class="w-10 h-10 rounded-xl bg-orange-600/20 border border-orange-500/30 flex items-center justify-center text-orange-400 font-athletic text-xl font-black">
                                FL
                            </div>
                        @endif
                        <div>
                            <span class="font-athletic text-lg font-black uppercase tracking-wider text-white block leading-none">
                                {{ $gym?->name ?? 'FitLedger' }}
                            </span>
                            <span class="text-[9px] font-bold uppercase tracking-widest text-[#FF7A00] block mt-0.5">
                                Official Digital Pass
                            </span>
                        </div>
                    </div>

                    @php $access = $member->accessStatus(); @endphp
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 shadow"
                        style="background-color: {{ $access->colorHex() }}25; color: {{ $access->colorHex() }}; border: 1px solid {{ $access->colorHex() }}50;">
                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $access->colorHex() }};"></span>
                        <span>{{ $access->getLabel() }}</span>
                    </span>
                </div>

                <!-- Member Name & Pass Info -->
                <div class="py-4 text-center relative z-10">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 block mb-1">Pass Holder</span>
                    <h1 class="font-athletic text-3xl sm:text-4xl font-black uppercase tracking-tight text-white leading-none">
                        {{ $member->name }}
                    </h1>
                    <p class="text-xs text-slate-400 font-mono mt-1">
                        {{ $member->phone }}
                    </p>
                </div>

                <!-- High-Contrast QR Code Viewport -->
                <div class="text-center relative z-10 my-1">
                    <div class="inline-block p-4 sm:p-5 rounded-3xl bg-white shadow-2xl shadow-black/80 ring-4 ring-white/10">
                        <div class="w-[190px] h-[190px] sm:w-[210px] sm:h-[210px] flex items-center justify-center">
                            {!! $qrSvg !!}
                        </div>
                    </div>

                    <div class="mt-3 font-mono text-xs font-bold text-slate-400 tracking-wider">
                        {{ $member->qr_code }}
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1 max-w-xs mx-auto">
                        Point this QR code at the front-desk webcam or turnstile barcode gun to check in.
                    </p>
                </div>

                <!-- Card Bottom Actions -->
                <div class="pt-4 mt-3 border-t border-white/10 flex items-center justify-center gap-2 relative z-10">
                    <button type="button" 
                        onclick="navigator.clipboard.writeText(window.location.href); alert('Pass URL copied to clipboard!');"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-xs font-bold text-slate-300 hover:text-white transition border border-white/10">
                        <svg class="w-4 h-4 text-[#FF7A00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                        <span>Copy Pass Link</span>
                    </button>

                    <button type="button" 
                        onclick="window.print();"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-xs font-bold text-slate-300 hover:text-white transition border border-white/10">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        <span>Print</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Pass Details, Stats & History (lg:col-span-7) -->
        <div class="lg:col-span-7 space-y-5">

            <!-- 3-Column Stats Row -->
            @php
                $days = $subscription ? (int) today()->diffInDays($subscription->ends_at, false) : 0;
                $totalDays = $subscription ? max(1, (int) $subscription->starts_at->diffInDays($subscription->ends_at)) : 0;
                $checkInsCount = $member->checkIns()->count();
            @endphp
            <div class="grid grid-cols-3 gap-3 text-center">
                <!-- Stat 1: Days Remaining -->
                <div class="rounded-2xl py-4 px-3 text-center" style="background-color: #12151b; border: 1px solid rgba(255, 255, 255, 0.08);">
                    <span class="font-athletic text-4xl sm:text-5xl font-black {{ $days <= 3 ? 'text-amber-400' : 'text-white' }} block leading-none">
                        {{ max(0, $days) }}
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1 block">Days Left</span>
                </div>

                <!-- Stat 2: Total Duration -->
                <div class="rounded-2xl py-4 px-3 text-center" style="background-color: #12151b; border: 1px solid rgba(255, 255, 255, 0.08);">
                    <span class="font-athletic text-4xl sm:text-5xl font-black text-white block leading-none">
                        {{ $totalDays }}
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1 block">Duration (Days)</span>
                </div>

                <!-- Stat 3: Total Check-Ins -->
                <div class="rounded-2xl py-4 px-3 text-center" style="background-color: #12151b; border: 1px solid rgba(255, 255, 255, 0.08);">
                    <span class="font-athletic text-4xl sm:text-5xl font-black text-[#FF7A00] block leading-none">
                        {{ $checkInsCount }}
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1 block">Total Visits</span>
                </div>
            </div>

            <!-- Current Plan Details Card -->
            <div class="rounded-2xl p-5" style="background-color: #12151b; border: 1px solid rgba(255, 255, 255, 0.08);">
                <div class="flex items-center justify-between pb-3 border-b border-white/5">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Current Enrolled Plan</span>
                    <span class="text-xs font-mono font-bold text-white">
                        {{ $subscription?->plan?->name ?? 'No active subscription' }}
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-4 pt-3 text-xs">
                    <div>
                        <span class="text-slate-500 block text-[11px]">Valid From:</span>
                        <strong class="text-slate-200">{{ $subscription ? $subscription->starts_at->format('M j, Y') : '—' }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-[11px]">Valid Until:</span>
                        <strong class="text-white">{{ $subscription ? $subscription->ends_at->format('M j, Y') : '—' }}</strong>
                    </div>
                </div>
            </div>

            <!-- Outstanding Dues & GCash Action Card -->
            @if ($outstanding && $outstanding->amountDue() > 0)
                <div class="rounded-2xl p-5 border border-amber-500/30 shadow-xl"
                    style="background: linear-gradient(165deg, rgba(245, 158, 11, 0.1) 0%, rgba(18, 21, 27, 0.95) 100%);">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-black uppercase tracking-wider text-amber-400 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                            <span>Payment Due</span>
                        </span>
                        <span class="text-xs font-mono text-slate-300 font-semibold">{{ $outstanding->plan?->name }}</span>
                    </div>

                    <div class="flex items-baseline justify-between mb-4">
                        <span class="text-xs font-bold text-slate-400">Outstanding Balance:</span>
                        <span class="font-athletic text-3xl sm:text-4xl font-black text-white">PHP {{ number_format($outstanding->amountDue(), 2) }}</span>
                    </div>

                    <form action="{{ route('portal.pay', ['qr_code' => $member->qr_code]) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-4 px-6 rounded-2xl font-athletic text-xl font-black uppercase tracking-wider text-white btn-orange transition flex items-center justify-center gap-2 transform active:scale-[0.98]">
                            <span>Pay with GCash</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </form>
                    <span class="text-[11px] text-center text-slate-400 block mt-2">Instant reactivation upon payment via PayMongo.</span>
                </div>
            @endif

            <!-- Recent Check-In History Activity -->
            <div class="rounded-2xl p-5" style="background-color: #12151b; border: 1px solid rgba(255, 255, 255, 0.08);">
                <div class="flex items-center justify-between mb-3 pb-2 border-b border-white/5">
                    <span class="font-athletic text-xl font-bold uppercase tracking-wider text-white">Recent Attendance</span>
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Last 5 Scans</span>
                </div>

                @if ($checkIns->isEmpty())
                    <div class="text-center py-6 text-xs text-slate-500">
                        No check-in entries recorded yet.
                    </div>
                @else
                    <div class="divide-y divide-white/5">
                        @foreach ($checkIns as $checkIn)
                            <div class="py-3 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-white/5 flex items-center justify-center flex-shrink-0">
                                        <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $checkIn->status->colorHex() }};"></span>
                                    </div>
                                    <div>
                                        <span class="font-bold text-white block">{{ $checkIn->scanned_at->format('M j, Y &bull; g:i A') }}</span>
                                        <span class="text-[11px] text-slate-400 block">{{ $checkIn->subscription?->plan?->name ?? 'Front-desk scan' }}</span>
                                    </div>
                                </div>

                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider"
                                    style="background-color: {{ $checkIn->status->colorHex() }}20; color: {{ $checkIn->status->colorHex() }};">
                                    {{ $checkIn->status->getLabel() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
