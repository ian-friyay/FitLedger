<?php

namespace Tests\Feature;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\Register;
use App\Mail\SendOtpCodeMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class AuthOtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_credentials_stage_dispatches_otp_email_and_switches_mode(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'coach@testgym.com',
            'password' => Hash::make('password123'),
        ]);

        Livewire::test(Login::class)
            ->fillForm([
                'email' => 'coach@testgym.com',
                'password' => 'password123',
            ])
            ->call('authenticate')
            ->assertSet('requiresOtp', true)
            ->assertSet('pendingUserEmail', 'coach@testgym.com')
            ->assertSee('6-Digit Security Code');

        Mail::assertSent(SendOtpCodeMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });

        $this->assertGuest();
    }

    public function test_login_rejects_invalid_otp_code(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'trainer@testgym.com',
            'password' => Hash::make('password123'),
        ]);

        Livewire::test(Login::class)
            ->fillForm([
                'email' => 'trainer@testgym.com',
                'password' => 'password123',
            ])
            ->call('authenticate')
            ->set('otp', '000000')
            ->call('verifyOtp')
            ->assertHasErrors(['otp']);

        $this->assertGuest();
    }

    public function test_login_authenticates_upon_valid_otp(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'admin@testgym.com',
            'password' => Hash::make('password123'),
        ]);

        $component = Livewire::test(Login::class)
            ->fillForm([
                'email' => 'admin@testgym.com',
                'password' => 'password123',
            ])
            ->call('authenticate');

        $validCode = Cache::get('auth_otp_' . $user->id);
        $this->assertNotNull($validCode);

        $component->set('otp', $validCode)
            ->call('verifyOtp')
            ->assertHasNoErrors();

        $this->assertAuthenticatedAs($user);
    }

    public function test_registration_dispatches_otp_and_creates_account_only_after_verification(): void
    {
        Mail::fake();

        $component = Livewire::test(Register::class)
            ->fillForm([
                'name' => 'John Doe',
                'email' => 'johndoe@testgym.com',
                'password' => 'secretPassword123',
                'passwordConfirmation' => 'secretPassword123',
            ])
            ->call('register')
            ->assertSet('requiresOtp', true)
            ->assertSee('Registration Code');

        Mail::assertSent(SendOtpCodeMail::class, function ($mail) {
            return $mail->hasTo('johndoe@testgym.com');
        });

        // User must NOT be created before OTP is verified
        $this->assertDatabaseMissing('users', ['email' => 'johndoe@testgym.com']);

        // Now verify with correct code
        $cacheKey = 'reg_otp_' . md5('johndoe@testgym.com');
        $cached = Cache::get($cacheKey);
        $this->assertNotNull($cached);
        $validCode = $cached['code'];

        $component->set('otp', $validCode)
            ->call('verifyRegistrationOtp')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', ['email' => 'johndoe@testgym.com']);
        $this->assertAuthenticated();
    }
}
