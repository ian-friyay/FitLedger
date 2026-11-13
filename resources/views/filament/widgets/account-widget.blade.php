@php
    $user = filament()->auth()->user();
    $tenant = filament()->getTenant();
    $userName = filament()->getUserName($user);
    $currentDateFormatted = now()->format('l, F j, Y');
    $contextLabel = $user?->is_platform_admin 
        ? 'Platform SuperAdmin' 
        : ($tenant ? $tenant->name : 'FitLedger Operations');
@endphp

<div class="fi-account-widget w-full text-center py-6 sm:py-8 my-2">
    <h1 class="font-athletic text-4xl sm:text-5xl lg:text-6xl font-black text-white uppercase tracking-wider leading-none">
        WELCOME, <span class="text-[#FF7A00]">{{ strtoupper($userName) }}</span>
    </h1>

    <div class="mt-3 flex flex-wrap items-center justify-center gap-x-3 gap-y-1.5 text-sm sm:text-base text-zinc-400 font-medium">
        <span class="text-white font-semibold">{{ $currentDateFormatted }}</span>
        <span class="text-zinc-600 hidden sm:inline">•</span>
        <span class="text-zinc-300 font-semibold">{{ $contextLabel }}</span>
        @if ($tenant && ! $user?->is_platform_admin)
            <span class="text-zinc-600 hidden sm:inline">•</span>
            @if ($tenant->isOpenNow())
                <span class="text-emerald-400 font-semibold inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    Open ({{ $tenant->getOperatingSchedule()['opening_time'] ?? '06:00' }} - {{ $tenant->getOperatingSchedule()['closing_time'] ?? '22:00' }})
                </span>
            @else
                <span class="text-zinc-500 inline-flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-zinc-600"></span>
                    Currently Closed
                </span>
            @endif

            @php
                $daysLeft = $tenant->subscriptionDaysLeft();
            @endphp
            @if ($daysLeft !== null)
                <span class="text-zinc-600 hidden sm:inline">•</span>
                <span class="{{ $daysLeft <= 5 ? 'text-amber-400 font-bold' : 'text-zinc-400' }}">
                    {{ $daysLeft }} days left
                </span>
            @endif
        @endif
    </div>
</div>
