@php
    $livewire ??= null;
@endphp

<x-filament-panels::layout.base :livewire="$livewire">
    <div class="auth-split-wrapper">
        <div class="auth-split-card">
            
            <!-- Left Side: Interactive Credentials Form -->
            <div class="auth-form-column">
                {{ $slot }}
            </div>

            <!-- Right Side: Pure Gym Background Image (No Customer Pass / Ticket Interface) -->
            <div class="auth-image-column" style="background-image: url('{{ asset('images/gym-auth-bg.jpg') }}');">
                <div class="auth-image-overlay"></div>
            </div>

        </div>
    </div>
</x-filament-panels::layout.base>
