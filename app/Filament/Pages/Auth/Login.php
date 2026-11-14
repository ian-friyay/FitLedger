<?php

namespace App\Filament\Pages\Auth;

use App\Mail\SendOtpCodeMail;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class Login extends BaseLogin
{
    protected static string $layout = 'filament.pages.auth.layout';

    protected static string $view = 'filament.pages.auth.login';

    public bool $requiresOtp = false;

    public string $otp = '';

    public ?int $pendingUserId = null;

    public ?string $pendingUserEmail = null;

    public bool $rememberMe = false;

    public ?string $devOtpHint = null;

    public function getHeading(): string
    {
        return $this->requiresOtp ? 'Two-Factor Authentication' : 'Log in to your Account';
    }

    public function getSubHeading(): string
    {
        return $this->requiresOtp 
            ? "Enter the 6-digit code sent to {$this->pendingUserEmail}" 
            : 'Welcome back! Enter your credentials.';
    }

    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();

        /** @var User|null $user */
        $user = User::query()->where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            $this->throwFailureValidationException();
        }

        if (! $user->is_active) {
            Notification::make()
                ->title('Account is deactivated')
                ->body('Please contact your administrator for assistance.')
                ->danger()
                ->send();

            return null;
        }

        // Generate 6-digit OTP code
        $otpCode = (string) random_int(100000, 999999);
        Cache::put('auth_otp_' . $user->id, $otpCode, now()->addMinutes(10));

        // Dispatch OTP Email
        try {
            Mail::to($user->email)->send(new SendOtpCodeMail($otpCode, $user->name, 'Sign In'));
        } catch (\Throwable $e) {
            \Log::warning('OTP email sending failed: ' . $e->getMessage());
        }

        $this->requiresOtp = true;
        $this->pendingUserId = $user->id;
        $this->pendingUserEmail = $user->email;
        $this->rememberMe = (bool) ($data['remember'] ?? false);
        if (app()->environment(['local', 'testing'])) {
            $this->devOtpHint = $otpCode;
        }

        Notification::make()
            ->title('Verification code sent')
            ->body("We've sent a 6-digit code to {$user->email}")
            ->success()
            ->send();

        return null;
    }

    public function verifyOtp(): ?LoginResponse
    {
        $this->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $cachedCode = Cache::get('auth_otp_' . $this->pendingUserId);

        if (! $cachedCode || $cachedCode !== trim($this->otp)) {
            $this->addError('otp', 'The verification code is invalid or has expired.');
            return null;
        }

        Cache::forget('auth_otp_' . $this->pendingUserId);

        Filament::auth()->loginUsingId($this->pendingUserId, $this->rememberMe);
        session()->regenerate();

        return app(LoginResponse::class);
    }

    public function resendOtp(): void
    {
        if (! $this->pendingUserId) {
            return;
        }

        $user = User::find($this->pendingUserId);
        if (! $user) {
            return;
        }

        $otpCode = (string) random_int(100000, 999999);
        Cache::put('auth_otp_' . $user->id, $otpCode, now()->addMinutes(10));
        if (app()->environment(['local', 'testing'])) {
            $this->devOtpHint = $otpCode;
        }

        try {
            Mail::to($user->email)->send(new SendOtpCodeMail($otpCode, $user->name, 'Sign In'));
        } catch (\Throwable $e) {
            \Log::warning('Resend OTP email failed: ' . $e->getMessage());
        }

        Notification::make()
            ->title('Fresh code sent')
            ->body("A new verification code has been dispatched to {$user->email}")
            ->success()
            ->send();
    }

    public function cancelOtp(): void
    {
        $this->requiresOtp = false;
        $this->otp = '';
        $this->pendingUserId = null;
        $this->pendingUserEmail = null;
        $this->devOtpHint = null;
    }
}
