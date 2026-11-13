<?php

namespace App\Http\Middleware;

use App\Models\Gym;
use Closure;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGymSubscriptionActive
{
    /**
     * Handle an incoming request.
     * Ensures the current gym tenant has an active trial or paid platform subscription.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Platform admins bypass all tenant billing restrictions
        if ($user?->is_platform_admin) {
            return $next($request);
        }

        $tenant = Filament::getTenant();

        if (! $tenant instanceof Gym) {
            return $next($request);
        }

        // Check if gym has active access (either active trial or active paid subscription)
        if ($tenant->hasActiveAccess()) {
            return $next($request);
        }

        // Allow access to the billing page, logout, and profile pages so they can renew
        if ($request->routeIs('filament.admin.pages.gym-billing') ||
            $request->routeIs('filament.admin.pages.billing') ||
            $request->is('*/billing*') ||
            $request->routeIs('filament.admin.auth.logout') ||
            $request->routeIs('filament.admin.tenant.profile') ||
            $request->routeIs('filament.admin.pages.dashboard') && $request->isMethod('head')
        ) {
            return $next($request);
        }

        // If trying to access any other gym resource while expired, redirect to billing page
        $billingUrl = url("/admin/{$tenant->slug}/billing");

        Notification::make()
            ->title('Subscription Required')
            ->body('Your FitLedger subscription or free trial has expired. Please select a plan to unlock full access.')
            ->danger()
            ->persistent()
            ->send();

        return redirect()->to($billingUrl);
    }
}
