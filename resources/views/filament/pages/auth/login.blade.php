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

    <!-- Quick Demo Role Switcher (Clean, Uncrowded Pills) -->
    <div class="p-2.5 rounded-2xl bg-white/[0.03] border border-white/5 space-y-2">
        <div class="flex items-center justify-between">
            <span class="text-[10px] font-bold uppercase tracking-wider text-orange-400 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                1-Click Demo Login:
            </span>
            <a href="{{ url('/portal') }}" class="text-[10px] text-zinc-400 hover:text-white transition-colors flex items-center gap-1">
                Member Pass Portal &rarr;
            </a>
        </div>

        <div class="grid grid-cols-2 gap-2">
            <button
                type="button"
                onclick="fillLoginCredentials('admin@fitledger.test', 'password')"
                class="flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl text-xs font-bold text-white bg-white/5 hover:bg-orange-500/20 border border-white/10 hover:border-orange-500/40 transition-all text-center"
            >
                <span>👑</span>
                <span>Platform Admin</span>
            </button>

            <button
                type="button"
                onclick="fillLoginCredentials('owner@fitledger.test', 'password')"
                class="flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl text-xs font-bold text-white bg-white/5 hover:bg-orange-500/20 border border-white/10 hover:border-orange-500/40 transition-all text-center"
            >
                <span>🥊</span>
                <span>Gym Owner</span>
            </button>
        </div>
    </div>

    <!-- Divider -->
    <div class="relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-white/10"></div>
        </div>
        <div class="relative flex justify-center text-[11px]">
            <span class="px-3 text-zinc-500 font-medium" style="background-color: #12151b;">or sign in with credentials</span>
        </div>
    </div>

    <!-- Livewire Auth Form -->
    <x-filament-panels::form id="form" wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="true"
        />
    </x-filament-panels::form>

    <!-- Footer Links -->
    <div class="pt-2 text-center text-xs text-zinc-400">
        @if (filament()->hasRegistration())
            <span>Don't have an account?</span>
            {{ $this->registerAction }}
        @endif
    </div>

</div>

<script>
    function fillLoginCredentials(email, password) {
        const emailInput = document.querySelector('input[type="email"]') || document.querySelector('input[name*="email"]') || document.getElementById('data.email');
        const passInput = document.querySelector('input[type="password"]') || document.querySelector('input[name*="password"]') || document.getElementById('data.password');

        if (emailInput) {
            emailInput.value = email;
            emailInput.dispatchEvent(new Event('input', { bubbles: true }));
            emailInput.dispatchEvent(new Event('change', { bubbles: true }));
        }

        if (passInput) {
            passInput.value = password;
            passInput.dispatchEvent(new Event('input', { bubbles: true }));
            passInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }
</script>
