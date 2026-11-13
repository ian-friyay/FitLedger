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

    <!-- Quick Demo Role Dropdown (Clean & Uncrowded) -->
    <div class="flex items-center justify-between p-2.5 rounded-xl bg-white/[0.03] border border-white/5">
        <span class="text-xs text-zinc-400 font-medium flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            Quick Demo Role:
        </span>
        <select onchange="if(this.value){ const [e, p] = this.value.split('|'); fillLoginCredentials(e, p); this.selectedIndex = 0; }"
            class="bg-[#0a0c10] border border-white/10 text-zinc-300 text-xs rounded-lg px-2.5 py-1.5 focus:border-orange-500 focus:ring-0 cursor-pointer">
            <option value="">Select demo account...</option>
            <option value="admin@fitledger.test|password">👑 Platform Admin</option>
            <option value="owner@fitledger.test|password">🥊 Gym Owner</option>
        </select>
    </div>

    <!-- Divider -->
    <div class="relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-white/10"></div>
        </div>
        <div class="relative flex justify-center text-[11px]">
            <span class="px-3 text-zinc-500 font-medium" style="background-color: #12151b;">or continue with email</span>
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
