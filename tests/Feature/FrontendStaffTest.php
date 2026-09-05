<?php

namespace Tests\Feature;

use App\Models\SchoolProfile;
use App\Models\Staff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FrontendStaffTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_page_is_accessible_without_cms_content(): void
    {
        $this->get(route('staff'))
            ->assertOk()
            ->assertSee('Pendidik & Tenaga Kependidikan', false)
            ->assertSee('Guru')
            ->assertSee('Tenaga Kependidikan');
    }

    public function test_staff_page_groups_only_active_staff_members(): void
    {
        SchoolProfile::create([
            'name' => "MI Islamiyah Syafi'iyah",
            'address' => 'Jl. Madrasah Nomor 1',
        ]);

        Staff::create([
            'name' => 'Siti Aminah, S.Pd.',
            'type' => Staff::TYPE_GURU,
            'position' => 'Guru Kelas',
            'employment_type' => 'Guru Kelas',
            'education' => 'S.Pd',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Staff::create([
            'name' => 'Budi Santoso',
            'type' => Staff::TYPE_EDUCATION_STAFF,
            'position' => 'Operator Madrasah',
            'employment_type' => 'Operator Madrasah',
            'education' => 'S1',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Staff::create([
            'name' => 'Data Tidak Aktif',
            'type' => Staff::TYPE_GURU,
            'position' => 'Guru Mata Pelajaran',
            'employment_type' => 'Guru Mata Pelajaran',
            'is_active' => false,
        ]);

        $this->get(route('staff'))
            ->assertOk()
            ->assertSee('Siti Aminah, S.Pd.')
            ->assertSee('Guru Kelas')
            ->assertSee('Budi Santoso')
            ->assertSee('Operator Madrasah')
            ->assertDontSee('Data Tidak Aktif')
            ->assertViewHas('teachers', fn ($teachers): bool => $teachers->pluck('name')->all() === ['Siti Aminah, S.Pd.'])
            ->assertViewHas('educationStaff', fn ($staff): bool => $staff->pluck('name')->all() === ['Budi Santoso']);
    }

    public function test_active_staff_member_can_be_opened_on_a_public_profile_page(): void
    {
        $staff = Staff::create([
            'name' => 'Ahmad Fauzi, S.Pd.I',
            'position' => 'Guru Pendidikan Agama Islam',
            'employment_type' => 'Guru Tetap',
            'education' => 'S.Pd.I',
            'subject_or_duty' => 'Pendidikan Agama Islam',
            'bio' => 'Mendampingi pembelajaran agama dan pembiasaan ibadah siswa.',
            'is_active' => true,
        ]);

        $inactiveStaff = Staff::create([
            'name' => 'Staff Tidak Aktif',
            'position' => 'Guru Kelas',
            'is_active' => false,
        ]);

        $this->get(route('staff'))
            ->assertOk()
            ->assertSee(route('staff.show', $staff));

        $this->get(route('staff.show', $staff))
            ->assertOk()
            ->assertSee('Ahmad Fauzi, S.Pd.I')
            ->assertSee('Guru Pendidikan Agama Islam')
            ->assertSee('Pendidikan Agama Islam')
            ->assertSee('Mendampingi pembelajaran agama dan pembiasaan ibadah siswa.');

        $this->get(route('staff.show', $inactiveStaff))
            ->assertNotFound();
    }

    public function test_public_staff_pages_use_a_placeholder_when_the_photo_file_is_missing(): void
    {
        Storage::fake('public');

        $staff = Staff::create([
            'name' => 'Foto Hilang',
            'position' => 'Guru Kelas',
            'employment_type' => 'Guru Tetap',
            'photo_path' => 'staff/missing.jpg',
            'is_active' => true,
        ]);

        $missingPhotoUrl = asset('storage/staff/missing.jpg');

        $this->get(route('staff'))
            ->assertOk()
            ->assertDontSee($missingPhotoUrl);

        $this->get(route('staff.show', $staff))
            ->assertOk()
            ->assertDontSee($missingPhotoUrl);
    }

    public function test_public_staff_pages_display_a_photo_that_exists_on_the_public_disk(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('staff/available.jpg', 'photo-content');

        $staff = Staff::create([
            'name' => 'Foto Tersedia',
            'position' => 'Guru Kelas',
            'employment_type' => 'Guru Tetap',
            'photo_path' => 'staff/available.jpg',
            'is_active' => true,
        ]);

        $photoUrl = asset('storage/staff/available.jpg');

        $this->get(route('staff'))
            ->assertOk()
            ->assertSee($photoUrl);

        $this->get(route('staff.show', $staff))
            ->assertOk()
            ->assertSee($photoUrl);
    }
}
