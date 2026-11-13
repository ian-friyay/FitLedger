<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Register as BaseRegister;

class Register extends BaseRegister
{
    protected static string $layout = 'filament.pages.auth.layout';

    protected static string $view = 'filament.pages.auth.register';

    public function getHeading(): string
    {
        return 'Create an Account';
    }

    public function getSubHeading(): string
    {
        return 'Join FitLedger to start managing your gym facility.';
    }
}
