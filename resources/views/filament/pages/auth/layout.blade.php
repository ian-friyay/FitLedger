@php
    $livewire ??= null;
@endphp

<x-filament-panels::layout.base :livewire="$livewire">
    <div class="min-h-screen w-full flex items-center justify-center p-4 sm:p-6 lg:p-8"
        style="background-color: #0a0c10;">
        
        <!-- Main 2-Column Split Card (Based on User Reference Layout) -->
        <div class="max-w-5xl w-full rounded-3xl overflow-hidden shadow-2xl grid grid-cols-1 lg:grid-cols-12"
            style="background-color: #12151b; border: 1px solid rgba(255, 255, 255, 0.08); min-height: 640px;">
            
            <!-- Left Side: Interactive Auth Form Column -->
            <div class="lg:col-span-6 p-8 sm:p-12 flex flex-col justify-between relative z-10">
                {{ $slot }}
            </div>

            <!-- Right Side: Branded Gym Visual Showcase Column -->
            <div class="lg:col-span-6 relative hidden lg:flex flex-col justify-between p-10 overflow-hidden"
                style="background-color: #0d1017;">
                
                <!-- Gym Photo Background with Deep Moody Lighting -->
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 hover:scale-105"
                    style="background-image: url('{{ asset('images/gym-auth-bg.jpg') }}'); opacity: 0.40; filter: contrast(1.15) brightness(0.75);">
                </div>
                
                <!-- Dark & Orange Gradient Atmospheric Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-[#0a0c10] via-[#0a0c10]/60 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-[#12151b] via-transparent to-orange-600/10"></div>

                <!-- Top Floating System Badge -->
                <div class="relative z-10 flex items-center justify-between">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider text-orange-400 backdrop-blur-md bg-black/50 border border-orange-500/30 shadow-lg">
                        <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                        FitLedger Turnstile Engine
                    </span>
                    <span class="text-xs font-mono text-zinc-400">v2.5 Pro</span>
                </div>

                <!-- Center Graphic: Floating Turnstile Scanner Showcase Card (Image 1 Style) -->
                <div class="relative z-10 my-auto py-6">
                    <div class="rounded-2xl p-5 backdrop-blur-xl shadow-2xl border border-white/10 transform hover:-translate-y-1 transition-all duration-300"
                        style="background: rgba(18, 21, 27, 0.85); box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.7), 0 0 25px -5px rgba(255, 87, 34, 0.2);">
                        
                        <!-- Window header dots -->
                        <div class="flex items-center gap-1.5 pb-3 border-b border-white/10 mb-4">
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500/80"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></span>
                            <span class="text-[10px] font-mono text-zinc-400 ml-2">Scanner Station &bull; Verified Entry</span>
                        </div>

                        <!-- Card preview body -->
                        <div class="flex items-center gap-3.5">
                            <div class="w-13 h-13 rounded-2xl flex items-center justify-center font-athletic font-black text-2xl text-white shadow-lg flex-shrink-0"
                                style="width: 52px; height: 52px; background: linear-gradient(135deg, #FF6D00 0%, #FF3D00 100%);">
                                FL
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-bold text-sm text-white truncate">Member Access Granted</h4>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                        Active Pass
                                    </span>
                                </div>
                                <p class="text-xs text-zinc-300 mt-0.5">Adrian V. &bull; Monthly MMA Pass</p>
                                <p class="text-[10px] text-zinc-400 mt-1">Turnstile Check-In: <span class="text-white font-mono">Today, 07:45 AM</span></p>
                            </div>
                        </div>

                        <!-- Mini stats bar inside card -->
                        <div class="grid grid-cols-3 gap-2 mt-4 pt-3 border-t border-white/10 text-center text-[10px]">
                            <div>
                                <span class="text-zinc-500 block">Attendance</span>
                                <span class="font-bold text-white">24 Visits</span>
                            </div>
                            <div>
                                <span class="text-zinc-500 block">Streak</span>
                                <span class="font-bold text-orange-400">🔥 5 Days</span>
                            </div>
                            <div>
                                <span class="text-zinc-500 block">Status</span>
                                <span class="font-bold text-emerald-400">Green Entry</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Headline, Subtitle, & Carousel Dots -->
                <div class="relative z-10 space-y-2">
                    <h2 class="font-athletic text-2xl sm:text-3xl font-black text-white uppercase tracking-tight leading-tight">
                        Connect with every gym operation.
                    </h2>
                    <p class="text-xs text-zinc-300 leading-relaxed max-w-md">
                        Everything you need in an all-in-one gym management engine: instant QR check-ins, class schedules, and automated dues tracking.
                    </p>

                    <!-- Carousel indicator dots (as shown in reference image) -->
                    <div class="flex items-center gap-2 pt-2">
                        <span class="w-4 h-1.5 rounded-full bg-orange-500"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-white/30"></span>
                        <span class="w-1.5 h-1.5 rounded-full bg-white/30"></span>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-filament-panels::layout.base>
