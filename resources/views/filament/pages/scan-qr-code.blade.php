<x-filament-panels::page>
    <div class="space-y-6">

        <!-- Result Banner Alert -->
        @if ($lastResult)
            <div class="rounded-2xl p-6 shadow-xl border transition-all animate-in fade-in zoom-in-95 duration-200"
                style="
                    @if($lastResult['color'] === 'success') background-color: #052e16; border-color: #15803d; color: #bbf7d0;
                    @elseif($lastResult['color'] === 'warning') background-color: #451a03; border-color: #b45309; color: #fde68a;
                    @else background-color: #450a0a; border-color: #b91c1c; color: #fecaca;
                    @endif
                ">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="p-3 rounded-xl flex-shrink-0"
                            style="background-color: {{ $lastResult['color_hex'] }}30; color: {{ $lastResult['color_hex'] }};">
                            @if ($lastResult['color'] === 'success')
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            @elseif ($lastResult['color'] === 'warning')
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            @else
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            @endif
                        </div>

                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-black tracking-wide uppercase"
                                    style="background-color: {{ $lastResult['color_hex'] }}; color: #ffffff;">
                                    {{ $lastResult['title'] }}
                                </span>
                                <span class="text-xs opacity-75">{{ $lastResult['timestamp'] }}</span>
                            </div>

                            @if (!empty($lastResult['member']))
                                <h2 class="text-2xl font-black text-white mt-1">{{ $lastResult['member']['name'] }}</h2>
                                <p class="text-xs opacity-80 font-mono">{{ $lastResult['member']['phone'] }} &bull; {{ $lastResult['member']['qr_code'] }}</p>
                            @endif

                            <p class="text-sm font-semibold mt-2">{{ $lastResult['message'] }}</p>

                            @if (!empty($lastResult['subscription']))
                                <div class="mt-3 flex flex-wrap gap-3 text-xs">
                                    <span class="px-2.5 py-1 rounded-lg bg-black/30 border border-white/10">
                                        Plan: <strong>{{ $lastResult['subscription']['plan'] }}</strong>
                                    </span>
                                    <span class="px-2.5 py-1 rounded-lg bg-black/30 border border-white/10">
                                        Valid Until: <strong>{{ $lastResult['subscription']['ends_at'] }}</strong>
                                    </span>
                                    @if ($lastResult['subscription']['amount_due'] > 0)
                                        <span class="px-2.5 py-1 rounded-lg bg-rose-500/20 text-rose-300 font-bold border border-rose-500/40">
                                            Amount Due: PHP {{ number_format($lastResult['subscription']['amount_due'], 2) }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex sm:flex-col gap-2 flex-shrink-0">
                        @if (!empty($lastResult['member']))
                            <a href="{{ $lastResult['member']['pass_url'] }}" target="_blank"
                                class="px-4 py-2 rounded-xl text-xs font-bold bg-white/10 hover:bg-white/20 text-white transition text-center border border-white/20">
                                Open Digital Pass ↗
                            </a>
                        @endif
                        <button type="button" wire:click="clearResult"
                            class="px-4 py-2 rounded-xl text-xs font-bold bg-white text-gray-900 hover:bg-gray-100 transition shadow">
                            Scan Next Member
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Scanner & Input Section (2 Columns) -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Camera Scanner Panel -->
                <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-base font-bold text-gray-950 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Live Camera Scanner</span>
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                Point a member's QR code or digital mobile pass towards your webcam.
                            </p>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button" id="start-scan-btn"
                                onclick="startCamera()"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white transition flex items-center gap-1.5 shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                                <span>Start Camera</span>
                            </button>
                            <button type="button" id="stop-scan-btn"
                                onclick="stopCamera()"
                                style="display: none;"
                                class="px-3.5 py-1.5 rounded-xl text-xs font-bold bg-gray-700 hover:bg-gray-600 text-white transition">
                                Stop Camera
                            </button>
                        </div>
                    </div>

                    <!-- Viewfinder container -->
                    <div class="relative rounded-xl overflow-hidden bg-gray-950 min-h-[300px] flex items-center justify-center border border-gray-800">
                        <div id="reader" class="w-full"></div>
                        <div id="camera-idle-placeholder" class="text-center p-8 space-y-3">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-gray-800/80 border border-gray-700 flex items-center justify-center text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                </svg>
                            </div>
                            <div class="text-sm font-semibold text-gray-300">Camera is currently paused</div>
                            <p class="text-xs text-gray-500 max-w-sm mx-auto">
                                Click "Start Camera" above to enable live continuous scanning, or scan using a handheld USB barcode scanner below.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Handheld Gun / Manual Code Input -->
                <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
                    <h3 class="text-base font-bold text-gray-950 dark:text-white flex items-center gap-2 mb-1">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                        <span>Handheld Barcode Gun or Manual Code</span>
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                        USB barcode scanners automatically press Enter upon scanning. You can also paste a code or link directly.
                    </p>

                    <form wire:submit="processScan" class="flex gap-3">
                        <div class="relative flex-1">
                            <input 
                                type="text" 
                                wire:model="manualCode"
                                id="manual-code-input"
                                placeholder="Scan with USB gun, paste pass URL, or type FL-..." 
                                autofocus
                                class="w-full bg-gray-50 dark:bg-gray-950 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition font-mono"
                            >
                        </div>

                        <button 
                            type="submit"
                            class="px-5 py-3 rounded-xl font-bold text-sm bg-emerald-600 hover:bg-emerald-500 text-white shadow transition flex items-center gap-1.5 flex-shrink-0">
                            <span>Check In</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </form>
                </div>

            </div>

            <!-- Recent Check-Ins Today Feed (1 Column) -->
            <div class="space-y-4">
                <div class="rounded-2xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-gray-950 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Live Activity Feed</span>
                        </h3>
                        <a href="{{ url('/admin/' . Filament\Facades\Filament::getTenant()?->slug . '/check-ins') }}" 
                           class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline font-semibold">
                            Full Log &rarr;
                        </a>
                    </div>

                    @php $recents = $this->recentCheckIns; @endphp

                    @if ($recents->isEmpty())
                        <div class="text-center py-8 text-xs text-gray-500">
                            No check-in activity recorded yet for today.
                        </div>
                    @else
                        <div class="divide-y divide-gray-100 dark:divide-gray-800/80">
                            @foreach ($recents as $item)
                                <div class="py-3 flex items-start justify-between gap-3 text-xs">
                                    <div>
                                        <div class="font-bold text-gray-900 dark:text-white flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full" style="background-color: {{ $item->status->colorHex() }};"></span>
                                            <span>{{ $item->member?->name ?? 'Unknown Member' }}</span>
                                        </div>
                                        <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ $item->subscription?->plan?->name ?? 'No plan' }} &bull; {{ $item->scanned_at->format('g:i:s A') }}
                                        </div>
                                    </div>

                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase"
                                        style="background-color: {{ $item->status->colorHex() }}20; color: {{ $item->status->colorHex() }};">
                                        {{ $item->status->getLabel() }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Front-Desk Quick Tips -->
                <div class="rounded-2xl bg-gray-50 dark:bg-gray-900/40 border border-gray-200/80 dark:border-gray-800/80 p-5 text-xs text-gray-500 dark:text-gray-400 space-y-2">
                    <div class="font-bold text-gray-900 dark:text-gray-200">Front Desk Guide</div>
                    <ul class="space-y-1 list-disc list-inside">
                        <li><strong>Green (Active)</strong>: Member is allowed entry.</li>
                        <li><strong>Yellow (Due)</strong>: Collect payment or prompt for renewal.</li>
                        <li><strong>Red (Expired)</strong>: Deny entry until re-enrolled.</li>
                    </ul>
                </div>
            </div>

        </div>

    </div>

    <!-- Html5Qrcode Library Script -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        let html5QrCode = null;
        let isProcessing = false;

        function playBeep(success = true) {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.type = 'sine';
                osc.frequency.value = success ? 880 : 300; // 880Hz high beep or 300Hz low boop
                gain.gain.value = 0.15;
                osc.start();
                setTimeout(() => {
                    osc.stop();
                    ctx.close();
                }, success ? 140 : 250);
            } catch (e) {
                // AudioContext not allowed or not supported
            }
        }

        function startCamera() {
            const placeholder = document.getElementById('camera-idle-placeholder');
            const startBtn = document.getElementById('start-scan-btn');
            const stopBtn = document.getElementById('stop-scan-btn');

            if (placeholder) placeholder.style.display = 'none';
            if (startBtn) startBtn.style.display = 'none';
            if (stopBtn) stopBtn.style.display = 'inline-flex';

            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("reader");
            }

            html5QrCode.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0,
                },
                (decodedText) => {
                    if (isProcessing) return;
                    isProcessing = true;
                    playBeep(true);

                    // Call Livewire component method
                    @this.call('processScan', decodedText).then(() => {
                        // Debounce next scan by 2.5 seconds to prevent accidental duplicate scans
                        setTimeout(() => {
                            isProcessing = false;
                        }, 2500);
                    }).catch(() => {
                        isProcessing = false;
                    });
                },
                (errorMessage) => {
                    // Frame scan failed, expected for idle camera frames
                }
            ).catch(err => {
                alert("Unable to start camera: " + err);
                stopCamera();
            });
        }

        function stopCamera() {
            const placeholder = document.getElementById('camera-idle-placeholder');
            const startBtn = document.getElementById('start-scan-btn');
            const stopBtn = document.getElementById('stop-scan-btn');

            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    if (placeholder) placeholder.style.display = 'block';
                    if (startBtn) startBtn.style.display = 'inline-flex';
                    if (stopBtn) stopBtn.style.display = 'none';
                }).catch(() => {});
            }
        }
    </script>
</x-filament-panels::page>
