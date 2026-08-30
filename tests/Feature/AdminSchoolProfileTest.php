<?php

namespace Tests\Feature;

use App\Models\SchoolProfile;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminSchoolProfileTest extends TestCase
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

    public function test_admin_can_open_school_profile_index_page(): void
    {
        $this->actingAsAdmin();

        $this->get('/admin/school-profile')->assertOk();
    }

    public function test_admin_can_create_school_profile_with_logo_upload(): void
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        $response = $this->post('/admin/school-profile', [
            'name' => 'MI Islamiyah Syafi\'iyah',
            'npsn' => '12345678',
            'accreditation' => 'A',
            'tagline' => 'Berakhlak, Unggul, dan Cerdas',
            'short_description' => 'Sekolah dasar Islam modern',
            'address' => 'Jl. Raya Cileungsi No. 12',
            'phone' => '02112345678',
            'email' => 'info@profilemiis.test',
            'website' => 'https://profilemiis.test',
            'about' => 'Sejarah sekolah yang berkembang',
            'logo' => UploadedFile::fake()->image('logo.png', 300, 300),
        ]);

        $schoolProfile = SchoolProfile::firstOrFail();

        $response->assertRedirect(route('admin.school-profile.edit', $schoolProfile));
        $this->assertDatabaseHas('school_profiles', [
            'name' => 'MI Islamiyah Syafi\'iyah',
            'email' => 'info@profilemiis.test',
        ]);

        $files = Storage::disk('public')->allFiles('school-profile');
        $this->assertNotEmpty($files);
    }

    public function test_admin_can_update_a_school_profile_without_replacing_its_images(): void
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        $logoPath = UploadedFile::fake()->image('logo-lama.png', 300, 300)
            ->store('school-profile/logo', 'public');
        $schoolPhotoPath = UploadedFile::fake()->image('foto-lama.jpg', 1200, 675)
            ->store('school-profile/foto', 'public');

        $schoolProfile = SchoolProfile::create([
            'name' => 'MI Islamiyah Syafi\'iyah',
            'npsn' => '12345678',
            'ns_madrasah' => 'MI-001',
            'address' => 'Jl. Lama No. 1',
            'logo_path' => $logoPath,
            'foto_sekolah_path' => $schoolPhotoPath,
        ]);

        $this->put(route('admin.school-profile.update', $schoolProfile), [
            'name' => 'MI Islamiyah Syafi\'iyah Diperbarui',
            'npsn' => '12345678',
            'ns_madrasah' => 'MI-001',
            'address' => 'Jl. Baru No. 2',
            'tagline' => 'Madrasah unggul berkarakter Islami',
        ])->assertRedirect(route('admin.school-profile.index'));

        $schoolProfile->refresh();

        $this->assertSame('MI Islamiyah Syafi\'iyah Diperbarui', $schoolProfile->name);
        $this->assertSame('Jl. Baru No. 2', $schoolProfile->address);
        $this->assertSame($logoPath, $schoolProfile->logo_path);
        $this->assertSame($schoolPhotoPath, $schoolProfile->foto_sekolah_path);
        Storage::disk('public')->assertExists($logoPath);
        Storage::disk('public')->assertExists($schoolPhotoPath);
    }

    public function test_admin_can_manage_history_and_vision_mission_from_school_profile(): void
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        $schoolProfile = SchoolProfile::create([
            'name' => 'MI Islamiyah Syafi\'iyah',
            'npsn' => '12345678',
            'address' => 'Jl. Madrasah No. 1',
        ]);

        $this->get(route('admin.school-profile.index'))
            ->assertOk()
            ->assertSee('Tambah Sejarah')
            ->assertSee('Tambah Visi &amp; Misi', false)
            ->assertSee(route('admin.school-profile.edit', ['school_profile' => $schoolProfile, 'section' => 'history']), false)
            ->assertSee(route('admin.school-profile.edit', ['school_profile' => $schoolProfile, 'section' => 'vision-mission']), false);

        $this->get(route('admin.school-profile.edit', $schoolProfile))
            ->assertOk()
            ->assertSee('Simpan Tentang Madrasah')
            ->assertDontSee('Simpan Sejarah Sekolah')
            ->assertDontSee('Simpan Visi & Misi', false);

        $this->get(route('admin.school-profile.edit', ['school_profile' => $schoolProfile, 'section' => 'history']))
            ->assertOk()
            ->assertSee('Simpan Sejarah Sekolah')
            ->assertDontSee('Simpan Tentang Madrasah')
            ->assertDontSee('Simpan Visi & Misi', false);

        $this->get(route('admin.school-profile.edit', ['school_profile' => $schoolProfile, 'section' => 'vision-mission']))
            ->assertOk()
            ->assertSee('Simpan Visi & Misi', false)
            ->assertDontSee('Simpan Tentang Madrasah')
            ->assertDontSee('Simpan Sejarah Sekolah');

        $this->put(route('admin.school-profile.content.update', [
            'school_profile' => $schoolProfile,
            'section' => 'about',
        ]), [
            'about' => 'MI Islamiyah Syafi\'iyah adalah madrasah yang berkomitmen pada pendidikan Islami.',
        ])->assertRedirect(route('admin.school-profile.index'));

        $this->put(route('admin.school-profile.content.update', [
            'school_profile' => $schoolProfile,
            'section' => 'history',
        ]), [
            'history_title' => 'Sejarah MI Islamiyah Syafi\'iyah',
            'history_content' => 'Madrasah ini tumbuh bersama masyarakat dan berkomitmen pada pendidikan Islami.',
            'history_image' => UploadedFile::fake()->image('sejarah-madrasah.webp', 1200, 900),
        ])->assertRedirect(route('admin.school-profile.index'));

        $this->put(route('admin.school-profile.content.update', [
            'school_profile' => $schoolProfile,
            'section' => 'vision-mission',
        ]), [
            'vision_mission_title' => 'Visi dan Misi MIIS',
            'vision_mission_content' => "Visi: Menjadi madrasah unggul.\n\nMisi:\n1. Menumbuhkan akhlak mulia.",
        ])->assertRedirect(route('admin.school-profile.index'));

        $this->assertDatabaseHas('pages', [
            'slug' => 'sejarah',
            'title' => 'Sejarah MI Islamiyah Syafi\'iyah',
            'is_published' => true,
        ]);
        $this->assertDatabaseHas('pages', [
            'slug' => 'visi-misi',
            'title' => 'Visi dan Misi MIIS',
            'is_published' => true,
        ]);
        $historyPage = Page::query()->where('slug', 'sejarah')->firstOrFail();
        $this->assertNotNull($historyPage->cover_image_path);
        Storage::disk('public')->assertExists($historyPage->cover_image_path);

        $this->put(route('admin.school-profile.content.update', [
            'school_profile' => $schoolProfile,
            'section' => 'history',
        ]), [
            'history_title' => 'Sejarah MI Islamiyah Syafi\'iyah',
            'history_content' => 'Sejarah madrasah telah diperbarui.',
        ])->assertRedirect(route('admin.school-profile.index'));

        $this->put(route('admin.school-profile.content.update', [
            'school_profile' => $schoolProfile,
            'section' => 'vision-mission',
        ]), [
            'vision_mission_title' => 'Visi dan Misi MIIS',
            'vision_mission_content' => 'Visi dan misi telah diperbarui.',
        ])->assertRedirect(route('admin.school-profile.index'));

        $this->assertDatabaseHas('pages', [
            'slug' => 'sejarah',
            'content' => 'Sejarah madrasah telah diperbarui.',
        ]);
        $this->assertDatabaseCount('pages', 2);
        $historyPage->refresh();
        Storage::disk('public')->assertExists($historyPage->cover_image_path);

        $this->get('/profil-sekolah')
            ->assertOk()
            ->assertSee('Sejarah madrasah telah diperbarui.')
            ->assertSee('Visi dan misi telah diperbarui.');
    }
}
