<?php

namespace App\Http\Controllers;

use App\Models\PlatformSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function index(): View
    {
        $settings = PlatformSetting::getLandingSettings();
        $plans = PlatformSetting::getPlans();
        $trialDays = (int) PlatformSetting::get('saas_trial_days', config('fitledger.saas.trial_days', 14));

        return view('landing.index', [
            'settings' => $settings,
            'plans' => $plans,
            'trialDays' => $trialDays,
        ]);
    }
}
