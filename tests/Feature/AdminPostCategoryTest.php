<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\User;
use Database\Seeders\PostCategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPostCategoryTest extends TestCase
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

    public function test_admin_can_open_the_post_category_index_page(): void
    {
        $this->actingAsAdmin();

        $this->get(route('admin.post-categories.index'))
            ->assertOk()
            ->assertSee('Kategori Berita');
    }

    public function test_default_post_categories_can_be_seeded_without_duplicates(): void
    {
        $this->seed(PostCategorySeeder::class);
        $this->seed(PostCategorySeeder::class);

        $this->assertSame(4, PostCategory::query()->count());
        $this->assertDatabaseHas('post_categories', [
            'name' => 'Kegiatan Sekolah',
            'slug' => 'kegiatan-sekolah',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_a_post_category_with_a_generated_slug(): void
    {
        $this->actingAsAdmin();

        $this->post(route('admin.post-categories.store'), [
            'name' => 'Kegiatan Sekolah',
            'description' => 'Informasi seluruh kegiatan sekolah.',
            'is_active' => true,
        ])->assertRedirect(route('admin.post-categories.index'));

        $this->assertDatabaseHas('post_categories', [
            'name' => 'Kegiatan Sekolah',
            'slug' => 'kegiatan-sekolah',
            'is_active' => true,
        ]);
    }

    public function test_post_category_validation_requires_a_name(): void
    {
        $this->actingAsAdmin();

        $this->from(route('admin.post-categories.create'))
            ->post(route('admin.post-categories.store'), [
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.post-categories.create'))
            ->assertSessionHasErrors('name');
    }

    public function test_admin_cannot_use_a_duplicate_category_slug(): void
    {
        $this->actingAsAdmin();

        PostCategory::create([
            'name' => 'Pengumuman',
            'slug' => 'pengumuman',
            'is_active' => true,
        ]);

        $this->from(route('admin.post-categories.create'))
            ->post(route('admin.post-categories.store'), [
                'name' => 'Pengumuman Baru',
                'slug' => 'Pengumuman',
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.post-categories.create'))
            ->assertSessionHasErrors('slug');
    }

    public function test_admin_can_update_a_post_category(): void
    {
        $this->actingAsAdmin();

        $category = PostCategory::create([
            'name' => 'Kegiatan',
            'slug' => 'kegiatan',
            'description' => 'Deskripsi awal.',
            'is_active' => true,
        ]);

        $this->put(route('admin.post-categories.update', $category), [
            'name' => 'Kegiatan Madrasah',
            'slug' => 'kegiatan-madrasah',
            'description' => 'Deskripsi yang diperbarui.',
            'is_active' => false,
        ])->assertRedirect(route('admin.post-categories.index'));

        $this->assertDatabaseHas('post_categories', [
            'id' => $category->id,
            'name' => 'Kegiatan Madrasah',
            'slug' => 'kegiatan-madrasah',
            'is_active' => false,
        ]);
    }

    public function test_admin_can_delete_an_unused_post_category(): void
    {
        $this->actingAsAdmin();

        $category = PostCategory::create([
            'name' => 'Informasi',
            'slug' => 'informasi',
            'is_active' => true,
        ]);

        $this->delete(route('admin.post-categories.destroy', $category))
            ->assertRedirect(route('admin.post-categories.index'));

        $this->assertDatabaseMissing('post_categories', ['id' => $category->id]);
    }

    public function test_admin_cannot_delete_a_category_used_by_an_archived_post(): void
    {
        $this->actingAsAdmin();

        $category = PostCategory::create([
            'name' => 'Kabar Madrasah',
            'slug' => 'kabar-madrasah',
            'is_active' => true,
        ]);

        $post = Post::create([
            'post_category_id' => $category->id,
            'title' => 'Kegiatan Semester Ganjil',
            'slug' => 'kegiatan-semester-ganjil',
            'content' => 'Dokumentasi kegiatan semester ganjil MI Islamiyah Syafi\'iyah.',
        ]);
        $post->delete();

        $this->delete(route('admin.post-categories.destroy', $category))
            ->assertRedirect(route('admin.post-categories.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('post_categories', ['id' => $category->id]);
    }
}
