<?php

namespace Tests\Feature;

use App\Models\FeaturedProgram;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminFeaturedProgramTest extends TestCase
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

    public function test_admin_can_open_the_featured_program_index_page(): void
    {
        $this->actingAsAdmin();

        $this->get(route('admin.programs.index'))
            ->assertOk()
            ->assertSee('Program Unggulan');
    }

    public function test_admin_can_create_a_featured_program_with_an_image_and_generated_slug(): void
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        $response = $this->post(route('admin.programs.store'), [
            'title' => 'Tahfiz Al-Qur’an',
            'short_description' => 'Program pembinaan hafalan Al-Qur’an.',
            'description' => 'Program pembinaan hafalan, tahsin, dan murojaah Al-Qur’an secara terarah.',
            'icon' => '✦',
            'sort_order' => 1,
            'is_active' => true,
            'image' => UploadedFile::fake()->image('tahfiz.png', 800, 500),
        ]);

        $response->assertRedirect(route('admin.programs.index'));
        $this->assertDatabaseHas('featured_programs', [
            'title' => 'Tahfiz Al-Qur’an',
            'slug' => 'tahfiz-al-quran',
            'is_active' => true,
        ]);

        $program = FeaturedProgram::firstOrFail();
        Storage::disk('public')->assertExists($program->image_path);
    }

    public function test_featured_program_validation_requires_a_title_and_description(): void
    {
        $this->actingAsAdmin();

        $this->from(route('admin.programs.create'))
            ->post(route('admin.programs.store'), [
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.programs.create'))
            ->assertSessionHasErrors(['title', 'description']);
    }

    public function test_admin_can_update_a_featured_program_and_replace_the_image(): void
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        $oldImagePath = UploadedFile::fake()->image('old-program.jpg')->store('programs', 'public');
        $program = FeaturedProgram::create([
            'title' => 'Program Tahfiz',
            'slug' => 'program-tahfiz',
            'description' => 'Deskripsi awal program tahfiz.',
            'image_path' => $oldImagePath,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $response = $this->put(route('admin.programs.update', $program), [
            'title' => 'Program Tahfiz Al-Qur’an',
            'slug' => 'program-tahfiz-al-quran',
            'short_description' => 'Pembinaan hafalan Al-Qur’an.',
            'description' => 'Deskripsi program tahfiz yang telah diperbarui.',
            'icon' => '✦',
            'sort_order' => 3,
            'is_active' => false,
            'image' => UploadedFile::fake()->image('new-program.webp', 800, 500),
        ]);

        $response->assertRedirect(route('admin.programs.index'));
        $program->refresh();

        $this->assertSame('Program Tahfiz Al-Qur’an', $program->title);
        $this->assertSame('program-tahfiz-al-quran', $program->slug);
        $this->assertFalse($program->is_active);
        $this->assertNotSame($oldImagePath, $program->image_path);
        Storage::disk('public')->assertMissing($oldImagePath);
        Storage::disk('public')->assertExists($program->image_path);
    }

    public function test_admin_cannot_use_a_duplicate_custom_slug(): void
    {
        $this->actingAsAdmin();

        FeaturedProgram::create([
            'title' => 'Program Pertama',
            'slug' => 'program-sama',
            'description' => 'Deskripsi program pertama.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->from(route('admin.programs.create'))
            ->post(route('admin.programs.store'), [
                'title' => 'Program Kedua',
                'slug' => 'Program Sama',
                'description' => 'Deskripsi program kedua.',
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.programs.create'))
            ->assertSessionHasErrors('slug');
    }

    public function test_admin_can_soft_delete_a_featured_program(): void
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        $imagePath = UploadedFile::fake()->image('program-image.jpg')->store('programs', 'public');
        $program = FeaturedProgram::create([
            'title' => 'Program Bahasa Arab',
            'slug' => 'program-bahasa-arab',
            'description' => 'Program penguatan bahasa Arab.',
            'image_path' => $imagePath,
            'sort_order' => 4,
            'is_active' => true,
        ]);

        $this->delete(route('admin.programs.destroy', $program))
            ->assertRedirect(route('admin.programs.index'));

        $this->assertSoftDeleted('featured_programs', ['id' => $program->id]);
        Storage::disk('public')->assertExists($imagePath);
    }
}
