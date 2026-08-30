<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPostTest extends TestCase
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

    protected function createCategory(): PostCategory
    {
        return PostCategory::create([
            'name' => 'Kegiatan Sekolah',
            'slug' => 'kegiatan-sekolah',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_open_the_post_index_page(): void
    {
        $this->actingAsAdmin();

        $this->get(route('admin.posts.index'))
            ->assertOk()
            ->assertSee('Berita & Kegiatan');
    }

    public function test_admin_can_open_the_create_post_page_and_select_a_category(): void
    {
        $this->actingAsAdmin();
        $category = $this->createCategory();

        $this->get(route('admin.posts.create'))
            ->assertOk()
            ->assertSee('Tambah Berita & Kegiatan')
            ->assertSee($category->name);
    }

    public function test_admin_can_open_the_edit_post_page(): void
    {
        $this->actingAsAdmin();
        $category = $this->createCategory();
        $post = Post::create([
            'post_category_id' => $category->id,
            'title' => 'Berita untuk Diedit',
            'slug' => 'berita-untuk-diedit',
            'content' => 'Isi berita untuk diedit.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get(route('admin.posts.edit', $post))
            ->assertOk()
            ->assertSee('Edit Berita & Kegiatan')
            ->assertSee($post->title);
    }

    public function test_admin_can_create_a_published_post_with_a_thumbnail_and_generated_slug(): void
    {
        $admin = $this->actingAsAdmin();
        $category = $this->createCategory();
        Storage::fake('public');

        $this->post(route('admin.posts.store'), [
            'post_category_id' => $category->id,
            'title' => 'Peringatan Hari Kemerdekaan',
            'excerpt' => 'Kegiatan upacara dan perlombaan kemerdekaan.',
            'content' => 'MI Islamiyah Syafi\'iyah menyelenggarakan upacara dan berbagai perlombaan kemerdekaan.',
            'status' => 'published',
            'published_at' => '2026-08-17 07:00:00',
            'thumbnail' => UploadedFile::fake()->image('kemerdekaan.png', 1200, 675),
        ])->assertRedirect(route('admin.posts.index'));

        $this->assertDatabaseHas('posts', [
            'post_category_id' => $category->id,
            'author_id' => $admin->id,
            'title' => 'Peringatan Hari Kemerdekaan',
            'slug' => 'peringatan-hari-kemerdekaan',
            'status' => 'published',
        ]);

        $post = Post::firstOrFail();
        Storage::disk('public')->assertExists($post->featured_image_path);
    }

    public function test_post_validation_requires_a_category_title_content_and_status(): void
    {
        $this->actingAsAdmin();

        $this->from(route('admin.posts.create'))
            ->post(route('admin.posts.store'))
            ->assertRedirect(route('admin.posts.create'))
            ->assertSessionHasErrors(['post_category_id', 'title', 'content', 'status']);
    }

    public function test_admin_cannot_use_a_duplicate_post_slug(): void
    {
        $this->actingAsAdmin();
        $category = $this->createCategory();

        Post::create([
            'post_category_id' => $category->id,
            'title' => 'Berita Pertama',
            'slug' => 'berita-sama',
            'content' => 'Isi berita pertama.',
            'status' => 'draft',
        ]);

        $this->from(route('admin.posts.create'))
            ->post(route('admin.posts.store'), [
                'post_category_id' => $category->id,
                'title' => 'Berita Kedua',
                'slug' => 'Berita Sama',
                'content' => 'Isi berita kedua.',
                'status' => 'draft',
            ])
            ->assertRedirect(route('admin.posts.create'))
            ->assertSessionHasErrors('slug');
    }

    public function test_admin_can_update_a_post_and_replace_its_thumbnail(): void
    {
        $admin = $this->actingAsAdmin();
        $category = $this->createCategory();
        Storage::fake('public');

        $oldThumbnail = UploadedFile::fake()->image('thumbnail-lama.jpg')->store('posts', 'public');
        $post = Post::create([
            'post_category_id' => $category->id,
            'author_id' => $admin->id,
            'title' => 'Kegiatan Awal',
            'slug' => 'kegiatan-awal',
            'content' => 'Isi kegiatan awal.',
            'featured_image_path' => $oldThumbnail,
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $this->put(route('admin.posts.update', $post), [
            'post_category_id' => $category->id,
            'title' => 'Kegiatan yang Diperbarui',
            'slug' => 'kegiatan-diperbarui',
            'excerpt' => 'Ringkasan kegiatan terbaru.',
            'content' => 'Isi kegiatan yang telah diperbarui.',
            'status' => 'draft',
            'meta_title' => 'Kegiatan Terbaru',
            'meta_description' => 'Meta deskripsi kegiatan terbaru.',
            'thumbnail' => UploadedFile::fake()->image('thumbnail-baru.webp', 1200, 675),
        ])->assertRedirect(route('admin.posts.index'));

        $post->refresh();

        $this->assertSame('Kegiatan yang Diperbarui', $post->title);
        $this->assertSame('kegiatan-diperbarui', $post->slug);
        $this->assertSame('draft', $post->status);
        $this->assertNull($post->published_at);
        $this->assertSame($admin->id, $post->author_id);
        $this->assertNotSame($oldThumbnail, $post->featured_image_path);
        Storage::disk('public')->assertMissing($oldThumbnail);
        Storage::disk('public')->assertExists($post->featured_image_path);
    }

    public function test_published_post_without_a_publication_date_uses_the_current_time(): void
    {
        $this->actingAsAdmin();
        $category = $this->createCategory();

        $this->post(route('admin.posts.store'), [
            'post_category_id' => $category->id,
            'title' => 'Informasi Penting',
            'content' => 'Isi informasi penting.',
            'status' => 'published',
        ])->assertRedirect(route('admin.posts.index'));

        $this->assertNotNull(Post::firstOrFail()->published_at);
    }

    public function test_admin_can_soft_delete_a_post_without_deleting_its_thumbnail(): void
    {
        $this->actingAsAdmin();
        $category = $this->createCategory();
        Storage::fake('public');

        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg')->store('posts', 'public');
        $post = Post::create([
            'post_category_id' => $category->id,
            'title' => 'Berita yang Dihapus',
            'slug' => 'berita-yang-dihapus',
            'content' => 'Isi berita yang dihapus.',
            'featured_image_path' => $thumbnail,
            'status' => 'draft',
        ]);

        $this->delete(route('admin.posts.destroy', $post))
            ->assertRedirect(route('admin.posts.index'));

        $this->assertSoftDeleted('posts', ['id' => $post->id]);
        Storage::disk('public')->assertExists($thumbnail);
    }
}
