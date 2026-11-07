<div class="flex flex-col items-center justify-center gap-3 p-4">
    <div class="rounded-xl bg-white p-3 ring-1 ring-gray-950/10 dark:ring-white/10 shadow-sm">
        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size($size ?? 220)
            ->margin(1)
            ->errorCorrection('H')
            ->backgroundColor(255, 255, 255)
            ->generate($member->passUrl()) !!}
    </div>

    <div class="text-center space-y-1">
        <div class="text-sm font-semibold text-gray-950 dark:text-white">{{ $member->name }}</div>
        <div class="font-mono text-xs text-gray-500">{{ $member->qr_code }}</div>
        <div class="pt-1">
            <a href="{{ $member->passUrl() }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 hover:text-emerald-500 dark:text-emerald-400 dark:hover:text-emerald-300">
                <span>Open Digital Pass</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
        </div>
    </div>
</div>
