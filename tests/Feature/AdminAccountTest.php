<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_their_account_page(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get(route('admin.account.edit'))
            ->assertOk()
            ->assertSee('Akun Saya')
            ->assertSee($admin->email);
    }

    public function test_admin_can_update_their_name_and_email(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->put(route('admin.account.update'), [
                'name' => 'Admin MIIS',
                'email' => 'admin-baru@profilemiis.test',
            ])
            ->assertRedirect(route('admin.account.edit'));

        $admin->refresh();

        $this->assertSame('Admin MIIS', $admin->name);
        $this->assertSame('admin-baru@profilemiis.test', $admin->email);
    }

    public function test_admin_can_update_their_password_with_the_current_password(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->put(route('admin.account.update'), [
                'name' => $admin->name,
                'email' => $admin->email,
                'current_password' => 'current-password',
                'password' => 'new-secure-password',
                'password_confirmation' => 'new-secure-password',
            ])
            ->assertRedirect(route('admin.account.edit'));

        $this->assertTrue(Hash::check('new-secure-password', $admin->fresh()->password));
    }

    public function test_admin_cannot_change_password_without_the_current_password(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->put(route('admin.account.update'), [
                'name' => $admin->name,
                'email' => $admin->email,
                'current_password' => 'incorrect-password',
                'password' => 'new-secure-password',
                'password_confirmation' => 'new-secure-password',
            ])
            ->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('current-password', $admin->fresh()->password));
    }

    public function test_non_admin_cannot_open_account_settings(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get(route('admin.account.edit'))
            ->assertRedirect(route('admin.login'));
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
