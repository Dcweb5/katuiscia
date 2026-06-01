<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\AccountLockedNotification;
use Tests\TestCase;

class PasswordResetWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_form_can_be_rendered()
    {
        $response = $this->get('/mot-de-passe-oublie');
        $response->assertStatus(200);
        $response->assertSee('Mot de passe oublié');
    }

    public function test_forgot_password_sends_reset_email_and_enforces_cooldown()
    {
        Notification::fake();
        $user = User::factory()->create([
            'email' => 'test@katuiscia.com',
            'password' => Hash::make('password123'),
        ]);

        // Submit the forgot password form
        $response = $this->post('/mot-de-passe-oublie', [
            'email' => 'test@katuiscia.com',
        ]);

        $response->assertRedirect(route('password.request-code', ['email' => 'test@katuiscia.com']));
        $response->assertSessionHas('email_sent', true);
        $response->assertSessionHas('cooldown');

        // Check that cooldown value is an integer (no decimals!)
        $cooldown = session('cooldown');
        $this->assertIsInt($cooldown);
        $this->assertEquals(60, $cooldown); // first cooldown should be 60s

        Notification::assertSentTo($user, ResetPasswordNotification::class);

        // Attempting to send again immediately should trigger custom rate-limiter
        $response2 = $this->post('/mot-de-passe-oublie', [
            'email' => 'test@katuiscia.com',
        ]);

        $response2->assertRedirect(route('password.request-code', ['email' => 'test@katuiscia.com']));
        $response2->assertSessionHasErrors('email');
        $errors = session('errors')->get('email');
        $this->assertStringContainsString('Veuillez patienter', $errors[0]);
    }

    public function test_password_can_be_reset_with_token()
    {
        $user = User::factory()->create([
            'email' => 'test@katuiscia.com',
            'password' => Hash::make('oldpassword'),
        ]);

        // Generate reset token using standard Laravel Password broker
        $token = \Illuminate\Support\Facades\Password::createToken($user);

        // Access the reset form
        $response = $this->get('/reinitialisation/' . $token . '?email=' . urlencode($user->email));
        $response->assertStatus(200);

        // Submit new password
        $response2 = $this->post('/reinitialisation', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response2->assertRedirect('/connexion');
        $response2->assertSessionHas('success');

        // Check if password was actually changed in DB
        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    public function test_password_can_be_reset_with_code()
    {
        Notification::fake();
        $user = User::factory()->create([
            'email' => 'code@katuiscia.com',
            'password' => Hash::make('oldpassword'),
        ]);

        // Submit forgot password form (sends unified link + code notification)
        $response = $this->post('/mot-de-passe-oublie', [
            'email' => 'code@katuiscia.com',
        ]);

        $response->assertRedirect(route('password.request-code', ['email' => 'code@katuiscia.com']));
        
        Notification::assertSentTo($user, ResetPasswordNotification::class);
        
        // Code should be in cache
        $storedCode = cache()->get('pwd_reset_code_code@katuiscia.com');
        $this->assertNotNull($storedCode);
        $this->assertEquals(6, strlen($storedCode));

        // Attempt verify with incorrect code
        $responseIncorrect = $this->post('/mot-de-passe-oublie/code', [
            'email' => 'code@katuiscia.com',
            'code' => '000000',
        ]);
        $responseIncorrect->assertSessionHasErrors('code');

        // Verify with correct code
        $responseCorrect = $this->post('/mot-de-passe-oublie/code', [
            'email' => 'code@katuiscia.com',
            'code' => $storedCode,
        ]);
        $responseCorrect->assertRedirect(route('password.reset-code', ['email' => 'code@katuiscia.com']));
        $this->assertEquals('code@katuiscia.com', session('pwd_reset_code_verified'));

        // Access new password form
        $responseForm = $this->get('/reinitialisation-mot-de-passe?email=' . urlencode('code@katuiscia.com'));
        $responseForm->assertStatus(200);

        // Submit new password
        $responseUpdate = $this->post('/reinitialisation-mot-de-passe', [
            'email' => 'code@katuiscia.com',
            'password' => 'newpassword321',
            'password_confirmation' => 'newpassword321',
        ]);

        $responseUpdate->assertRedirect('/connexion');
        $this->assertTrue(Hash::check('newpassword321', $user->fresh()->password));
    }

    public function test_user_is_locked_after_5_failed_logins_and_can_be_unlocked_by_admin()
    {
        Notification::fake();
        $user = User::factory()->create([
            'email' => 'failed@katuiscia.com',
            'password' => Hash::make('secret123'),
        ]);

        // Attempt 5 incorrect logins
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/connexion', [
                'email' => 'failed@katuiscia.com',
                'password' => 'wrong-pass',
            ]);
            $response->assertRedirect();
        }

        // 5th attempt triggers lock and sends AccountLockedNotification
        $user = $user->fresh();
        $this->assertEquals(5, $user->login_attempts);
        $this->assertNotNull($user->locked_until);
        $this->assertTrue($user->locked_until->isFuture());

        Notification::assertSentTo($user, AccountLockedNotification::class);

        // Try to log in again, should display lockout error
        $responseLock = $this->post('/connexion', [
            'email' => 'failed@katuiscia.com',
            'password' => 'secret123',
        ]);
        $responseLock->assertSessionHasErrors('email');
        $errors = session('errors')->get('email');
        $this->assertStringContainsString('temporairement bloqué', $errors[0]);

        // Admin login and unblock
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        // Submit unblock request
        $responseUnblock = $this->put(route('admin.users.unblock', $user));
        $responseUnblock->assertRedirect();

        // Check DB is updated
        $user = $user->fresh();
        $this->assertEquals(0, $user->login_attempts);
        $this->assertNull($user->locked_until);
    }

    public function test_registration_does_not_crash_on_mail_timeout_and_shows_fallback_code()
    {
        // Mock Mail facade to throw an Exception
        \Illuminate\Support\Facades\Mail::shouldReceive('send')
            ->andThrow(new \Symfony\Component\Mailer\Exception\TransportException('Connection to ssl://mail.katuiscia.com:465 timed out.'));

        // Register a user
        $response = $this->post('/inscription', [
            'firstname' => 'Sophie',
            'lastname' => 'Jeanou',
            'email' => 'test_fallback@katuiscia.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => '1',
        ]);

        // It should redirect to verification notice without crashing with a 500 error!
        $response->assertRedirect(route('verification.notice'));
        $response->assertSessionHas('email_error');

        // Access the verification notice page
        $responseNotice = $this->get(route('verification.notice'));
        $responseNotice->assertStatus(200);
        $responseNotice->assertSee('Mode de secours');
        $responseNotice->assertSee('Saisissez le code de validation suivant');
    }

    public function test_forgot_password_does_not_crash_on_mail_timeout_and_shows_fallback_code()
    {
        // Create user
        $user = User::factory()->create([
            'email' => 'test_pwd_fallback@katuiscia.com',
        ]);

        // Mock Mail facade to throw an Exception
        \Illuminate\Support\Facades\Mail::shouldReceive('send')
            ->andThrow(new \Symfony\Component\Mailer\Exception\TransportException('Connection to ssl://mail.katuiscia.com:465 timed out.'));

        // Submit forgot password request
        $response = $this->post('/mot-de-passe-oublie', [
            'email' => 'test_pwd_fallback@katuiscia.com',
        ]);

        // It should redirect to the request code entry page instead of crashing
        $response->assertRedirect(route('password.request-code', ['email' => 'test_pwd_fallback@katuiscia.com']));
        $response->assertSessionHas('email_error');

        // Access the code entry page
        $responseCode = $this->get(route('password.request-code', ['email' => 'test_pwd_fallback@katuiscia.com']));
        $responseCode->assertStatus(200);
        $responseCode->assertSee('Mode de secours');
        $responseCode->assertSee('Saisissez le code de réinitialisation suivant');
    }
}
