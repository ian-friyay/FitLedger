<div class="mb-6 rounded-2xl border border-zinc-800 bg-zinc-900/90 p-4 shadow-xl backdrop-blur-sm">
    <div class="flex items-center justify-between mb-3">
        <span class="text-xs font-bold uppercase tracking-wider text-orange-500 flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            Quick Demo Login
        </span>
        <span class="text-[10px] text-zinc-500">1-Click Auto Fill</span>
    </div>

    <div class="grid grid-cols-2 gap-2 mb-3">
        <button
            type="button"
            onclick="fillLoginCredentials('admin@fitledger.test', 'password')"
            class="group flex flex-col items-start p-2.5 rounded-xl bg-zinc-950/80 border border-zinc-800 hover:border-orange-500/50 hover:bg-zinc-800/60 transition-all text-left"
        >
            <div class="flex items-center gap-1.5 w-full">
                <span class="text-xs font-bold text-white group-hover:text-orange-400">Platform Admin</span>
                <span class="text-[10px] px-1.5 py-0.2 rounded bg-purple-500/20 text-purple-300 font-mono ml-auto">Super</span>
            </div>
            <span class="text-[10px] text-zinc-400 font-mono truncate w-full mt-0.5">admin@fitledger.test</span>
        </button>

        <button
            type="button"
            onclick="fillLoginCredentials('owner@fitledger.test', 'password')"
            class="group flex flex-col items-start p-2.5 rounded-xl bg-zinc-950/80 border border-zinc-800 hover:border-orange-500/50 hover:bg-zinc-800/60 transition-all text-left"
        >
            <div class="flex items-center gap-1.5 w-full">
                <span class="text-xs font-bold text-white group-hover:text-orange-400">Gym Owner</span>
                <span class="text-[10px] px-1.5 py-0.2 rounded bg-emerald-500/20 text-emerald-300 font-mono ml-auto">Tenant</span>
            </div>
            <span class="text-[10px] text-zinc-400 font-mono truncate w-full mt-0.5">owner@fitledger.test</span>
        </button>
    </div>

    <!-- Quick Link to Member Status Portal -->
    <a
        href="{{ url('/portal') }}"
        class="w-full py-2 px-3 rounded-xl bg-zinc-950/60 border border-dashed border-zinc-800 hover:border-zinc-600 text-zinc-300 hover:text-white flex items-center justify-between text-xs transition-colors"
    >
        <span class="flex items-center gap-2">
            <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            <strong>Looking for Member Status?</strong>
        </span>
        <span class="text-[11px] text-orange-400 font-semibold flex items-center gap-1">
            Open Portal &rarr;
        </span>
    </a>
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
