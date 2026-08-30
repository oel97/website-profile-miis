<?php

namespace Tests\Feature;

use App\Models\GalleryAlbum;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\SchoolProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendSeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_layout_renders_canonical_social_metadata_and_skip_link(): void
    {
        SchoolProfile::create([
            'name' => "MI Islamiyah Syafi'iyah",
            'address' => 'Jl. Madrasah Nomor 1',
            'short_description' => 'Madrasah unggul berkarakter Islami.',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('<link rel="canonical" href="'.route('home').'">', false)
            ->assertSee('property="og:site_name" content="MI Islamiyah Syafi&#039;iyah"', false)
            ->assertSee('name="twitter:card"', false)
            ->assertSee('href="#main-content"', false)
            ->assertSee('id="main-content"', false);
    }

    public function test_sitemap_only_contains_public_posts_and_published_gallery_albums(): void
    {
        $category = PostCategory::create([
            'name' => 'Kegiatan',
            'slug' => 'kegiatan',
            'is_active' => true,
        ]);

        $visiblePost = Post::create([
            'post_category_id' => $category->id,
            'title' => 'Berita Terbit',
            'slug' => 'berita-terbit',
            'content' => 'Isi berita terbit.',
            'status' => 'published',
            'published_at' => now(),
        ]);
        Post::create([
            'post_category_id' => $category->id,
            'title' => 'Berita Draft',
            'slug' => 'berita-draft',
            'content' => 'Isi berita draft.',
            'status' => 'draft',
        ]);

        $visibleAlbum = GalleryAlbum::create([
            'title' => 'Album Terbit',
            'slug' => 'album-terbit',
            'is_published' => true,
        ]);
        GalleryAlbum::create([
            'title' => 'Album Tersembunyi',
            'slug' => 'album-tersembunyi',
            'is_published' => false,
        ]);

        $this->get(route('sitemap'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('<urlset', false)
            ->assertSee(route('news.show', $visiblePost), false)
            ->assertSee(route('gallery.show', $visibleAlbum), false)
            ->assertDontSee('berita-draft')
            ->assertDontSee('album-tersembunyi');
    }
}
