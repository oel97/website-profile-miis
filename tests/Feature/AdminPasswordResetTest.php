<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AdminPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_open_the_password_reset_request_page(): void
    {
        $this->get(route('admin.password.request'))
            ->assertOk()
            ->assertSee('Lupa kata sandi?');
    }

    public function test_active_admin_can_request_a_password_reset_link(): void
    {
        Notification::fake();

        $admin = $this->admin();

        $this->post(route('admin.password.email'), ['email' => $admin->email])
            ->assertRedirect()
            ->assertSessionHas('status');

        Notification::assertSentTo($admin, ResetPassword::class, function (ResetPassword $notification) use ($admin): bool {
            $url = (ResetPassword::$createUrlCallback)($admin, $notification->token);

            return str_starts_with($url, route('admin.password.reset', ['token' => $notification->token]))
                && str_contains($url, 'email='.urlencode($admin->email));
        });
    }

    public function test_password_reset_request_requires_a_valid_email(): void
    {
        $this->from(route('admin.password.request'))
            ->post(route('admin.password.email'), ['email' => 'bukan-email'])
            ->assertRedirect(route('admin.password.request'))
            ->assertSessionHasErrors('email');
    }

    public function test_non_admin_accounts_do_not_receive_a_password_reset_link(): void
    {
        Notification::fake();

        $user = User::factory()->create([
            'email' => 'user@profilemiis.test',
            'role' => 'user',
            'is_active' => true,
        ]);

        $this->post(route('admin.password.email'), ['email' => $user->email])
            ->assertRedirect()
            ->assertSessionHas('status');

        Notification::assertNothingSent();
    }

    public function test_active_admin_can_reset_their_password_with_a_valid_token(): void
    {
        $admin = $this->admin();
        $token = Password::broker()->createToken($admin);

        $this->post(route('admin.password.update'), [
            'token' => $token,
            'email' => $admin->email,
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ])
            ->assertRedirect(route('admin.login'))
            ->assertSessionHas('status');

        $this->assertTrue(Hash::check('new-secure-password', $admin->fresh()->password));

        $this->post(route('admin.login.store'), [
            'email' => $admin->email,
            'password' => 'new-secure-password',
        ])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin->fresh());
    }

    public function test_password_reset_requires_a_matching_confirmation(): void
    {
        $admin = $this->admin();

        $this->post(route('admin.password.update'), [
            'token' => 'invalid-token',
            'email' => $admin->email,
            'password' => 'new-secure-password',
            'password_confirmation' => 'different-password',
        ])->assertSessionHasErrors('password');
    }

    public function test_password_reset_rejects_an_invalid_or_reused_token(): void
    {
        $admin = $this->admin();

        $this->post(route('admin.password.update'), [
            'token' => 'invalid-token',
            'email' => $admin->email,
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ])->assertSessionHasErrors('email');

        $token = Password::broker()->createToken($admin);

        $payload = [
            'token' => $token,
            'email' => $admin->email,
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ];

        $this->post(route('admin.password.update'), $payload)
            ->assertRedirect(route('admin.login'));

        $this->post(route('admin.password.update'), $payload)
            ->assertSessionHasErrors('email');
    }

    private function admin(): User
    {
        return User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@profilemiis.test',
            'password' => Hash::make('current-password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }
}
