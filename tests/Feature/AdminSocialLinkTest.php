<?php

namespace Tests\Feature;

use App\Models\SocialLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSocialLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_the_social_link_index_page(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.social-links.index'))
            ->assertOk()
            ->assertSee('Sosial Media Sekolah');
    }

    public function test_admin_can_open_create_and_edit_social_link_pages(): void
    {
        $admin = $this->admin();
        $socialLink = $this->socialLink();

        $this->actingAs($admin)
            ->get(route('admin.social-links.create'))
            ->assertOk()
            ->assertSee('Tambah Sosial Media');

        $this->actingAs($admin)
            ->get(route('admin.social-links.edit', $socialLink))
            ->assertOk()
            ->assertSee('Edit Sosial Media')
            ->assertSee($socialLink->platform);
    }

    public function test_admin_can_create_a_social_link(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.social-links.store'), [
                'platform' => 'Instagram',
                'label' => 'Instagram MIIS',
                'url' => 'https://instagram.com/miis',
                'icon' => 'instagram',
                'sort_order' => 1,
                'is_active' => true,
            ])
            ->assertRedirect(route('admin.social-links.index'));

        $this->assertDatabaseHas('social_links', [
            'platform' => 'Instagram',
            'label' => 'Instagram MIIS',
            'url' => 'https://instagram.com/miis',
            'icon' => 'instagram',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }

    public function test_social_link_requires_platform_url_and_status(): void
    {
        $this->from(route('admin.social-links.create'))
            ->actingAs($this->admin())
            ->post(route('admin.social-links.store'), [])
            ->assertRedirect(route('admin.social-links.create'))
            ->assertSessionHasErrors(['platform', 'url', 'is_active']);
    }

    public function test_social_link_validates_a_unique_platform_and_valid_url(): void
    {
        $this->socialLink();

        $this->actingAs($this->admin())
            ->post(route('admin.social-links.store'), [
                'platform' => 'Facebook',
                'url' => 'bukan-url',
                'is_active' => true,
            ])
            ->assertSessionHasErrors('url');

        $this->actingAs($this->admin())
            ->post(route('admin.social-links.store'), [
                'platform' => 'Instagram',
                'url' => 'https://instagram.com/miis-kedua',
                'is_active' => true,
            ])
            ->assertSessionHasErrors('platform');
    }

    public function test_admin_can_update_a_social_link(): void
    {
        $socialLink = $this->socialLink();

        $this->actingAs($this->admin())
            ->put(route('admin.social-links.update', $socialLink), [
                'platform' => 'Facebook',
                'label' => 'Facebook MIIS',
                'url' => 'https://facebook.com/miis',
                'icon' => 'facebook',
                'sort_order' => 3,
                'is_active' => false,
            ])
            ->assertRedirect(route('admin.social-links.index'));

        $socialLink->refresh();

        $this->assertSame('Facebook', $socialLink->platform);
        $this->assertSame('https://facebook.com/miis', $socialLink->url);
        $this->assertFalse($socialLink->is_active);
    }

    public function test_admin_can_delete_a_social_link(): void
    {
        $socialLink = $this->socialLink();

        $this->actingAs($this->admin())
            ->delete(route('admin.social-links.destroy', $socialLink))
            ->assertRedirect(route('admin.social-links.index'));

        $this->assertDatabaseMissing('social_links', ['id' => $socialLink->id]);
    }

    public function test_non_admin_cannot_access_social_link_management(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get(route('admin.social-links.index'))
            ->assertRedirect(route('admin.login'));
    }

    private function admin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    private function socialLink(): SocialLink
    {
        return SocialLink::create([
            'platform' => 'Instagram',
            'label' => 'Instagram MIIS',
            'url' => 'https://instagram.com/miis',
            'icon' => 'instagram',
            'sort_order' => 0,
            'is_active' => true,
        ]);
    }
}
