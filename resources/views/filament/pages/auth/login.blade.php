<div class="w-full flex flex-col justify-between h-full space-y-6">

    <!-- Back to Website Link -->
    <div>
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-zinc-400 hover:text-white transition group py-0.5">
            <svg class="w-4 h-4 text-[#FF7A00] transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Website</span>
        </a>
    </div>

    <!-- Brand Header -->
    <div>
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/fitledger-logo.jpg') }}" alt="FitLedger Logo"
                class="h-10 w-auto rounded-xl object-contain bg-white/5 border border-white/10 shadow-md shadow-orange-500/20 flex-shrink-0">
            <span class="font-athletic text-2xl font-black tracking-wider text-white uppercase block leading-none">
                FIT<span class="text-[#FF7A00]">LEDGER</span>
            </span>
        </div>

        <div class="mt-6">
            <h1 class="font-athletic text-3xl font-black text-white uppercase tracking-tight">
                {{ $this->getHeading() }}
            </h1>
            <p class="text-xs text-zinc-400 mt-1">
                {{ $this->getSubHeading() }}
            </p>
        </div>
    </div>

    @if (! $requiresOtp)
        <!-- STAGE 1: CREDENTIALS SIGN-IN -->

        <!-- Divider -->
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-white/10"></div>
            </div>
            <div class="relative flex justify-center text-[11px]">
                <span class="px-3 text-zinc-500 font-medium" style="background-color: #12151b;">or sign in with credentials</span>
            </div>
        </div>

        <!-- Credentials Livewire Form -->
        <x-filament-panels::form id="form" wire:submit="authenticate">
            {{ $this->form }}

            <x-filament-panels::form.actions
                :actions="$this->getCachedFormActions()"
                :full-width="true"
            />
        </x-filament-panels::form>

        <!-- Footer Navigation Link -->
        <div class="pt-2 text-center text-xs text-zinc-400">
            @if (filament()->hasRegistration())
                <span>Don't have an account?</span>
                <a href="{{ filament()->getRegistrationUrl() }}" class="text-[#FF7A00] hover:text-orange-400 font-bold ml-1 transition">
                    Create an account
                </a>
            @endif
        </div>

    @else
        <!-- STAGE 2: 2-FACTOR EMAIL OTP VERIFICATION -->
        <div class="space-y-6 animate-in fade-in duration-200">
            
            <!-- Dev / Demo Helper Banner -->
            @if ($devOtpHint)
                <div class="p-3 rounded-xl bg-orange-500/10 border border-orange-500/30 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-xs text-orange-400">
                        <span class="font-mono font-bold uppercase tracking-wider">Demo / Dev Code:</span>
                        <strong class="font-mono font-black text-sm text-white">{{ $devOtpHint }}</strong>
                    </div>
                    <button type="button" wire:click="$set('otp', '{{ $devOtpHint }}')"
                        class="text-[11px] font-bold text-orange-400 hover:text-white underline">
                        Auto-fill
                    </button>
                </div>
            @endif

            <form wire:submit="verifyOtp" class="space-y-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300 mb-2">
                        6-Digit Security Code <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" wire:model="otp" maxlength="6" autofocus
                        placeholder="123456"
                        class="w-full text-center text-3xl font-mono font-black tracking-[0.3em] py-3.5 px-4 rounded-xl bg-[#0a0c10] border border-white/10 text-white placeholder-zinc-700 focus:border-[#FF7A00] focus:ring-1 focus:ring-[#FF7A00] transition">
                    @error('otp')
                        <span class="text-xs text-rose-400 font-semibold block mt-1.5">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full py-3.5 rounded-xl font-athletic text-base font-black uppercase tracking-wider text-white shadow-xl transition-transform hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-2"
                    style="background: linear-gradient(135deg, #FF6D00 0%, #FF3D00 100%);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Verify & Continue</span>
                </button>
            </form>

            <div class="flex items-center justify-between pt-2 text-xs text-zinc-400">
                <button type="button" wire:click="resendOtp" class="text-zinc-300 hover:text-white underline font-medium">
                    Resend code
                </button>

                <button type="button" wire:click="cancelOtp" class="text-zinc-400 hover:text-zinc-200">
                    Use different account
                </button>
            </div>
        </div>
    @endif
</div>
