<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminFacilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_the_facility_index_page(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get(route('admin.facilities.index'))
            ->assertOk()
            ->assertSee('Sarana dan Prasarana');
    }

    public function test_admin_can_open_create_and_edit_facility_pages(): void
    {
        $admin = $this->admin();
        $facility = $this->facility();

        $this->actingAs($admin)
            ->get(route('admin.facilities.create'))
            ->assertOk()
            ->assertSee('Tambah Sarana Prasarana');

        $this->actingAs($admin)
            ->get(route('admin.facilities.edit', $facility))
            ->assertOk()
            ->assertSee('Edit Sarana Prasarana')
            ->assertSee($facility->name);
    }

    public function test_admin_can_create_a_facility_with_image_and_generated_slug(): void
    {
        Storage::fake('public');

        $admin = $this->admin();

        $this->actingAs($admin)
            ->post(route('admin.facilities.store'), [
                'name' => 'Perpustakaan Digital',
                'description' => 'Ruang baca dengan koleksi buku dan perangkat digital.',
                'quantity' => 2,
                'unit' => 'ruang',
                'sort_order' => 1,
                'is_published' => true,
                'image' => UploadedFile::fake()->image('perpustakaan.jpg'),
            ])
            ->assertRedirect(route('admin.facilities.index'));

        $facility = Facility::firstOrFail();

        $this->assertSame('perpustakaan-digital', $facility->slug);
        $this->assertSame(2, $facility->quantity);
        $this->assertSame('ruang', $facility->unit);
        $this->assertTrue($facility->is_published);
        Storage::disk('public')->assertExists($facility->image_path);
    }

    public function test_facility_requires_a_name_and_publication_status(): void
    {
        $admin = $this->admin();

        $this->from(route('admin.facilities.create'))
            ->actingAs($admin)
            ->post(route('admin.facilities.store'), [])
            ->assertRedirect(route('admin.facilities.create'))
            ->assertSessionHasErrors(['name', 'is_published']);
    }

    public function test_facility_slug_must_be_unique(): void
    {
        $admin = $this->admin();
        $this->facility('Ruang Kelas', 'ruang-kelas');

        $this->actingAs($admin)
            ->post(route('admin.facilities.store'), [
                'name' => 'Ruang Kelas Baru',
                'slug' => 'ruang-kelas',
                'is_published' => true,
            ])
            ->assertSessionHasErrors('slug');
    }

    public function test_admin_can_update_a_facility_and_replace_its_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('facilities/gambar-lama.jpg', 'gambar lama');

        $admin = $this->admin();
        $facility = Facility::create([
            'name' => 'Laboratorium Lama',
            'slug' => 'laboratorium-lama',
            'image_path' => 'facilities/gambar-lama.jpg',
            'is_published' => true,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.facilities.update', $facility), [
                'name' => 'Laboratorium IPA',
                'slug' => 'laboratorium-ipa',
                'description' => 'Laboratorium untuk praktik sains siswa.',
                'quantity' => 1,
                'unit' => 'ruang',
                'sort_order' => 2,
                'is_published' => false,
                'image' => UploadedFile::fake()->image('laboratorium-baru.png'),
            ])
            ->assertRedirect(route('admin.facilities.index'));

        $facility->refresh();

        $this->assertSame('Laboratorium IPA', $facility->name);
        $this->assertSame('laboratorium-ipa', $facility->slug);
        $this->assertFalse($facility->is_published);
        Storage::disk('public')->assertMissing('facilities/gambar-lama.jpg');
        Storage::disk('public')->assertExists($facility->image_path);
    }

    public function test_admin_can_soft_delete_a_facility_without_removing_its_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('facilities/fasilitas-dihapus.jpg', 'gambar fasilitas');

        $admin = $this->admin();
        $facility = Facility::create([
            'name' => 'Aula Sekolah',
            'slug' => 'aula-sekolah',
            'image_path' => 'facilities/fasilitas-dihapus.jpg',
            'is_published' => true,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.facilities.destroy', $facility))
            ->assertRedirect(route('admin.facilities.index'));

        $this->assertSoftDeleted('facilities', ['id' => $facility->id]);
        Storage::disk('public')->assertExists('facilities/fasilitas-dihapus.jpg');
    }

    public function test_non_admin_cannot_access_facility_management(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get(route('admin.facilities.index'))
            ->assertRedirect(route('admin.login'));
    }

    private function admin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    private function facility(string $name = 'Perpustakaan Sekolah', string $slug = 'perpustakaan-sekolah'): Facility
    {
        return Facility::create([
            'name' => $name,
            'slug' => $slug,
            'is_published' => true,
        ]);
    }
}
