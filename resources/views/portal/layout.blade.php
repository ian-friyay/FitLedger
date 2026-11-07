<!DOCTYPE html>
<html lang="en" class="h-full bg-[#0a0c10] text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'FitLedger Member Pass')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,600;0,700;0,800;0,900;1,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-athletic { font-family: 'Barlow Condensed', sans-serif; letter-spacing: -0.02em; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0a0c10; }
        ::-webkit-scrollbar-thumb { background: #1f242d; border-radius: 9999px; }

        .app-card {
            background-color: #13161c;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        .app-card-nested {
            background-color: #1a1e26;
            border: 1px solid rgba(255, 255, 255, 0.04);
        }
        .orange-glow {
            box-shadow: 0 10px 25px -5px rgba(255, 87, 34, 0.35);
        }
        .btn-orange {
            background: linear-gradient(135deg, #FF6D00 0%, #FF3D00 100%);
            box-shadow: 0 8px 20px -4px rgba(255, 61, 0, 0.4);
        }
        .btn-orange:hover {
            background: linear-gradient(135deg, #FF7D1A 0%, #FF501A 100%);
        }
    </style>
</head>
<body class="min-h-full flex flex-col bg-[#0a0c10] text-slate-100 antialiased selection:bg-[#FF5722] selection:text-white pb-24">

    <!-- Top Status / Brand Bar -->
    <header class="sticky top-0 z-40 bg-[#0a0c10]/90 backdrop-blur-md border-b border-white/5">
        <div class="max-w-md mx-auto px-5 h-16 flex items-center justify-between">
            <a href="{{ route('portal.lookup') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/fitledger-logo.jpg') }}" alt="FitLedger" class="h-9 w-auto object-contain rounded-xl shadow-md shadow-orange-500/20 group-hover:scale-105 transition transform">
                <div>
                    <span class="font-athletic text-2xl font-black uppercase tracking-tight text-white block leading-none">FITLEDGER</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-[#FF7A00] block">Member Portal</span>
                </div>
            </a>

            <div class="flex items-center gap-2">
                <a href="{{ route('portal.lookup') }}" class="w-9 h-9 rounded-2xl bg-[#161920] border border-white/5 flex items-center justify-center text-slate-400 hover:text-white transition" title="Search Pass">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </a>
                <a href="/admin" class="px-3 py-1.5 rounded-2xl bg-[#161920] border border-white/5 text-xs font-bold text-slate-300 hover:text-white transition flex items-center gap-1.5" title="Staff Portal">
                    <span>Staff</span>
                    <span class="text-[#FF7A00]">&rarr;</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Mobile Shell Container -->
    <main class="flex-1 max-w-md mx-auto w-full px-4 pt-5 pb-8">
        @if (session('error'))
            <div class="mb-4 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-300 text-xs flex items-center gap-3">
                <div class="w-7 h-7 rounded-xl bg-rose-500/20 flex items-center justify-center flex-shrink-0 text-rose-400 font-bold">!</div>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @if (session('info'))
            <div class="mb-4 p-4 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-300 text-xs flex items-center gap-3">
                <div class="w-7 h-7 rounded-xl bg-blue-500/20 flex items-center justify-center flex-shrink-0 text-blue-400 font-bold">i</div>
                <span class="font-medium">{{ session('info') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bottom Floating Navigation Bar (Mobile App Style) -->
    <nav class="fixed bottom-4 inset-x-0 z-50 pointer-events-none">
        <div class="max-w-xs mx-auto pointer-events-auto">
            <div class="bg-[#12151b]/95 backdrop-blur-xl border border-white/10 rounded-full px-5 py-2.5 shadow-2xl shadow-black/80 flex items-center justify-between">
                <!-- Home / Lookup -->
                <a href="{{ route('portal.lookup') }}" class="flex flex-col items-center gap-1 text-slate-400 hover:text-white transition group py-1">
                    <svg class="w-5 h-5 {{ request()->routeIs('portal.lookup') ? 'text-[#FF7A00]' : 'text-slate-400' }} group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="text-[9px] font-bold uppercase tracking-wider {{ request()->routeIs('portal.lookup') ? 'text-[#FF7A00]' : 'text-slate-500' }}">Find</span>
                </a>

                <!-- Floating Center Orange Action Button -->
                <a href="{{ route('portal.lookup') }}" class="w-11 h-11 -my-3 rounded-full btn-orange flex items-center justify-center text-white shadow-lg transition transform hover:scale-105 active:scale-95">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                </a>

                <!-- Staff / Admin Link -->
                <a href="/admin" class="flex flex-col items-center gap-1 text-slate-400 hover:text-white transition group py-1">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-500">Staff</span>
                </a>
            </div>
        </div>
    </nav>

</body>
</html>
