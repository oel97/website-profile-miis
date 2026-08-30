<?php

namespace Tests\Feature;

use App\Models\HeroSlide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminHeroSlideTest extends TestCase
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

    public function test_admin_can_open_the_hero_slide_index_page(): void
    {
        $this->actingAsAdmin();

        $this->get(route('admin.hero-slides.index'))
            ->assertOk()
            ->assertSee('Hero Slide');
    }

    public function test_admin_can_create_a_hero_slide_with_images(): void
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        $this->post(route('admin.hero-slides.store'), [
            'title' => 'Pendaftaran Peserta Didik Baru',
            'subtitle' => 'Mari bergabung bersama MI Islamiyah Syafi\'iyah.',
            'image' => UploadedFile::fake()->image('hero-desktop.jpg', 1600, 800),
            'mobile_image' => UploadedFile::fake()->image('hero-mobile.jpg', 800, 1000),
            'button_label' => 'Daftar Sekarang',
            'button_url' => 'https://example.test/ppdb',
            'overlay_opacity' => 55,
            'sort_order' => 1,
            'is_active' => true,
        ])->assertRedirect(route('admin.hero-slides.index'));

        $heroSlide = HeroSlide::firstOrFail();

        $this->assertSame('Pendaftaran Peserta Didik Baru', $heroSlide->title);
        $this->assertTrue($heroSlide->is_active);
        Storage::disk('public')->assertExists($heroSlide->image_path);
        Storage::disk('public')->assertExists($heroSlide->mobile_image_path);
    }

    public function test_hero_slide_validation_requires_content_and_a_valid_button_url(): void
    {
        $this->actingAsAdmin();

        $this->from(route('admin.hero-slides.create'))
            ->post(route('admin.hero-slides.store'), [
                'button_url' => 'bukan-url',
                'overlay_opacity' => 101,
            ])
            ->assertRedirect(route('admin.hero-slides.create'))
            ->assertSessionHasErrors(['title', 'subtitle', 'image', 'button_url', 'overlay_opacity', 'is_active']);
    }

    public function test_admin_can_update_a_hero_slide_and_replace_its_images(): void
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        $oldImage = UploadedFile::fake()->image('old-desktop.jpg')->store('hero', 'public');
        $oldMobileImage = UploadedFile::fake()->image('old-mobile.jpg')->store('hero', 'public');
        $heroSlide = HeroSlide::create([
            'title' => 'Hero Lama',
            'subtitle' => 'Deskripsi lama.',
            'image_path' => $oldImage,
            'mobile_image_path' => $oldMobileImage,
            'overlay_opacity' => 45,
            'is_active' => true,
        ]);

        $this->put(route('admin.hero-slides.update', $heroSlide), [
            'title' => 'Hero Baru',
            'subtitle' => 'Deskripsi baru.',
            'image' => UploadedFile::fake()->image('new-desktop.webp', 1600, 800),
            'mobile_image' => UploadedFile::fake()->image('new-mobile.webp', 800, 1000),
            'overlay_opacity' => 35,
            'sort_order' => 2,
            'is_active' => false,
        ])->assertRedirect(route('admin.hero-slides.index'));

        $heroSlide->refresh();

        $this->assertSame('Hero Baru', $heroSlide->title);
        $this->assertFalse($heroSlide->is_active);
        Storage::disk('public')->assertMissing($oldImage);
        Storage::disk('public')->assertMissing($oldMobileImage);
        Storage::disk('public')->assertExists($heroSlide->image_path);
        Storage::disk('public')->assertExists($heroSlide->mobile_image_path);
    }

    public function test_admin_can_delete_a_hero_slide_and_its_images(): void
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        $imagePath = UploadedFile::fake()->image('hero.jpg')->store('hero', 'public');
        $heroSlide = HeroSlide::create([
            'title' => 'Hero Dihapus',
            'subtitle' => 'Deskripsi hero.',
            'image_path' => $imagePath,
            'overlay_opacity' => 45,
            'is_active' => true,
        ]);

        $this->delete(route('admin.hero-slides.destroy', $heroSlide))
            ->assertRedirect(route('admin.hero-slides.index'));

        $this->assertDatabaseMissing('hero_slides', ['id' => $heroSlide->id]);
        Storage::disk('public')->assertMissing($imagePath);
    }

    public function test_non_admin_cannot_access_hero_slide_management(): void
    {
        $user = User::factory()->create(['role' => 'editor', 'is_active' => true]);

        $this->actingAs($user)
            ->get(route('admin.hero-slides.index'))
            ->assertRedirect(route('admin.login'));
    }
}
