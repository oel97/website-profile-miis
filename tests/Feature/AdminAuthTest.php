<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_page_is_accessible(): void
    {
        $response = $this->get('/admin/login');

        $response->assertOk();
    }

    public function test_guest_is_redirected_to_the_admin_login_page(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
    }

    public function test_super_admin_can_login_and_access_dashboard(): void
    {
        $this->seed(AdminUserSeeder::class);

        $response = $this->post('/admin/login', [
            'email' => 'admin@profilemiis.test',
            'password' => 'admin12345',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs(User::where('email', 'admin@profilemiis.test')->first());

        $this->get('/admin')->assertOk();
    }

    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'teacher',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertRedirect('/admin/login');
    }

    public function test_non_admin_user_cannot_login_to_the_admin_area(): void
    {
        User::factory()->create([
            'email' => 'editor@profilemiis.test',
            'password' => bcrypt('editor-password'),
            'role' => 'editor',
            'is_active' => true,
        ]);

        $this->post('/admin/login', [
            'email' => 'editor@profilemiis.test',
            'password' => 'editor-password',
        ])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_inactive_admin_cannot_login(): void
    {
        User::factory()->create([
            'email' => 'inactive-admin@profilemiis.test',
            'password' => bcrypt('inactive-password'),
            'role' => 'admin',
            'is_active' => false,
        ]);

        $this->post('/admin/login', [
            'email' => 'inactive-admin@profilemiis.test',
            'password' => 'inactive-password',
        ])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
