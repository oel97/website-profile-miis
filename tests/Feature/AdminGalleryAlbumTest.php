<?php

namespace Tests\Feature;

use App\Models\GalleryAlbum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminGalleryAlbumTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_the_gallery_album_index_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.gallery-albums.index'))
            ->assertOk()
            ->assertSee('Galeri Album');
    }

    public function test_admin_can_open_create_and_edit_gallery_album_pages(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
        $album = GalleryAlbum::create([
            'title' => 'Dokumentasi Kegiatan',
            'slug' => 'dokumentasi-kegiatan',
            'description' => 'Dokumentasi kegiatan sekolah.',
            'is_published' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.gallery-albums.create'))
            ->assertOk()
            ->assertSee('Tambah Album Galeri');

        $this->actingAs($admin)
            ->get(route('admin.gallery-albums.edit', $album))
            ->assertOk()
            ->assertSee('Edit Album Galeri')
            ->assertSee('Dokumentasi Kegiatan');
    }

    public function test_admin_can_create_a_gallery_album_with_cover_and_generated_slug(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.gallery-albums.store'), [
            'title' => 'Dokumentasi Maulid Nabi',
            'description' => 'Kegiatan peringatan Maulid Nabi.',
            'event_date' => '2026-09-05',
            'sort_order' => 1,
            'is_published' => true,
            'image' => UploadedFile::fake()->image('maulid.jpg'),
        ]);

        $response->assertRedirect(route('admin.gallery-albums.index'));

        $album = GalleryAlbum::firstOrFail();

        $this->assertSame('dokumentasi-maulid-nabi', $album->slug);
        $this->assertTrue($album->is_published);
        Storage::disk('public')->assertExists($album->cover_image_path);
    }

    public function test_gallery_album_requires_a_title_and_publication_status(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->from(route('admin.gallery-albums.create'))
            ->actingAs($admin)
            ->post(route('admin.gallery-albums.store'), [])
            ->assertRedirect(route('admin.gallery-albums.create'))
            ->assertSessionHasErrors(['title', 'is_published']);
    }

    public function test_gallery_album_slug_must_be_unique(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
        GalleryAlbum::create([
            'title' => 'Album Pertama',
            'slug' => 'album-sama',
            'is_published' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.gallery-albums.store'), [
                'title' => 'Album Kedua',
                'slug' => 'album-sama',
                'is_published' => true,
            ])
            ->assertSessionHasErrors('slug');
    }

    public function test_admin_can_update_a_gallery_album_and_replace_its_cover(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('gallery/albums/cover-lama.jpg', 'cover lama');

        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
        $album = GalleryAlbum::create([
            'title' => 'Album Lama',
            'slug' => 'album-lama',
            'description' => 'Deskripsi lama.',
            'cover_image_path' => 'gallery/albums/cover-lama.jpg',
            'is_published' => true,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.gallery-albums.update', $album), [
                'title' => 'Album Diperbarui',
                'slug' => 'album-diperbarui',
                'description' => 'Deskripsi diperbarui.',
                'event_date' => '2026-10-01',
                'sort_order' => 2,
                'is_published' => false,
                'image' => UploadedFile::fake()->image('cover-baru.png'),
            ])
            ->assertRedirect(route('admin.gallery-albums.index'));

        $album->refresh();

        $this->assertSame('Album Diperbarui', $album->title);
        $this->assertSame('album-diperbarui', $album->slug);
        $this->assertFalse($album->is_published);
        $this->assertNotSame('gallery/albums/cover-lama.jpg', $album->cover_image_path);
        Storage::disk('public')->assertMissing('gallery/albums/cover-lama.jpg');
        Storage::disk('public')->assertExists($album->cover_image_path);
    }

    public function test_admin_can_soft_delete_a_gallery_album_without_removing_its_cover(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('gallery/albums/cover.jpg', 'cover album');

        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
        $album = GalleryAlbum::create([
            'title' => 'Album Untuk Dihapus',
            'slug' => 'album-untuk-dihapus',
            'cover_image_path' => 'gallery/albums/cover.jpg',
            'is_published' => true,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.gallery-albums.destroy', $album))
            ->assertRedirect(route('admin.gallery-albums.index'));

        $this->assertSoftDeleted('gallery_albums', ['id' => $album->id]);
        Storage::disk('public')->assertExists('gallery/albums/cover.jpg');
    }
}
