<?php

namespace Tests\Feature;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminStaffTest extends TestCase
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

    public function test_admin_can_open_the_staff_index_page(): void
    {
        $this->actingAsAdmin();

        $this->get(route('admin.staff.index'))
            ->assertOk()
            ->assertSee('Guru & Tenaga Kependidikan', false);

        $this->get(route('admin.staff.create'))
            ->assertOk()
            ->assertSee('enctype="multipart/form-data"', false)
            ->assertSee('name="photo"', false);
    }

    public function test_admin_can_create_a_staff_member_with_a_photo(): void
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        $response = $this->post(route('admin.staff.store'), [
            'name' => 'Siti Aminah, S.Pd.',
            'position' => 'Guru Kelas',
            'employment_type' => 'Guru Tetap',
            'education' => 'S.Pd.',
            'bio' => 'Mengajar dengan pendekatan yang ramah anak.',
            'sort_order' => 1,
            'is_active' => true,
            'photo' => UploadedFile::fake()->image('siti-aminah.png', 400, 500),
        ]);

        $response->assertRedirect(route('admin.staff.index'));
        $this->assertDatabaseHas('staff', [
            'name' => 'Siti Aminah, S.Pd.',
            'position' => 'Guru Kelas',
            'employment_type' => 'Guru Tetap',
            'is_active' => true,
        ]);

        $staff = Staff::firstOrFail();
        Storage::disk('public')->assertExists($staff->photo_path);

        $this->get(route('admin.staff.index'))
            ->assertOk()
            ->assertSee(asset('storage/'.$staff->photo_path));
    }

    public function test_staff_validation_requires_the_mandatory_fields(): void
    {
        $this->actingAsAdmin();

        $this->from(route('admin.staff.create'))
            ->post(route('admin.staff.store'), [
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.staff.create'))
            ->assertSessionHasErrors(['name', 'position', 'employment_type']);
    }

    public function test_staff_photo_must_be_a_supported_image_under_two_megabytes(): void
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        $validData = [
            'name' => 'Siti Aminah',
            'position' => 'Guru Kelas',
            'employment_type' => 'Guru Tetap',
            'is_active' => true,
        ];

        $this->post(route('admin.staff.store'), $validData + [
            'photo' => UploadedFile::fake()->create('photo.gif', 100, 'image/gif'),
        ])->assertSessionHasErrors('photo');

        $this->post(route('admin.staff.store'), $validData + [
            'photo' => UploadedFile::fake()->create('photo.jpg', 2049, 'image/jpeg'),
        ])->assertSessionHasErrors('photo');

        $this->assertDatabaseCount('staff', 0);
    }

    public function test_admin_can_update_a_staff_member_and_replace_the_photo(): void
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        $oldPhotoPath = UploadedFile::fake()->image('old-photo.jpg')->store('staff', 'public');
        $staff = Staff::create([
            'name' => 'Ahmad Fauzi',
            'position' => 'Guru Kelas',
            'employment_type' => 'Guru Tetap',
            'education' => 'S.Pd.',
            'photo_path' => $oldPhotoPath,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $response = $this->put(route('admin.staff.update', $staff), [
            'name' => 'Ahmad Fauzi, S.Pd.',
            'position' => 'Wali Kelas',
            'employment_type' => 'Guru Tetap',
            'education' => 'S.Pd.',
            'bio' => 'Wali kelas tahun pelajaran berjalan.',
            'sort_order' => 3,
            'is_active' => false,
            'photo' => UploadedFile::fake()->image('new-photo.webp', 400, 500),
        ]);

        $response->assertRedirect(route('admin.staff.index'));
        $staff->refresh();

        $this->assertSame('Ahmad Fauzi, S.Pd.', $staff->name);
        $this->assertFalse($staff->is_active);
        $this->assertNotSame($oldPhotoPath, $staff->photo_path);
        Storage::disk('public')->assertMissing($oldPhotoPath);
        Storage::disk('public')->assertExists($staff->photo_path);

        $this->get(route('admin.staff.index'))
            ->assertOk()
            ->assertSee(asset('storage/'.$staff->photo_path));
    }

    public function test_admin_can_soft_delete_a_staff_member(): void
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        $photoPath = UploadedFile::fake()->image('staff-photo.jpg')->store('staff', 'public');
        $staff = Staff::create([
            'name' => 'Nur Hasanah',
            'position' => 'Tenaga Administrasi',
            'employment_type' => 'Tenaga Kependidikan',
            'photo_path' => $photoPath,
            'sort_order' => 4,
            'is_active' => true,
        ]);

        $this->delete(route('admin.staff.destroy', $staff))
            ->assertRedirect(route('admin.staff.index'));

        $this->assertSoftDeleted('staff', ['id' => $staff->id]);
        Storage::disk('public')->assertMissing($photoPath);
    }

    public function test_admin_index_uses_a_placeholder_when_the_photo_file_is_missing(): void
    {
        $this->actingAsAdmin();
        Storage::fake('public');

        Staff::create([
            'name' => 'Foto Hilang',
            'position' => 'Guru Kelas',
            'employment_type' => 'Guru Tetap',
            'photo_path' => 'staff/missing.jpg',
            'is_active' => true,
        ]);

        $this->get(route('admin.staff.index'))
            ->assertOk()
            ->assertDontSee(asset('storage/staff/missing.jpg'));
    }
}
