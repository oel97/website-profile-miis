<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_news_index_is_accessible_without_cms_content(): void
    {
        $this->get(route('news'))
            ->assertOk()
            ->assertSee('Kabar Madrasah')
            ->assertSee('Berita dan kegiatan yang telah diterbitkan');
    }

    public function test_news_index_displays_only_visible_posts(): void
    {
        $activeCategory = $this->category('Kegiatan', 'kegiatan', true);
        $inactiveCategory = $this->category('Arsip', 'arsip', false);

        $this->createPost($activeCategory, 'Berita Terbit', 'berita-terbit', 'published');
        $this->createPost($activeCategory, 'Berita Draft', 'berita-draft', 'draft');
        $this->createPost($inactiveCategory, 'Berita Kategori Nonaktif', 'berita-kategori-nonaktif', 'published');

        $this->get(route('news'))
            ->assertOk()
            ->assertSee('Berita Terbit')
            ->assertSee('Kegiatan')
            ->assertDontSee('Berita Draft')
            ->assertDontSee('Berita Kategori Nonaktif');
    }

    public function test_news_detail_displays_seo_content_and_related_posts(): void
    {
        $category = $this->category('Kegiatan', 'kegiatan', true);
        $post = $this->createPost($category, 'Kegiatan Utama', 'kegiatan-utama', 'published', [
            'excerpt' => 'Ringkasan kegiatan utama.',
            'content' => 'Isi lengkap kegiatan utama.',
            'meta_title' => 'SEO Kegiatan Utama',
            'meta_description' => 'Deskripsi SEO kegiatan utama.',
        ]);
        $this->createPost($category, 'Kegiatan Terkait', 'kegiatan-terkait', 'published');

        $this->get(route('news.show', $post))
            ->assertOk()
            ->assertSee('SEO Kegiatan Utama')
            ->assertSee('Deskripsi SEO kegiatan utama.')
            ->assertSee('Ringkasan kegiatan utama.')
            ->assertSee('Isi lengkap kegiatan utama.')
            ->assertSee('Kegiatan Terkait');
    }

    public function test_draft_post_cannot_be_opened_publicly(): void
    {
        $post = $this->createPost($this->category('Kegiatan', 'kegiatan', true), 'Berita Draft', 'berita-draft', 'draft');

        $this->get(route('news.show', $post))
            ->assertNotFound();
    }

    private function category(string $name, string $slug, bool $isActive): PostCategory
    {
        return PostCategory::create([
            'name' => $name,
            'slug' => $slug,
            'is_active' => $isActive,
        ]);
    }

    /**
     * @param  array<string, string>  $overrides
     */
    private function createPost(PostCategory $category, string $title, string $slug, string $status, array $overrides = []): Post
    {
        return Post::create(array_merge([
            'post_category_id' => $category->id,
            'title' => $title,
            'slug' => $slug,
            'excerpt' => "Ringkasan {$title}.",
            'content' => "Isi {$title}.",
            'status' => $status,
            'published_at' => $status === 'published' ? now() : null,
        ], $overrides));
    }
}
