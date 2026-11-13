<?php

namespace App\Filament\Pages\Auth;

use App\Mail\SendOtpCodeMail;
use App\Models\User;
use Filament\Events\Auth\Registered;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class Register extends BaseRegister
{
    protected static string $layout = 'filament.pages.auth.layout';

    protected static string $view = 'filament.pages.auth.register';

    public bool $requiresOtp = false;

    public string $otp = '';

    public ?array $pendingRegistrationData = null;

    public ?string $devOtpHint = null;

    public function getHeading(): string
    {
        return $this->requiresOtp ? 'Verify Your Email' : 'Create an Account';
    }

    public function getSubHeading(): string
    {
        return $this->requiresOtp 
            ? "Enter the 6-digit code sent to " . ($this->pendingRegistrationData['email'] ?? 'your email')
            : 'Join FitLedger to start managing your gym facility.';
    }

    public function register(): ?RegistrationResponse
    {
        $data = $this->form->getState();

        // Generate 6-digit OTP code
        $otpCode = (string) random_int(100000, 999999);
        $cacheKey = 'reg_otp_' . md5($data['email']);
        
        Cache::put($cacheKey, [
            'data' => $data,
            'code' => $otpCode,
        ], now()->addMinutes(10));

        // Dispatch OTP Email
        try {
            Mail::to($data['email'])->send(new SendOtpCodeMail($otpCode, $data['name'], 'Account Registration'));
        } catch (\Throwable $e) {
            \Log::warning('Registration OTP email sending failed: ' . $e->getMessage());
        }

        $this->requiresOtp = true;
        $this->pendingRegistrationData = $data;
        $this->devOtpHint = $otpCode;

        Notification::make()
            ->title('Verification code sent')
            ->body("A 6-digit security code has been sent to {$data['email']}")
            ->success()
            ->send();

        return null;
    }

    public function verifyRegistrationOtp(): ?RegistrationResponse
    {
        $this->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        if (! $this->pendingRegistrationData) {
            $this->addError('otp', 'Registration session expired. Please try again.');
            $this->requiresOtp = false;
            return null;
        }

        $cacheKey = 'reg_otp_' . md5($this->pendingRegistrationData['email']);
        $cached = Cache::get($cacheKey);

        if (! $cached || $cached['code'] !== trim($this->otp)) {
            $this->addError('otp', 'The verification code is invalid or has expired.');
            return null;
        }

        Cache::forget($cacheKey);

        // Create the user
        $data = $this->pendingRegistrationData;
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => true,
        ]);

        event(new Registered($user));

        Filament::auth()->login($user);
        session()->regenerate();

        return app(RegistrationResponse::class);
    }

    public function resendOtp(): void
    {
        if (! $this->pendingRegistrationData) {
            return;
        }

        $data = $this->pendingRegistrationData;
        $otpCode = (string) random_int(100000, 999999);
        $cacheKey = 'reg_otp_' . md5($data['email']);

        Cache::put($cacheKey, [
            'data' => $data,
            'code' => $otpCode,
        ], now()->addMinutes(10));

        $this->devOtpHint = $otpCode;

        try {
            Mail::to($data['email'])->send(new SendOtpCodeMail($otpCode, $data['name'], 'Account Registration'));
        } catch (\Throwable $e) {
            \Log::warning('Resend registration OTP failed: ' . $e->getMessage());
        }

        Notification::make()
            ->title('Fresh code sent')
            ->body("A new verification code has been dispatched to {$data['email']}")
            ->success()
            ->send();
    }

    public function cancelOtp(): void
    {
        $this->requiresOtp = false;
        $this->otp = '';
        $this->pendingRegistrationData = null;
        $this->devOtpHint = null;
    }
}
