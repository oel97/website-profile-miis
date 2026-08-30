<?php

namespace Tests\Feature;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminGalleryPhotoTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_and_filter_gallery_photos_by_album(): void
    {
        $admin = $this->admin();
        $firstAlbum = $this->album('Album Pertama', 'album-pertama');
        $secondAlbum = $this->album('Album Kedua', 'album-kedua');

        GalleryPhoto::create([
            'gallery_album_id' => $firstAlbum->id,
            'image_path' => 'gallery/photos/pertama.jpg',
            'caption' => 'Foto album pertama',
        ]);
        GalleryPhoto::create([
            'gallery_album_id' => $secondAlbum->id,
            'image_path' => 'gallery/photos/kedua.jpg',
            'caption' => 'Foto album kedua',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.gallery-photos.index', ['album_id' => $firstAlbum->id]))
            ->assertOk()
            ->assertSee('Foto album pertama')
            ->assertDontSee('Foto album kedua');
    }

    public function test_admin_can_open_create_and_edit_gallery_photo_pages(): void
    {
        $admin = $this->admin();
        $album = $this->album();
        $photo = GalleryPhoto::create([
            'gallery_album_id' => $album->id,
            'image_path' => 'gallery/photos/kegiatan.jpg',
            'caption' => 'Kegiatan sekolah',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.gallery-photos.create'))
            ->assertOk()
            ->assertSee('Tambah Foto Galeri');

        $this->actingAs($admin)
            ->get(route('admin.gallery-photos.edit', $photo))
            ->assertOk()
            ->assertSee('Edit Foto Galeri')
            ->assertSee('Kegiatan sekolah');
    }

    public function test_admin_can_create_a_gallery_photo_in_the_selected_album(): void
    {
        Storage::fake('public');

        $admin = $this->admin();
        $album = $this->album();

        $this->actingAs($admin)
            ->post(route('admin.gallery-photos.store'), [
                'gallery_album_id' => $album->id,
                'caption' => 'Kegiatan upacara',
                'alt_text' => 'Siswa mengikuti upacara bendera',
                'sort_order' => 2,
                'image' => UploadedFile::fake()->image('upacara.jpg'),
            ])
            ->assertRedirect(route('admin.gallery-photos.index', ['album_id' => $album->id]));

        $photo = GalleryPhoto::firstOrFail();

        $this->assertSame($album->id, $photo->gallery_album_id);
        $this->assertSame('Kegiatan upacara', $photo->caption);
        Storage::disk('public')->assertExists($photo->image_path);
    }

    public function test_admin_can_create_multiple_gallery_photos_in_the_selected_album(): void
    {
        Storage::fake('public');

        $admin = $this->admin();
        $album = $this->album();

        $this->actingAs($admin)
            ->post(route('admin.gallery-photos.store'), [
                'gallery_album_id' => $album->id,
                'caption' => 'Dokumentasi pesantren Ramadan',
                'sort_order' => 3,
                'images' => [
                    UploadedFile::fake()->image('ramadan-1.jpg'),
                    UploadedFile::fake()->image('ramadan-2.jpg'),
                ],
            ])
            ->assertRedirect(route('admin.gallery-photos.index', ['album_id' => $album->id]));

        $photos = GalleryPhoto::orderBy('sort_order')->get();

        $this->assertCount(2, $photos);
        $this->assertSame([3, 4], $photos->pluck('sort_order')->all());
        $this->assertSame($album->id, $photos->first()->gallery_album_id);
        Storage::disk('public')->assertExists($photos->first()->image_path);
        Storage::disk('public')->assertExists($photos->last()->image_path);
    }

    public function test_gallery_photo_requires_an_existing_album_and_image_when_creating(): void
    {
        $admin = $this->admin();

        $this->from(route('admin.gallery-photos.create'))
            ->actingAs($admin)
            ->post(route('admin.gallery-photos.store'), [])
            ->assertRedirect(route('admin.gallery-photos.create'))
            ->assertSessionHasErrors(['gallery_album_id', 'image', 'images']);
    }

    public function test_admin_can_update_a_gallery_photo_and_replace_its_image(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('gallery/photos/foto-lama.jpg', 'foto lama');

        $admin = $this->admin();
        $firstAlbum = $this->album('Album Lama', 'album-lama');
        $secondAlbum = $this->album('Album Baru', 'album-baru');
        $photo = GalleryPhoto::create([
            'gallery_album_id' => $firstAlbum->id,
            'image_path' => 'gallery/photos/foto-lama.jpg',
            'caption' => 'Caption lama',
            'alt_text' => 'Alt lama',
            'sort_order' => 0,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.gallery-photos.update', $photo), [
                'gallery_album_id' => $secondAlbum->id,
                'caption' => 'Caption diperbarui',
                'alt_text' => 'Alt diperbarui',
                'sort_order' => 1,
                'image' => UploadedFile::fake()->image('foto-baru.png'),
            ])
            ->assertRedirect(route('admin.gallery-photos.index', ['album_id' => $secondAlbum->id]));

        $photo->refresh();

        $this->assertSame($secondAlbum->id, $photo->gallery_album_id);
        $this->assertSame('Caption diperbarui', $photo->caption);
        $this->assertSame('Alt diperbarui', $photo->alt_text);
        Storage::disk('public')->assertMissing('gallery/photos/foto-lama.jpg');
        Storage::disk('public')->assertExists($photo->image_path);
    }

    public function test_admin_can_delete_a_gallery_photo_and_its_file(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('gallery/photos/foto-dihapus.jpg', 'foto dihapus');

        $admin = $this->admin();
        $album = $this->album();
        $photo = GalleryPhoto::create([
            'gallery_album_id' => $album->id,
            'image_path' => 'gallery/photos/foto-dihapus.jpg',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.gallery-photos.destroy', $photo))
            ->assertRedirect(route('admin.gallery-photos.index', ['album_id' => $album->id]));

        $this->assertDatabaseMissing('gallery_photos', ['id' => $photo->id]);
        Storage::disk('public')->assertMissing('gallery/photos/foto-dihapus.jpg');
    }

    public function test_non_admin_cannot_access_gallery_photo_management(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get(route('admin.gallery-photos.index'))
            ->assertRedirect(route('admin.login'));
    }

    private function admin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    private function album(string $title = 'Dokumentasi Kegiatan', string $slug = 'dokumentasi-kegiatan'): GalleryAlbum
    {
        return GalleryAlbum::create([
            'title' => $title,
            'slug' => $slug,
            'is_published' => true,
        ]);
    }
}
