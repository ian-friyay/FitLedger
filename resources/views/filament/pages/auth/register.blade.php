<div class="w-full flex flex-col justify-between h-full space-y-6">

    <!-- Brand Header -->
    <div>
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/fitledger-logo.jpg') }}" alt="FitLedger Logo"
                class="h-10 w-auto rounded-xl object-contain bg-white/5 border border-white/10 shadow-md shadow-orange-500/20 flex-shrink-0">
            <div>
                <span class="font-athletic text-2xl font-black tracking-wider text-white uppercase block leading-none">
                    FIT<span class="text-[#FF7A00]">LEDGER</span>
                </span>
                <span class="text-[10px] uppercase font-bold tracking-widest text-[#FF7A00] block mt-0.5">
                    Gym Management OS
                </span>
            </div>
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

    <!-- Livewire Register Form -->
    <x-filament-panels::form id="form" wire:submit="register">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="true"
        />
    </x-filament-panels::form>

    <!-- Footer Links -->
    <div class="pt-2 text-center text-xs text-zinc-400">
        <span>Already have an account?</span>
        {{ $this->loginAction }}
    </div>

</div>
