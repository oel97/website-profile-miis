<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminAchievementTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsAdmin(): User
    {
        $user = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $this->actingAs($user);

        return $user;
    }

    public function test_admin_can_open_the_achievement_index_page(): void
    {
        $this->actingAsAdmin();

        $this->get(route('admin.achievements.index'))
            ->assertOk()
            ->assertSee('Prestasi Sekolah');
    }

    public function test_admin_can_open_the_create_and_edit_achievement_pages(): void
    {
        $this->actingAsAdmin();
        $achievement = Achievement::create([
            'title' => 'Juara Olimpiade Sains',
            'slug' => 'juara-olimpiade-sains',
            'description' => 'Prestasi awal.',
            'is_published' => true,
        ]);

        $this->get(route('admin.achievements.create'))
            ->assertOk()
            ->assertSee('Tambah Prestasi');

        $this->get(route('admin.achievements.edit', $achievement))
            ->assertOk()
            ->assertSee('Edit Prestasi')
            ->assertSee($achievement->title);
    }

    public function test_admin_can_create_an_achievement_with_image_and_certificate(): void
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        $this->post(route('admin.achievements.store'), [
            'title' => 'Juara 1 Olimpiade Matematika',
            'recipient_name' => 'Tim Matematika MIIS',
            'level' => 'Kabupaten',
            'organizer' => 'Dinas Pendidikan',
            'achievement_date' => '2026-08-10',
            'description' => 'Tim Matematika MI Islamiyah Syafi\'iyah meraih juara pertama.',
            'sort_order' => 1,
            'is_published' => true,
            'image' => UploadedFile::fake()->image('prestasi.png', 1200, 675),
            'certificate' => UploadedFile::fake()->create('sertifikat.pdf', 512, 'application/pdf'),
        ])->assertRedirect(route('admin.achievements.index'));

        $this->assertDatabaseHas('achievements', [
            'title' => 'Juara 1 Olimpiade Matematika',
            'slug' => 'juara-1-olimpiade-matematika',
            'is_published' => true,
        ]);

        $achievement = Achievement::firstOrFail();
        Storage::disk('public')->assertExists($achievement->image_path);
        Storage::disk('public')->assertExists($achievement->certificate_path);
    }

    public function test_achievement_validation_requires_title_description_and_status(): void
    {
        $this->actingAsAdmin();

        $this->from(route('admin.achievements.create'))
            ->post(route('admin.achievements.store'))
            ->assertRedirect(route('admin.achievements.create'))
            ->assertSessionHasErrors(['title', 'description', 'is_published']);
    }

    public function test_admin_cannot_use_a_duplicate_achievement_slug(): void
    {
        $this->actingAsAdmin();

        Achievement::create([
            'title' => 'Prestasi Pertama',
            'slug' => 'prestasi-sama',
            'description' => 'Deskripsi prestasi pertama.',
            'is_published' => true,
        ]);

        $this->from(route('admin.achievements.create'))
            ->post(route('admin.achievements.store'), [
                'title' => 'Prestasi Kedua',
                'slug' => 'Prestasi Sama',
                'description' => 'Deskripsi prestasi kedua.',
                'is_published' => true,
            ])
            ->assertRedirect(route('admin.achievements.create'))
            ->assertSessionHasErrors('slug');
    }

    public function test_admin_can_update_an_achievement_and_replace_its_files(): void
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        $oldImage = UploadedFile::fake()->image('foto-lama.jpg')->store('achievements', 'public');
        $oldCertificate = UploadedFile::fake()->create('sertifikat-lama.pdf', 512, 'application/pdf')->store('achievements', 'public');
        $achievement = Achievement::create([
            'title' => 'Juara Lomba Sains',
            'slug' => 'juara-lomba-sains',
            'description' => 'Deskripsi awal prestasi.',
            'image_path' => $oldImage,
            'certificate_path' => $oldCertificate,
            'is_published' => true,
        ]);

        $this->put(route('admin.achievements.update', $achievement), [
            'title' => 'Juara Lomba Sains Nasional',
            'slug' => 'juara-lomba-sains-nasional',
            'recipient_name' => 'Siswa MIIS',
            'level' => 'Nasional',
            'organizer' => 'Panitia Olimpiade',
            'achievement_date' => '2026-08-12',
            'description' => 'Deskripsi prestasi yang diperbarui.',
            'sort_order' => 2,
            'is_published' => false,
            'image' => UploadedFile::fake()->image('foto-baru.webp', 1200, 675),
            'certificate' => UploadedFile::fake()->create('sertifikat-baru.pdf', 512, 'application/pdf'),
        ])->assertRedirect(route('admin.achievements.index'));

        $achievement->refresh();

        $this->assertSame('Juara Lomba Sains Nasional', $achievement->title);
        $this->assertSame('juara-lomba-sains-nasional', $achievement->slug);
        $this->assertFalse($achievement->is_published);
        $this->assertNotSame($oldImage, $achievement->image_path);
        $this->assertNotSame($oldCertificate, $achievement->certificate_path);
        Storage::disk('public')->assertMissing($oldImage);
        Storage::disk('public')->assertMissing($oldCertificate);
        Storage::disk('public')->assertExists($achievement->image_path);
        Storage::disk('public')->assertExists($achievement->certificate_path);
    }

    public function test_admin_can_soft_delete_an_achievement_without_deleting_its_files(): void
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        $image = UploadedFile::fake()->image('foto-prestasi.jpg')->store('achievements', 'public');
        $achievement = Achievement::create([
            'title' => 'Prestasi yang Dihapus',
            'slug' => 'prestasi-yang-dihapus',
            'description' => 'Deskripsi prestasi yang dihapus.',
            'image_path' => $image,
            'is_published' => true,
        ]);

        $this->delete(route('admin.achievements.destroy', $achievement))
            ->assertRedirect(route('admin.achievements.index'));

        $this->assertSoftDeleted('achievements', ['id' => $achievement->id]);
        Storage::disk('public')->assertExists($image);
    }
}
