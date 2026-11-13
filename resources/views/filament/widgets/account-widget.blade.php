@php
    $user = filament()->auth()->user();
    $tenant = filament()->getTenant();
    $avatarUrl = $user?->getFilamentAvatarUrl();
    $userInitials = $user ? strtoupper(substr(trim($user->name), 0, 2)) : 'FL';
    $currentDay = now()->format('l');
    $currentDate = now()->format('F j, Y');
@endphp

<div class="fi-account-widget relative overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-r from-[#12151b] via-[#161a23] to-[#0e1117] p-6 shadow-2xl">
    <!-- Ambient orange glow background decoration -->
    <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-[#FF7A00]/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -left-16 -bottom-16 h-48 w-48 rounded-full bg-blue-500/5 blur-3xl"></div>

    <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
        <!-- Left: User Avatar & Big Welcome -->
        <div class="flex items-center gap-5 sm:gap-6">
            <!-- Large Profile Avatar -->
            <div class="relative shrink-0">
                <div class="h-20 w-20 sm:h-24 sm:w-24 rounded-2xl overflow-hidden ring-2 ring-[#FF7A00]/40 shadow-xl shadow-orange-500/10 bg-[#0a0c10] flex items-center justify-center">
                    @if ($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                    @else
                        <div class="h-full w-full flex items-center justify-center bg-gradient-to-br from-orange-500/20 to-zinc-900 border border-orange-500/30 text-orange-400 font-athletic text-3xl font-black tracking-wider">
                            {{ $userInitials }}
                        </div>
                    @endif
                </div>
                <!-- Online Pulse Dot -->
                <div class="absolute -bottom-1 -right-1 flex h-6 w-6 items-center justify-center rounded-full bg-[#12151b] ring-2 ring-[#12151b]">
                    <span class="relative flex h-3.5 w-3.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500"></span>
                    </span>
                </div>
            </div>

            <!-- Welcome Text & Account Name -->
            <div class="space-y-1">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="font-mono text-xs font-bold uppercase tracking-widest text-[#FF7A00]">
                        WELCOME BACK
                    </span>
                    <span class="text-zinc-600">•</span>
                    @if ($user?->is_platform_admin)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-orange-500/15 text-orange-400 border border-orange-500/30 font-mono">
                            Platform SuperAdmin
                        </span>
                    @elseif ($tenant)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-zinc-800 text-zinc-300 border border-white/10 font-mono">
                            {{ $tenant->name }}
                        </span>
                    @endif
                </div>

                <h1 class="font-athletic text-3xl sm:text-4xl lg:text-5xl font-black text-white uppercase tracking-tight leading-none">
                    {{ filament()->getUserName($user) }}
                </h1>

                <p class="text-xs sm:text-sm text-zinc-400 font-medium pt-0.5">
                    @if ($user?->is_platform_admin)
                        Platform operations & global gym network management dashboard.
                    @elseif ($tenant)
                        @if ($tenant->isOpenNow())
                            <span class="text-emerald-400 font-semibold inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                Gym is currently Open
                            </span>
                            <span class="text-zinc-500">({{ $tenant->getOperatingSchedule()['opening_time'] ?? '06:00' }} - {{ $tenant->getOperatingSchedule()['closing_time'] ?? '22:00' }})</span>
                        @else
                            <span class="text-zinc-400 inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-zinc-600"></span>
                                Gym currently Closed
                            </span>
                            <span class="text-zinc-500">(Standard hours: {{ $tenant->getOperatingSchedule()['opening_time'] ?? '06:00' }} - {{ $tenant->getOperatingSchedule()['closing_time'] ?? '22:00' }})</span>
                        @endif
                    @else
                        Welcome to your FitLedger dashboard.
                    @endif
                </p>
            </div>
        </div>

        <!-- Right: Athletic Date & Calendar Panel -->
        <div class="flex items-center gap-4 sm:gap-6 self-start lg:self-center border-t lg:border-t-0 lg:border-l border-white/10 pt-4 lg:pt-0 lg:pl-8">
            <div class="flex items-center gap-3.5 bg-black/30 border border-white/10 rounded-2xl px-5 py-3.5 shadow-inner">
                <div class="flex flex-col items-center justify-center w-12 h-12 rounded-xl bg-orange-500/10 border border-orange-500/30 text-orange-400 shrink-0">
                    <span class="font-mono text-[10px] uppercase font-bold tracking-wider leading-none text-orange-400/80">DAY</span>
                    <span class="font-athletic text-xl font-black leading-none mt-0.5">{{ now()->format('d') }}</span>
                </div>
                <div>
                    <div class="font-athletic text-2xl font-black text-white uppercase tracking-wide leading-none">
                        {{ $currentDay }}
                    </div>
                    <div class="text-xs text-zinc-400 font-medium font-mono pt-1">
                        {{ $currentDate }}
                    </div>
                </div>
            </div>

            @if ($tenant && ! $user?->is_platform_admin)
                @php
                    $daysLeft = $tenant->subscriptionDaysLeft();
                @endphp
                <div class="hidden sm:flex flex-col items-end bg-black/20 border border-white/5 rounded-2xl px-4 py-3">
                    <span class="text-[10px] font-mono uppercase text-zinc-500 font-bold tracking-wider">Access Status</span>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="font-athletic text-lg font-black {{ ($daysLeft !== null && $daysLeft <= 5) ? 'text-amber-400' : 'text-emerald-400' }}">
                            {{ $daysLeft !== null ? $daysLeft . ' DAYS' : 'ACTIVE' }}
                        </span>
                        <span class="text-[11px] text-zinc-400 font-mono">LEFT</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
