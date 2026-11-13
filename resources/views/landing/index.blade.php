<!DOCTYPE html>
<html lang="en" class="dark scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FitLedger — High-Performance Gym Management OS</title>
    <meta name="description" content="Instant QR turnstile passes, automated GCash dues tracking, and multi-tenant operations for combat sports & boutique fitness facilities.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,600;0,700;0,800;0,900;1,700&family=Outfit:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (Vite / CDN Fallback) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            orange: '#FF7A00',
                            flame: '#FF5722',
                            dark: '#0a0c10',
                            card: '#12151b',
                            cardAlt: '#161922',
                        }
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        athletic: ['Barlow Condensed', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #0a0c10;
            color: #f1f5f9;
            font-family: 'Outfit', sans-serif;
            overflow-x: hidden;
        }

        .font-athletic {
            font-family: 'Barlow Condensed', sans-serif;
            letter-spacing: 0.02em;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        ::selection {
            background-color: #FF5722;
            color: #ffffff;
        }

        .glow-orange {
            box-shadow: 0 0 50px -10px rgba(255, 122, 0, 0.25);
        }

        .hero-mesh {
            background-image: radial-gradient(circle at 50% 0%, rgba(255, 122, 0, 0.12) 0%, rgba(10, 12, 16, 0) 65%);
        }
    </style>
</head>
<body class="bg-[#0a0c10] text-slate-100 antialiased selection:bg-[#FF5722] selection:text-white min-h-screen flex flex-col justify-between">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-50 bg-[#0a0c10]/90 backdrop-blur-md border-b border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-4 group">
                <img src="{{ asset('images/fitledger-logo.jpg') }}" alt="FitLedger"
                    class="h-9 w-auto rounded-xl object-contain bg-white/5 border border-white/10 shadow-md shadow-orange-500/20 group-hover:scale-105 transition transform flex-shrink-0">
                <span class="font-athletic text-2xl font-black uppercase tracking-wider text-white block leading-none">
                    FIT<span class="text-[#FF7A00]">LEDGER</span>
                </span>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-300">
                <a href="#features" class="hover:text-white transition">Features</a>
                <a href="#how-it-works" class="hover:text-white transition">How It Works</a>
                @if ($settings['show_pricing'] ?? true)
                    <a href="#pricing" class="hover:text-white transition">Pricing</a>
                @endif
                @if ($settings['show_faq'] ?? true)
                    <a href="#faq" class="hover:text-white transition">FAQ</a>
                @endif
                <a href="{{ route('portal.lookup') }}" class="text-[#FF7A00] hover:text-orange-400 font-bold transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    Member Pass Portal
                </a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-3">
                <a href="{{ url('/admin/login') }}" class="text-xs sm:text-sm font-semibold text-slate-300 hover:text-white px-3 py-2 rounded-xl transition">
                    Sign In
                </a>
                <a href="{{ url('/admin/register') }}" class="px-4 sm:px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#FF6D00] to-[#FF3D00] hover:from-[#FF7D1A] hover:to-[#FF501A] text-white font-bold text-xs sm:text-sm shadow-lg shadow-orange-500/25 transition transform hover:-translate-y-0.5">
                    Register Gym
                </a>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        <!-- Hero Section -->
        <section class="relative pt-16 pb-24 lg:pt-28 lg:pb-32 hero-mesh overflow-hidden border-b border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center max-w-4xl mx-auto">
                    @if ($settings['show_announcement'] ?? true)
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-orange-500/10 border border-orange-500/30 text-orange-400 text-xs font-mono font-bold tracking-wide uppercase mb-8">
                            <span class="w-2 h-2 rounded-full bg-orange-400 animate-pulse"></span>
                            {{ $settings['announcement_text'] ?? 'Next-Gen Gym Management OS • Native GCash Dues & 2s QR Turnstile Check-In' }}
                        </div>
                    @endif

                    <h1 class="font-athletic text-4xl sm:text-6xl lg:text-7xl font-black text-white uppercase tracking-tight leading-[0.95]">
                        {{ $settings['hero_title'] ?? 'The High-Performance Operating System for Gyms & Combat Academies' }}
                    </h1>

                    <p class="mt-6 text-base sm:text-xl text-slate-400 max-w-3xl mx-auto font-normal leading-relaxed">
                        {{ $settings['hero_subtitle'] ?? 'Eliminate paper logbooks and bloated legacy software. FitLedger unites instant turnstile QR passes, automated GCash dues tracking, and real-time class check-ins into one fast, athletic platform.' }}
                    </p>

                    <!-- CTAs -->
                    <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ url($settings['primary_cta_url'] ?? '/admin/register') }}" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-gradient-to-r from-[#FF6D00] to-[#FF3D00] hover:from-[#FF7D1A] hover:to-[#FF501A] text-white font-bold text-base shadow-xl shadow-orange-500/30 transition transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <span>{{ $settings['primary_cta_text'] ?? 'Start 14-Day Free Trial' }}</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                        <a href="{{ url($settings['secondary_cta_url'] ?? '/portal') }}" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-[#12151b] hover:bg-[#181c24] border border-white/10 hover:border-white/20 text-white font-bold text-base transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            <span>{{ $settings['secondary_cta_text'] ?? 'Member Pass Portal' }}</span>
                        </a>
                    </div>

                    <p class="mt-4 text-xs font-mono text-slate-500 tracking-wider uppercase">
                        Zero setup fee • Instant activation • No hardware vendor lock-in
                    </p>
                </div>

                <!-- Showcase Preview Card -->
                <div class="mt-16 max-w-5xl mx-auto rounded-2xl border border-white/10 bg-[#12151b] p-3 sm:p-5 shadow-2xl overflow-hidden glow-orange">
                    <div class="rounded-xl bg-[#0a0c10] border border-white/5 p-6 sm:p-8 flex flex-col md:flex-row items-center justify-between gap-8">
                        <!-- Digital Member Pass Mockup -->
                        <div class="w-full md:w-1/2 bg-[#12151b] rounded-xl border border-white/10 p-5 shadow-lg">
                            <div class="flex items-center justify-between pb-3 border-b border-white/10">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-orange-500/20 text-orange-400 font-athletic font-black flex items-center justify-center text-sm border border-orange-500/30">FL</div>
                                    <span class="font-athletic font-bold uppercase text-white tracking-wide text-sm">Bakbakan MMA & Fitness</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">PASS ACTIVE</span>
                            </div>
                            <div class="py-4 text-center">
                                <div class="w-32 h-32 mx-auto bg-white rounded-lg p-2 shadow-inner flex items-center justify-center">
                                    <svg class="w-full h-full text-black" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M2 2h8v8H2V2zm2 2v4h4V4H4zm10-2h8v8h-8V2zm2 2v4h4V4h-4zM2 14h8v8H2v-8zm2 2v4h4v-4H4zm8 0h2v2h-2v-2zm4 0h2v2h-2v-2zm-2 2h2v2h-2v-2zm4 0h2v2h-2v-2zm2-2h2v2h-2v-2zm0 4h2v2h-2v-2zm-4 0h2v2h-2v-2zm-2 2h2v2h-2v-2zm-6-6h2v2H8v-2zm0 4h2v2H8v-2zm4-2h2v2h-2v-2z"/>
                                    </svg>
                                </div>
                                <div class="mt-3 font-athletic text-lg font-black uppercase text-white">Paolo Reyes</div>
                                <div class="text-xs text-slate-400 font-mono">Monthly Combat Unlimited • 24 Visits</div>
                            </div>
                            <div class="pt-3 border-t border-white/5 flex items-center justify-between text-xs font-mono text-slate-400">
                                <span>Streak: 🔥 5 Days</span>
                                <span class="text-emerald-400 font-bold">Valid: Dec 13, 2026</span>
                            </div>
                        </div>

                        <!-- Turnstile Kiosk Readout Simulation -->
                        <div class="w-full md:w-1/2 space-y-4">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-emerald-400 animate-ping"></span>
                                <span class="font-mono text-xs font-bold uppercase text-emerald-400 tracking-wider">Live Kiosk Scanner Active</span>
                            </div>
                            <h3 class="font-athletic text-2xl sm:text-3xl font-black text-white uppercase tracking-tight">
                                Sub-2-Second Turnstile Verification
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
                                Mount any front-desk tablet, counter webcam, or connect to turnstile gates. Member passes scan instantly in the browser with zero native app install required.
                            </p>
                            <div class="p-3.5 rounded-xl bg-black/40 border border-emerald-500/30 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div>
                                    <div class="font-athletic text-base font-bold text-white uppercase">Turnstile Unlocked • Entry Verified</div>
                                    <div class="text-[11px] font-mono text-slate-400">Paolo Reyes — Checked in at 07:45 AM today</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Metric Numbers Banner -->
        <section class="py-12 bg-[#0e1117] border-b border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 text-center">
                    <div class="p-4">
                        <div class="font-athletic text-4xl sm:text-5xl font-black text-[#FF7A00] uppercase tracking-tight">
                            {{ $settings['stat_1_value'] ?? '< 2s' }}
                        </div>
                        <div class="mt-1 text-xs sm:text-sm font-semibold uppercase tracking-wider text-slate-400">
                            {{ $settings['stat_1_label'] ?? 'Check-in Scan Speed' }}
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="font-athletic text-4xl sm:text-5xl font-black text-white uppercase tracking-tight">
                            {{ $settings['stat_2_value'] ?? '100%' }}
                        </div>
                        <div class="mt-1 text-xs sm:text-sm font-semibold uppercase tracking-wider text-slate-400">
                            {{ $settings['stat_2_label'] ?? 'Browser-Based Pass (No App Store)' }}
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="font-athletic text-4xl sm:text-5xl font-black text-emerald-400 uppercase tracking-tight">
                            {{ $settings['stat_3_value'] ?? '₱0' }}
                        </div>
                        <div class="mt-1 text-xs sm:text-sm font-semibold uppercase tracking-wider text-slate-400">
                            {{ $settings['stat_3_label'] ?? 'Payment Merchant Markup' }}
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="font-athletic text-4xl sm:text-5xl font-black text-[#FF7A00] uppercase tracking-tight">
                            {{ $settings['stat_4_value'] ?? '99.9%' }}
                        </div>
                        <div class="mt-1 text-xs sm:text-sm font-semibold uppercase tracking-wider text-slate-400">
                            {{ $settings['stat_4_label'] ?? 'Front Desk Kiosk Uptime' }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Grid -->
        <section id="features" class="py-20 lg:py-28 bg-[#0a0c10] border-b border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto">
                    <h2 class="font-athletic text-3xl sm:text-5xl font-black text-white uppercase tracking-tight leading-tight">
                        {{ $settings['features_heading'] ?? 'Engineered for Combat Sports & High-Throughput Facilities' }}
                    </h2>
                    <p class="mt-4 text-base sm:text-lg text-slate-400">
                        {{ $settings['features_subheading'] ?? 'Every tool your gym needs to operate at peak efficiency—without confusing clutter or per-member gouging.' }}
                    </p>
                </div>

                <div class="mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="p-8 rounded-2xl bg-[#12151b] border border-white/10 hover:border-orange-500/40 transition duration-200">
                        <div class="w-12 h-12 rounded-xl bg-orange-500/10 border border-orange-500/30 text-orange-400 flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </div>
                        <h3 class="font-athletic text-2xl font-bold uppercase text-white mb-2">Turnstile & Kiosk Scanner</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Full-screen front-desk scanner with color-coded access validation. Green for active passes, yellow for dues owed, and red for expired members.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="p-8 rounded-2xl bg-[#12151b] border border-white/10 hover:border-orange-500/40 transition duration-200">
                        <div class="w-12 h-12 rounded-xl bg-orange-500/10 border border-orange-500/30 text-orange-400 flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="font-athletic text-2xl font-bold uppercase text-white mb-2">Zero-App Digital Pass</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Members bookmark a fast mobile web pass on their phone. No App Store or Google Play account required. Instant QR display and live attendance streaks.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="p-8 rounded-2xl bg-[#12151b] border border-white/10 hover:border-orange-500/40 transition duration-200">
                        <div class="w-12 h-12 rounded-xl bg-orange-500/10 border border-orange-500/30 text-orange-400 flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <h3 class="font-athletic text-2xl font-bold uppercase text-white mb-2">Automated GCash Billing</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Direct GCash Express Send reference tracking and automated checkout webhooks. Instant receipt uploads and one-click manual verification queue.
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="p-8 rounded-2xl bg-[#12151b] border border-white/10 hover:border-orange-500/40 transition duration-200">
                        <div class="w-12 h-12 rounded-xl bg-orange-500/10 border border-orange-500/30 text-orange-400 flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <h3 class="font-athletic text-2xl font-bold uppercase text-white mb-2">Multi-Location Tenancy</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Own or operate more than one gym branch? Switch locations instantly from the sidebar switcher while keeping all billing, staff, and records isolated.
                        </p>
                    </div>

                    <!-- Feature 5 -->
                    <div class="p-8 rounded-2xl bg-[#12151b] border border-white/10 hover:border-orange-500/40 transition duration-200">
                        <div class="w-12 h-12 rounded-xl bg-orange-500/10 border border-orange-500/30 text-orange-400 flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="font-athletic text-2xl font-bold uppercase text-white mb-2">Class Rosters & Bookings</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Manage schedules for Muay Thai, BJJ, Boxing, and CrossFit. Cap attendee capacity, prevent overbooking, and check in students as they enter.
                        </p>
                    </div>

                    <!-- Feature 6 -->
                    <div class="p-8 rounded-2xl bg-[#12151b] border border-white/10 hover:border-orange-500/40 transition duration-200">
                        <div class="w-12 h-12 rounded-xl bg-orange-500/10 border border-orange-500/30 text-orange-400 flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <h3 class="font-athletic text-2xl font-bold uppercase text-white mb-2">Revenue & Peak Hour Analytics</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Discover peak facility usage times, outstanding uncollected dues, and monthly recurring cashflow with one-click exportable financial reports.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="py-20 lg:py-28 bg-[#0e1117] border-b border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto">
                    <span class="font-mono text-xs font-bold uppercase text-orange-400 tracking-wider">3-Step Onboarding</span>
                    <h2 class="mt-2 font-athletic text-3xl sm:text-5xl font-black text-white uppercase tracking-tight">
                        Up and Running in 5 Minutes
                    </h2>
                </div>

                <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="p-8 rounded-2xl bg-[#12151b] border border-white/10 relative">
                        <div class="font-athletic text-6xl font-black text-orange-500/20 mb-4">01</div>
                        <h3 class="font-athletic text-2xl font-bold uppercase text-white mb-2">Register Your Facility</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Sign up your gym with customized operating hours, membership plans, and your official brand logo.
                        </p>
                    </div>

                    <div class="p-8 rounded-2xl bg-[#12151b] border border-white/10 relative">
                        <div class="font-athletic text-6xl font-black text-orange-500/20 mb-4">02</div>
                        <h3 class="font-athletic text-2xl font-bold uppercase text-white mb-2">Mount The Kiosk Scanner</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Open the FitLedger scanner screen on any tablet, counter webcam, or phone at your entrance counter.
                        </p>
                    </div>

                    <div class="p-8 rounded-2xl bg-[#12151b] border border-white/10 relative">
                        <div class="font-athletic text-6xl font-black text-orange-500/20 mb-4">03</div>
                        <h3 class="font-athletic text-2xl font-bold uppercase text-white mb-2">Issue Digital Passes</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Members receive their personal pass link. They scan at the counter and renew dues directly via GCash.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Dynamic Pricing Section -->
        @if ($settings['show_pricing'] ?? true)
            <section id="pricing" class="py-20 lg:py-28 bg-[#0a0c10] border-b border-white/5">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-3xl mx-auto">
                        <h2 class="font-athletic text-3xl sm:text-5xl font-black text-white uppercase tracking-tight">
                            {{ $settings['pricing_heading'] ?? 'Simple, Transparent Platform Pricing' }}
                        </h2>
                        <p class="mt-4 text-base sm:text-lg text-slate-400">
                            {{ $settings['pricing_subheading'] ?? 'One flat fee for your facility. 100% feature access, unlimited members, and zero hidden add-ons.' }}
                        </p>
                    </div>

                    <div class="mt-16 grid grid-cols-1 md:grid-cols-2 max-w-4xl mx-auto gap-8">
                        <!-- Monthly Plan -->
                        <div class="p-8 rounded-2xl bg-[#12151b] border border-white/10 flex flex-col justify-between">
                            <div>
                                <div class="inline-block px-3 py-1 rounded-full text-xs font-mono font-bold bg-white/10 text-slate-300 uppercase tracking-wider mb-4">
                                    {{ $plans['monthly']['badge'] ?? 'Most Flexible' }}
                                </div>
                                <h3 class="font-athletic text-3xl font-black uppercase text-white">Monthly All-Access Pass</h3>
                                <p class="mt-2 text-sm text-slate-400">Complete, unrestricted access to all FitLedger features for 30 days.</p>
                                
                                <div class="mt-6 flex items-baseline gap-2">
                                    <span class="font-athletic text-5xl font-black text-white">₱{{ number_format($plans['monthly']['price'] ?? 1499) }}</span>
                                    <span class="text-slate-400 font-mono text-sm">/ 30 days</span>
                                </div>

                                <ul class="mt-8 space-y-3 text-sm text-slate-300">
                                    <li class="flex items-center gap-2.5">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Unlimited Members & Digital Passes
                                    </li>
                                    <li class="flex items-center gap-2.5">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Full Turnstile & Front-Desk QR Scanner
                                    </li>
                                    <li class="flex items-center gap-2.5">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Automated GCash Dues Reconciliation
                                    </li>
                                    <li class="flex items-center gap-2.5">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Combat Sports Class Scheduling & Rosters
                                    </li>
                                </ul>
                            </div>

                            <a href="{{ url('/admin/register') }}" class="mt-8 block text-center py-3.5 px-6 rounded-xl bg-white/10 hover:bg-white/15 text-white font-bold text-sm transition">
                                Start 14-Day Free Trial
                            </a>
                        </div>

                        <!-- Annual Plan -->
                        <div class="p-8 rounded-2xl bg-gradient-to-b from-[#181c24] to-[#12151b] border-2 border-[#FF7A00] relative flex flex-col justify-between shadow-2xl">
                            <div class="absolute -top-3.5 right-6 px-3.5 py-1 rounded-full text-xs font-mono font-bold bg-[#FF7A00] text-black uppercase tracking-wider shadow">
                                {{ $plans['annual']['badge'] ?? 'Best Value (Annual)' }}
                            </div>

                            <div>
                                <div class="inline-block px-3 py-1 rounded-full text-xs font-mono font-bold bg-orange-500/20 text-orange-400 uppercase tracking-wider mb-4">
                                    Save Over 15%
                                </div>
                                <h3 class="font-athletic text-3xl font-black uppercase text-white">Annual All-Access Pass</h3>
                                <p class="mt-2 text-sm text-slate-400">Complete, unrestricted access to all FitLedger features for 365 days.</p>
                                
                                <div class="mt-6 flex items-baseline gap-2">
                                    <span class="font-athletic text-5xl font-black text-[#FF7A00]">₱{{ number_format($plans['annual']['price'] ?? 14999) }}</span>
                                    <span class="text-slate-400 font-mono text-sm">/ 365 days</span>
                                </div>

                                <ul class="mt-8 space-y-3 text-sm text-slate-300">
                                    <li class="flex items-center gap-2.5">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Everything in Monthly Pass
                                    </li>
                                    <li class="flex items-center gap-2.5">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Priority Customer Support & Setup Assistance
                                    </li>
                                    <li class="flex items-center gap-2.5">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Multi-Location Management Readiness
                                    </li>
                                    <li class="flex items-center gap-2.5">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Guaranteed Pricing Protection
                                    </li>
                                </ul>
                            </div>

                            <a href="{{ url('/admin/register') }}" class="mt-8 block text-center py-3.5 px-6 rounded-xl bg-gradient-to-r from-[#FF6D00] to-[#FF3D00] hover:from-[#FF7D1A] hover:to-[#FF501A] text-white font-bold text-sm shadow-lg shadow-orange-500/30 transition">
                                Claim Annual Pass
                            </a>
                        </div>
                    </div>

                    <div class="mt-8 text-center text-xs font-mono text-slate-500">
                        {{ $settings['pricing_guarantee'] ?? '14 days free trial upon registration. No credit card required. Cancel anytime.' }}
                    </div>
                </div>
            </section>
        @endif

        <!-- FAQ Section -->
        @if ($settings['show_faq'] ?? true)
            <section id="faq" class="py-20 lg:py-28 bg-[#0e1117] border-b border-white/5">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <span class="font-mono text-xs font-bold uppercase text-orange-400 tracking-wider">Got Questions?</span>
                        <h2 class="mt-2 font-athletic text-3xl sm:text-5xl font-black text-white uppercase tracking-tight">
                            Frequently Asked Questions
                        </h2>
                    </div>

                    <div class="space-y-6">
                        <div class="p-6 rounded-2xl bg-[#12151b] border border-white/10">
                            <h4 class="font-athletic text-xl font-bold uppercase text-white mb-2">Do our members need to download an app from an app store?</h4>
                            <p class="text-sm text-slate-400 leading-relaxed">
                                No. FitLedger is 100% web-based. Members access their personal digital pass by opening their unique link in Safari, Chrome, or any mobile browser. They can add it to their home screen with one tap.
                            </p>
                        </div>

                        <div class="p-6 rounded-2xl bg-[#12151b] border border-white/10">
                            <h4 class="font-athletic text-xl font-bold uppercase text-white mb-2">What hardware is required for the front-desk scanner?</h4>
                            <p class="text-sm text-slate-400 leading-relaxed">
                                FitLedger works with any device that has a camera or browser: an iPad or Android tablet at the reception counter, a laptop webcam, an attendant's smartphone, or dedicated barcode/turnstile hardware via the scanner API key.
                            </p>
                        </div>

                        <div class="p-6 rounded-2xl bg-[#12151b] border border-white/10">
                            <h4 class="font-athletic text-xl font-bold uppercase text-white mb-2">How does GCash payment processing work?</h4>
                            <p class="text-sm text-slate-400 leading-relaxed">
                                FitLedger supports both manual GCash Express Send tracking (where members submit their 13-digit reference number and receipt for quick staff confirmation) and automated payment gateway checkouts.
                            </p>
                        </div>

                        <div class="p-6 rounded-2xl bg-[#12151b] border border-white/10">
                            <h4 class="font-athletic text-xl font-bold uppercase text-white mb-2">Can I manage multiple gym locations?</h4>
                            <p class="text-sm text-slate-400 leading-relaxed">
                                Yes. FitLedger is built with multi-tenancy from the ground up. You can operate multiple gyms or branches from a single account and seamlessly switch between them from the sidebar.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <!-- Final CTA Banner -->
        <section class="py-20 bg-gradient-to-r from-[#12151b] via-[#1a1715] to-[#12151b] border-b border-white/5 relative overflow-hidden">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                <h2 class="font-athletic text-4xl sm:text-6xl font-black text-white uppercase tracking-tight">
                    Ready to Power Your Gym with FitLedger?
                </h2>
                <p class="mt-4 text-base sm:text-lg text-slate-400 max-w-2xl mx-auto">
                    Start your 14-day free trial today. Set up your classes, customize your branded pass, and experience frictionless front-desk check-ins.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ url('/admin/register') }}" class="px-8 py-4 rounded-xl bg-gradient-to-r from-[#FF6D00] to-[#FF3D00] hover:from-[#FF7D1A] hover:to-[#FF501A] text-white font-bold text-base shadow-xl shadow-orange-500/30 transition transform hover:-translate-y-0.5">
                        Register Your Gym Now
                    </a>
                    <a href="{{ route('portal.lookup') }}" class="px-8 py-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-white font-bold text-base transition">
                        Find Member Digital Pass
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-[#0a0c10] border-t border-white/5 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/fitledger-logo.jpg') }}" alt="FitLedger"
                    class="h-8 w-auto rounded-xl object-contain bg-white/5 border border-white/10">
                <span class="font-athletic text-xl font-black uppercase tracking-wider text-white">
                    FIT<span class="text-[#FF7A00]">LEDGER</span>
                </span>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-6 text-xs text-slate-400 font-medium">
                <span>{{ $settings['contact_address'] ?? 'BGC, Taguig City, Metro Manila, Philippines' }}</span>
                <span>•</span>
                <a href="mailto:{{ $settings['contact_email'] ?? 'support@fitledger.test' }}" class="hover:text-white transition">
                    {{ $settings['contact_email'] ?? 'support@fitledger.test' }}
                </a>
                <span>•</span>
                <span>{{ $settings['contact_phone'] ?? '+63 917 888 9999' }}</span>
            </div>

            <div class="text-xs text-slate-500 font-mono text-center md:text-right">
                {{ $settings['copyright_text'] ?? ('© '.date('Y').' FitLedger. All rights reserved.') }}
            </div>
        </div>
    </footer>

</body>
</html>
