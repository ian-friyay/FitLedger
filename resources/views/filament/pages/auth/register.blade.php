<div class="w-full flex flex-col justify-between h-full space-y-6">

    <!-- Brand Header -->
    <div>
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-athletic font-black text-xl text-white shadow-lg"
                style="background: linear-gradient(135deg, #FF6D00 0%, #FF3D00 100%);">
                FL
            </div>
            <div>
                <span class="font-athletic text-2xl font-black tracking-wider text-white uppercase block leading-none">FitLedger</span>
                <span class="text-[10px] uppercase font-bold tracking-widest text-orange-500 block">Gym Management OS</span>
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
