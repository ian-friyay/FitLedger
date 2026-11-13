@extends('portal.layout')

@section('title', $member->name . ' - FitLedger Pass')

@section('content')
<div class="space-y-5">

    <!-- Top Navigation / Gym Status -->
    <div class="flex items-center justify-between px-1">
        <a href="{{ route('portal.lookup') }}" class="text-xs font-bold text-slate-400 hover:text-white transition flex items-center gap-1.5">
            <svg class="w-4 h-4 text-[#FF7A00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Back</span>
        </a>

        @if ($gym)
            @php $schedule = $gym->getOperatingSchedule(); @endphp
            @if ($gym->isOpenNow())
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Open Now ({{ $schedule['opening_time'] }}-{{ $schedule['closing_time'] }})</span>
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-slate-800 text-slate-400 border border-white/5">
                    <span class="w-2 h-2 rounded-full bg-slate-500"></span>
                    <span>Closed</span>
                </span>
            @endif
        @endif
    </div>

    <!-- Gym Brand & Member Title Header (Bold Athletic Styling) -->
    <div class="px-1">
        <div class="flex items-center gap-3.5 mb-2">
            @if ($gym?->getLogoUrl())
                <img src="{{ $gym->getLogoUrl() }}" alt="{{ $gym->name }}" class="w-12 h-12 rounded-2xl object-cover bg-white/5 border border-white/10 shadow-lg flex-shrink-0">
            @endif
            <div>
                <span class="text-[10px] font-black uppercase tracking-widest text-[#FF7A00] block">
                    {{ $gym?->name ?? 'FitLedger' }} &bull; Digital Pass
                </span>
                <h1 class="font-athletic text-4xl sm:text-5xl font-black uppercase tracking-tight text-white leading-none mt-0.5">
                    {{ $member->name }}
                </h1>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2 mt-2.5">
            @php $access = $member->accessStatus(); @endphp
            <span class="px-3 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider"
                style="background-color: {{ $access->colorHex() }}; color: #ffffff;">
                {{ $access->getLabel() }}
            </span>

            @if ($subscription?->plan)
                <span class="px-3 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-[#FF6D00]/20 text-[#FF7A00] border border-[#FF6D00]/30">
                    {{ $subscription->plan->name }}
                </span>
            @endif

            <span class="text-xs text-slate-500 font-mono">{{ $member->phone }}</span>
        </div>
    </div>

    <!-- 3-Column Stats Row (Modeled after the reference screen) -->
    @php
        $days = $subscription ? (int) today()->diffInDays($subscription->ends_at, false) : 0;
        $totalDays = $subscription ? max(1, (int) $subscription->starts_at->diffInDays($subscription->ends_at)) : 0;
        $checkInsCount = $member->checkIns()->count();
    @endphp
    <div class="grid grid-cols-3 gap-2.5 text-center">
        <!-- Stat 1: Days Remaining -->
        <div class="app-card rounded-2xl py-3.5 px-2">
            <span class="font-athletic text-3xl sm:text-4xl font-black {{ $days <= 3 ? 'text-amber-400' : 'text-white' }} block leading-none">
                {{ max(0, $days) }}
            </span>
            <span class="text-[9px] font-black uppercase tracking-wider text-slate-400 mt-1 block">Days Left</span>
        </div>

        <!-- Stat 2: Total Duration -->
        <div class="app-card rounded-2xl py-3.5 px-2">
            <span class="font-athletic text-3xl sm:text-4xl font-black text-white block leading-none">
                {{ $totalDays }}
            </span>
            <span class="text-[9px] font-black uppercase tracking-wider text-slate-400 mt-1 block">Duration</span>
        </div>

        <!-- Stat 3: Total Check-Ins -->
        <div class="app-card rounded-2xl py-3.5 px-2">
            <span class="font-athletic text-3xl sm:text-4xl font-black text-[#FF7A00] block leading-none">
                {{ $checkInsCount }}
            </span>
            <span class="text-[9px] font-black uppercase tracking-wider text-slate-400 mt-1 block">Visits</span>
        </div>
    </div>

    <!-- The QR Code Pass Card (OLED Fitness Badge) -->
    <div class="app-card rounded-3xl p-6 shadow-2xl relative overflow-hidden">
        <div class="text-center">
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-3">Entrance QR Code</span>
            
            <!-- High-Contrast QR Code Container -->
            <div class="inline-block p-4 rounded-3xl bg-white shadow-2xl shadow-black/80 ring-4 ring-white/10 my-1">
                <div class="w-[200px] h-[200px] flex items-center justify-center">
                    {!! $qrSvg !!}
                </div>
            </div>

            <div class="mt-3 font-mono text-xs font-bold text-slate-400">
                {{ $member->qr_code }}
            </div>
            <p class="text-[11px] text-slate-500 mt-1">
                Point this code at the gym camera or turnstile reader to enter.
            </p>

            <button type="button" 
                onclick="navigator.clipboard.writeText(window.location.href); alert('Pass URL copied!');"
                class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/5 hover:bg-white/10 text-xs font-bold text-slate-300 transition border border-white/5">
                <svg class="w-3.5 h-3.5 text-[#FF7A00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                </svg>
                <span>Copy Pass Link</span>
            </button>
        </div>
    </div>

    <!-- Outstanding Dues & GCash Action Card (Orange Hero Button) -->
    @if ($outstanding && $outstanding->amountDue() > 0)
        <div class="app-card rounded-3xl p-5 border border-amber-500/30 bg-gradient-to-b from-[#1c1813] to-[#12151b] shadow-xl">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-black uppercase tracking-wider text-amber-400 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                    <span>Payment Due</span>
                </span>
                <span class="text-xs font-mono text-slate-400">{{ $outstanding->plan?->name }}</span>
            </div>

            <div class="flex items-baseline justify-between mb-4">
                <span class="text-xs font-bold text-slate-400">Balance:</span>
                <span class="font-athletic text-3xl font-black text-white">PHP {{ number_format($outstanding->amountDue(), 2) }}</span>
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
            <span class="text-[10px] text-center text-slate-500 block mt-2">Instant reactivation upon payment via PayMongo.</span>
        </div>
    @endif

    <!-- Activity Log (Exercise / Session List Style from Screenshot) -->
    <div class="space-y-3">
        <div class="flex items-center justify-between px-1">
            <span class="font-athletic text-lg font-bold uppercase tracking-wider text-white">RECENT ACTIVITY</span>
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Last 5 Scans</span>
        </div>

        @if ($checkIns->isEmpty())
            <div class="app-card rounded-2xl p-6 text-center text-xs text-slate-500">
                No check-in entries logged yet.
            </div>
        @else
            <div class="space-y-2">
                @foreach ($checkIns as $checkIn)
                    <div class="app-card rounded-2xl p-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-white/5 flex items-center justify-center flex-shrink-0">
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $checkIn->status->colorHex() }};"></span>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-white block">{{ $checkIn->scanned_at->format('M j, Y &bull; g:i A') }}</span>
                                <span class="text-[10px] text-slate-400 block">{{ $checkIn->subscription?->plan?->name ?? 'Front-desk scan' }}</span>
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
@endsection
