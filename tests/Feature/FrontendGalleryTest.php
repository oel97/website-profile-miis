<?php

namespace Tests\Feature;

use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendGalleryTest extends TestCase
{
    use RefreshDatabase;

    public function test_gallery_index_is_accessible_without_cms_content(): void
    {
        $this->get(route('gallery'))
            ->assertOk()
            ->assertSee('Galeri MIIS')
            ->assertSee('Album Dokumentasi');
    }

    public function test_gallery_index_displays_only_published_albums_in_sort_order_with_photo_counts(): void
    {
        $secondAlbum = $this->album('Album Kedua', 'album-kedua', true, 2);
        $firstAlbum = $this->album('Album Pertama', 'album-pertama', true, 1);
        $this->album('Album Tidak Terbit', 'album-tidak-terbit', false, 0);

        GalleryPhoto::create([
            'gallery_album_id' => $firstAlbum->id,
            'image_path' => 'gallery/photos/pertama.jpg',
        ]);
        GalleryPhoto::create([
            'gallery_album_id' => $firstAlbum->id,
            'image_path' => 'gallery/photos/kedua.jpg',
        ]);

        $response = $this->get(route('gallery'))
            ->assertOk()
            ->assertSee('Album Pertama')
            ->assertSee('2 foto')
            ->assertSee('Album Kedua')
            ->assertDontSee('Album Tidak Terbit');

        $this->assertLessThan(
            strpos($response->getContent(), 'Album Kedua'),
            strpos($response->getContent(), 'Album Pertama'),
        );
    }

    public function test_gallery_detail_displays_a_published_album_and_its_photos(): void
    {
        $album = $this->album('Kegiatan Ramadan', 'kegiatan-ramadan', true, 0);
        GalleryPhoto::create([
            'gallery_album_id' => $album->id,
            'image_path' => 'gallery/photos/ramadan.jpg',
            'caption' => 'Kegiatan pesantren Ramadan',
            'alt_text' => 'Siswa mengikuti pesantren Ramadan',
        ]);

        $this->get(route('gallery.show', $album))
            ->assertOk()
            ->assertSee('Kegiatan Ramadan')
            ->assertSee('Kegiatan pesantren Ramadan')
            ->assertSee('Siswa mengikuti pesantren Ramadan');
    }

    public function test_unpublished_gallery_album_cannot_be_opened_publicly(): void
    {
        $album = $this->album('Album Tidak Terbit', 'album-tidak-terbit', false, 0);

        $this->get(route('gallery.show', $album))
            ->assertNotFound();
    }

    private function album(string $title, string $slug, bool $isPublished, int $sortOrder): GalleryAlbum
    {
        return GalleryAlbum::create([
            'title' => $title,
            'slug' => $slug,
            'sort_order' => $sortOrder,
            'is_published' => $isPublished,
        ]);
    }
}
