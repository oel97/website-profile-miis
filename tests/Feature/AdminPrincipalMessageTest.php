<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPrincipalMessageTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsAdmin(): User
    {
        $user = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@profilemiis.test',
            'password' => bcrypt('admin12345'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $this->actingAs($user);

        return $user;
    }

    public function test_admin_can_open_principal_message_index_page(): void
    {
        $this->actingAsAdmin();

        $this->get('/admin/principal-message')->assertOk();
    }

    public function test_admin_can_create_principal_message_with_photo_upload(): void
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        $response = $this->post('/admin/principal-message', [
            'name' => 'Ustadz Ahmad Zaki',
            'position' => 'Kepala Madrasah',
            'title' => 'Selamat Datang di MI Islamiyah Syafi\'iyah',
            'message' => 'Dengan penuh syukur, kami menyiapkan pembelajaran yang islami dan berkualitas.',
            'is_active' => true,
            'photo' => UploadedFile::fake()->image('kepala.png', 400, 500),
        ]);

        $response->assertRedirect('/admin/principal-message');
        $this->assertDatabaseHas('principal_messages', [
            'name' => 'Ustadz Ahmad Zaki',
            'position' => 'Kepala Madrasah',
            'is_active' => true,
        ]);

        $files = Storage::disk('public')->allFiles('principal');
        $this->assertNotEmpty($files);
    }
}
