@extends('portal.layout')

@section('title', 'Find Your Member Pass - FitLedger')

@section('content')
<div class="space-y-6">

    <!-- Hero Banner (Inspired by Mobile Fitness Dashboard) -->
    <div class="relative rounded-3xl p-6 bg-gradient-to-b from-[#1a1e27] to-[#12151b] border border-white/5 overflow-hidden shadow-2xl">
        <!-- Radial glow accent -->
        <div class="absolute -top-12 -right-12 w-48 h-48 bg-[#FF5722]/15 rounded-full blur-3xl pointer-events-none"></div>

        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#FF5722]/20 border border-[#FF5722]/40 text-[#FF7A00] text-[11px] font-bold uppercase tracking-wider mb-4">
            <span class="w-2 h-2 rounded-full bg-[#FF5722] animate-pulse"></span>
            <span>Digital Gym Pass</span>
        </div>

        <h1 class="font-athletic text-4xl sm:text-5xl font-black uppercase tracking-tight text-white leading-none">
            YOUR PASS, <br><span class="text-[#FF7A00]">ZERO HASSLE.</span>
        </h1>
        <p class="text-xs text-slate-400 mt-2 font-medium leading-relaxed max-w-xs">
            Scan your phone at the front desk, view remaining subscription days, and pay dues with GCash.
        </p>

        <!-- Search Form -->
        <form action="{{ route('portal.find') }}" method="POST" class="mt-6 space-y-4">
            @csrf

            <div>
                <label for="query" class="block text-[11px] font-bold uppercase tracking-wider text-slate-300 mb-2">
                    Enter Phone Number, QR Code, or Email
                </label>
                <div class="relative">
                    <input 
                        type="text" 
                        name="query" 
                        id="query"
                        value="{{ old('query') }}"
                        placeholder="e.g. 09170000001 or FL-01..." 
                        required 
                        autofocus
                        class="w-full bg-[#0a0c10] border border-white/10 rounded-2xl pl-12 pr-4 py-3.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-[#FF5722] focus:ring-1 focus:ring-[#FF5722] transition shadow-inner font-medium"
                    >
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-500">
                        <svg class="w-5 h-5 text-[#FF7A00]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <button 
                type="submit" 
                class="w-full py-4 px-6 rounded-2xl font-athletic text-xl font-black uppercase tracking-wider text-white btn-orange transition flex items-center justify-center gap-2 transform active:scale-[0.98]"
            >
                <span>Find My Pass</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </form>

        <!-- Demo Pills for Fast Testing -->
        <div class="mt-5 pt-4 border-t border-white/5">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-2">Tap a test account:</span>
            <div class="flex flex-wrap gap-1.5 text-xs">
                <button type="button" onclick="document.getElementById('query').value = '09170000001'" 
                    class="px-3 py-1 rounded-xl bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white border border-white/5 transition text-[11px] font-semibold">
                    Paolo (Paid)
                </button>
                <button type="button" onclick="document.getElementById('query').value = '09170000002'" 
                    class="px-3 py-1 rounded-xl bg-white/5 hover:bg-white/10 text-amber-300 hover:text-white border border-amber-500/20 transition text-[11px] font-semibold">
                    Jasmine (Due)
                </button>
                <button type="button" onclick="document.getElementById('query').value = '09170000003'" 
                    class="px-3 py-1 rounded-xl bg-white/5 hover:bg-white/10 text-rose-300 hover:text-white border border-rose-500/20 transition text-[11px] font-semibold">
                    Karlo (Expired)
                </button>
            </div>
        </div>
    </div>

    <!-- Features Section (Fitness Stats Style Cards) -->
    <div class="space-y-3">
        <div class="flex items-center justify-between px-1">
            <span class="font-athletic text-lg font-bold uppercase tracking-wider text-white">SYSTEM FEATURES</span>
            <span class="text-[11px] font-bold text-[#FF7A00] uppercase tracking-wider">Fast & Paperless</span>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <!-- Stat Tile 1 -->
            <div class="app-card rounded-3xl p-4 flex flex-col justify-between">
                <div class="w-8 h-8 rounded-2xl bg-[#FF5722]/10 border border-[#FF5722]/20 text-[#FF7A00] flex items-center justify-center mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
                <div>
                    <span class="font-athletic text-2xl font-black text-white block leading-none">TOUCHLESS</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1 block">Live QR Entrance</span>
                </div>
            </div>

            <!-- Stat Tile 2 -->
            <div class="app-card rounded-3xl p-4 flex flex-col justify-between">
                <div class="w-8 h-8 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <span class="font-athletic text-2xl font-black text-white block leading-none">GCASH DUES</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1 block">Instant PayMongo</span>
                </div>
            </div>
        </div>

        <!-- Full-Width Capability Banner -->
        <div class="app-card rounded-3xl p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-[#1f242e] border border-white/5 flex items-center justify-center text-[#FF7A00]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-bold text-white block">Multi-Gym Management</span>
                    <span class="text-[11px] text-slate-400 block">Independent branch data & scanner keys</span>
                </div>
            </div>
            <span class="text-xs font-bold text-slate-500">&rarr;</span>
        </div>
    </div>

</div>
@endsection
