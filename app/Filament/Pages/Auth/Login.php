<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    protected static string $layout = 'filament.pages.auth.layout';

    protected static string $view = 'filament.pages.auth.login';

    public function getHeading(): string
    {
        return 'Log in to your Account';
    }

    public function getSubHeading(): string
    {
        return 'Welcome back! Select a demo role or enter your credentials.';
    }
}
